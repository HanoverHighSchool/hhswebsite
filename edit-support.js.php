<?php

require_once("opendb.php");
$status = loginStatus();

$editable = false;

if ($status & $LoginStatusOK && ($isHomePage ? $status & $LoginStatusCanHome : $status & $LoginStatusCanEdit)) {
?>

var hasEdits = false;
var stopAfter = false;
var editing = false;

var currentNode = null;
var clickedNode = null;
var highlightNew = true;
var highlightNav = false;
var highlight = "#c44";
var highlightN = "#4c4";

var editors = 1;
var inspect = null;

function insertAfter(toInsert, after) {
   var parent = after.parentNode;
   if (parent.lastchild == after) {
      parent.appendChild(newElement);
   } else {
      parent.insertBefore(toInsert, after.nextSibling);
   }
}

function isNav(element) {
   var parent = element.parentNode;
   while (parent != null && parent != document) {
      if (parent.getAttribute) { //Stupid, stupid errors. Should *not* have to check for this
         var pClass = parent.getAttribute("class");
         if (pClass != null) {
            if (pClass.indexOf("nav") != -1)
               return true;
            if (pClass.indexOf("navbar") != -1)
               return true;
         }
      }
      parent = parent.parentNode;
   }
   return false;
}

function isCK(element) {
   var parent = element.parentNode;
   while (parent != null && parent != document) {
      if (parent.getAttribute) { //Stupid, stupid errors. Should *not* have to check for this
         var pClass = parent.getAttribute("class");
         if (pClass != null) {
            if (pClass.indexOf("cke_") != -1)
               return true;
            if (pClass.indexOf("cke_editor_") != -1)
               return true;
         }
      }
      parent = parent.parentNode;
   }
   return false;
}

function getTagger(element) {
   if (element.getAttribute && element.getAttribute("tag") != null)
      return element;
   var parent = element.parentNode;
   while (parent != null && parent != document) {
      if (parent.getAttribute) {
         if (parent.getAttribute("tag") != null)
            return parent;
      }
      parent = parent.parentNode;
   }
   return false;
}

function h(element) {
   if (element.h)
      return;

	if (!element.getAttribute("tag") && !highlightNew)
		return;

   if (isCK(element))
      return false;

   if (isNav(element) && !highlightNav)
      return;

   element.oldHref = element.getAttribute("href");
   if (element.classList.contains("btn")) {
      h(element.parentNode);
      return;
   } else {
      element.oldTC = element.style.color;
      element.oldBG = element.style.backgroundColor;
      element.oldImg = element.style.backgroundImage;
      if (element.getAttribute("tag"))
         element.style.backgroundColor = highlight;
      else
         element.style.backgroundColor = highlightN;
      element.style.backgroundImage = "";
      element.style.color = "#000";
   }
   element.h = true;
}

function u(element) {
   if (!element.h && !element.e)
      return;
   if (element.h) {
      if (element.classList.contains("btn")) {
         u(element.parentNode);
         return;
      } else {
         element.style.backgroundColor = element.oldBG;
         element.style.backgroundImage = element.oldImg;
         element.style.color = element.oldTC;
         element.oldBG = "";
         element.oldImg = "";
         element.oldTC = "";
      }
      element.oldHref = "";
   }
   if (element.e) {
      //TODO: This is totally screwed
      //We don't want any HTML tags!

      var editor = eval("CKEDITOR.instances.editor" + editors);
      if (editor != null) {
         var text = editor.getData();
         editor.destroy();

         editors ++;
      }

//      var s = new Sanitize();
//      s.clean_node(element);

      if (element.innerHTML == "")
         element.innerHTML = "<br>";

      var re = new RegExp(' editor', 'gi');
      element.id = element.id.replace(re, '');
      element.setAttribute("contenteditable", "false");
      if (!element.getAttribute("tag")) {
         element.setAttribute("tag", tags + 1);
         tags ++;
         if (element.classList.contains("btn"))
            button[tags] = new Array(element.value ? element.value : element.innerHTML, element.style.color);
         else
            tag[tags] = element.value ? element.value : element.innerHTML;
      }
      if (element.classList.contains("btn"))
         button[tags][0] = element.value ? element.value : element.innerHTML;
      else
         tag[element.getAttribute("tag")] = element.value ? element.value : element.innerHTML;

      if (inspect != null) {
         inspect.parentNode.removeChild(inspect);
         inspect = null;
      }

      editing = false;
   }
   element.h = false;
   element.e = false;
}

function e(element) {
   if (element.e)
      return false;

	if (!element.getAttribute("tag") && !highlightNew)
		return false;

   if (isCK(element))
      return false;

   if (isNav(element) && !highlightNav)
      return;

   if (element.classList.contains("btn")) {
      e(element.parentNode);
      return;
   }

   element.e = true;
   hasEdits = true;

   var config = {
      skin: 'kama',
      toolbar: [
         { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
         { name: 'format',    items: ['Bold', 'Italic', '-', 'Link', 'Unlink'] },
      ]
   };

   $(element).wrap("<form onsubmit=\"u(clickedNode);\" />");
   $(element).ckeditor(config);

   return true;

   /*
   var textarea = document.createElement("div");
   textarea.setAttribute("id", "editor");
   console.log("Setting width to" + $(element).width());
   textarea.style.width = $(element).width();
   textarea.style.minWidth = $(element).width();
   textarea.style.maxWidth = $(element).width();
   textarea.style.height = $(element).height();

   textarea.innerHTML = strip(element.innerHTML);
   textarea.setAttribute("contenteditable", "true");

   element.innerHTML = "";
   element.appendChild(textarea);
   */
}

function loopSanitize(element, oldText) {
   if (element.innerHTML === oldText) {
      setTimeout(function(){loopSanitize(element, oldText);}, 100);
      return;
   }
   var s = new Sanitize();

   var frag = s.clean_node(element);
   //Do something with frag
   element.innerHTML = frag.textContent;
}

function saveEdits() {
   if (clickedNode)
      u(clickedNode);

   var editLI = document.getElementById("editLI");

   $(editLI).replaceWith($("<div>Saving...</div>"));
   editLI.setAttribute("class", "menu-text");

   var send = "tags=" + encodeURIComponent(tags);
   for (var i = 0; i < tags; i ++) {
      send += "&tag[" + i + "]=" + encodeURIComponent(tag[i + 1]);
   }
   send += "&page=" + curPage;

   $.post("/update.php", send, function() {
      alert("Changes saved!");
      $(editLI).replaceWith($("<a>Save Edits</a>"));
      editLI.removeAttribute("class");
      editLI.setAttribute("onclick", "saveEdits();");
   });
   console.log(send);

   hasEdits = false;
   if (stopAfter)
      stopEditing();
}

function stopEditing(stay) {
   if (hasEdits) {
      if (!confirm("You have unsaved changes. Do you want to discard them?"))
         return;
      stopAfter = true;
//      saveEdits();
//      return;
   }

   if (window.reset) {
      reset();
   }

   if (!stay) {
      var editLI = document.getElementById("editLI");
      var stopLI = document.getElementById("stopLI");

      stopLI.parentNode.parentNode.removeChild(stopLI.parentNode);

      editLI.innerHTML = "Edit This Page";
      editLI.setAttribute("onclick", "loadEdit();");
      editLI.parentNode.removeChild(editLI.parentNode.lastChild);

      window.location.reload();
   }
   return true;
}

function strip(string) {
   var lines = string.split("\n");
   var fin = "";
   for (var i = 0; i < lines.length; i ++) {
      if (i)
         fin += "\n" + $.trim(lines[i]);
      else
         fin = $.trim(lines[i]);
   }
   return fin;
}

function isDescendant(parent, child) {
   if (child == null)
      return false;
   var node = child.parentNode;
   while (node != null) {
      if (node == parent)
         return true;

      node = node.parentNode;
   }
   return false;
}

function editorOK(element) {
   if (element.id === "editor")
      return false;
   if (element.getAttribute("editor-enabled") === "false")
      return false;
   if (element.tagName.toUpperCase() === "BODY")
      return false;
   if (element.tagName.toUpperCase() === "HTML")
      return false;
   if (element.tagName.toUpperCase() === "BR")
      return false;
   if (element.tagName.toUpperCase() === "FOOTER")
      return false;
   if (element.className === "caret")
      return false;
   if (element.className === "inspect")
      return false;
   return true;
}

$('body').mousemove(function(event) {
   if (clickedNode != null)
      return;
   if (!editorOK(event.target))
      return;
   if (editing)
      return;
   if ((event.target = getTagger(event.target)) === false)
      return;
   if (event.target !== currentNode) {
      if (currentNode != null) {
         u(currentNode);
      }
      currentNode = event.target;
      h(currentNode);
   }
});

$('body').click(function(event) {
   if (clickedNode == null)
      return;
   if (event.target.id === "editor")
      return;
   if (isDescendant(clickedNode, event.target))
      return;
   if (isDescendant(event.target, inspect))
      return;
   if (event.target.className === "inspect")
      return;
   if (isCK(event.target))
      return;
   if (event.target !== clickedNode) {
      u(currentNode);
      clickedNode = null;
      currentNode = null;
   }
});

$('body').dblclick(function(event) {
   if (!editorOK(event.target))
      return;
   if ((event.target = getTagger(event.target)) === false)
      return;
   if (event.target.classList.contains("btn")) {
      event.target = event.target.parentNode;
   }
   if (event.target !== clickedNode) {
      if (clickedNode != null) {
         u(currentNode);
      }
      if (e(currentNode)) {
         editing = true;
         clickedNode = event.target;
         h(currentNode);
      }
   }
});

function editorInit() {
   var editLI = document.getElementById("editLI");

   editLI.innerHTML = "Save Edits";
   editLI.setAttribute("onclick", "saveEdits();");

   var newLI  = document.createElement("li");
   var newLI2 = document.createElement("li");
   var liA  = document.createElement("a");
   var liA2 = document.createElement("a");

   liA.setAttribute("href", "#");
   liA.setAttribute("id", "stopLI");
   liA.setAttribute("onclick", "stopEditing();");
   liA.innerHTML = "Stop Editing";

   liA2.setAttribute("href", "#");
   liA2.setAttribute("id", "helpLI");
   liA2.setAttribute("onclick", "helpEditing();");
   liA2.innerHTML = "Editing Help...";

   newLI.appendChild(liA);
   newLI2.appendChild(liA2);

   insertAfter(newLI, editLI.parentNode);
   insertAfter(newLI2, newLI);

   var As = document.getElementsByTagName("A");
   for (var i = 0; i < As.length; i ++) {
      if (hasEdits && isNav(As[i]) && As[i].getAttribute("href") != null && !As[i].classList.contains("dropdown-toggle")) {
         var href = As[i].getAttribute("href");
         As[i].onclick = function() {
            if (stopEditing(true))
               window.location.href = href;
         }
      }
      As[i].removeAttribute("href");
   }
}
editorInit();
<?php
} else {
?>
window.location.href = "/nope.php";
<?php
}
?>

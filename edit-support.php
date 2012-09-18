<?php

require_once("opendb.php");
$status = loginStatus();

$editable = false;

if ($status & $LoginStatusOK && ($isHomePage ? $status & $LoginStatusCanHome : $status & $LoginStatusCanEdit)) {
?>
var hasEdits = false;
var stopAfter = false;

function insertAfter(toInsert, after) {
   var parent = after.parentNode;
   if (parent.lastchild == after) {
      parent.appendChild(newElement);
   } else {
      parent.insertBefore(toInsert, after.nextSibling);
   }
}

var currentNode = null;
var clickedNode = null;

function h(element) {
   if (element.h)
      return;
    
	if (!element.getAttribute("tag"))
		return;
	
   element.oldHref = element.getAttribute("href");
   if (!element.classList.contains("btn")) {
      element.oldTC = element.style.color;
      element.oldBG = element.style.backgroundColor;
      element.oldImg = element.style.backgroundImage;
      if (element.getAttribute("tag"))
         element.style.backgroundColor = "#ff9";
      else
         element.style.backgroundColor = "#9f9";
      element.style.backgroundImage = "";
      element.style.color = "#000";
   }
   element.h = true;
}

function u(element) {
   if (!element.h && !element.e)
      return;
   if (element.h) {
      if (!element.classList.contains("btn")) {
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
      var re = new RegExp(' editor', 'gi');
      element.id = element.id.replace(re, '');
      element.setAttribute("contenteditable", "false");
      if (!element.getAttribute("tag")) {
         element.setAttribute("tag", tags + 1);
         tags ++;
         tag[tags] = element.value ? element.value : element.innerHTML;
      }
      tag[element.getAttribute("tag")] = element.value ? element.value : element.innerHTML;
   }
   element.h = false;
   element.e = false;
}

function e(element) {
   if (element.e)
      return;

	if (!element.getAttribute("tag"))
		return;

   element.e = true;
   hasEdits = true;

   if (!element.getAttribute("tag")) {
      element.setAttribute("tag", tags + 1);
      tags ++;
      tag[tags] = element.value ? element.value : element.innerHTML;
   }

   element.id += " editor";
   element.setAttribute("contenteditable", "true");

   element.focus();

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

function saveEdits() {
   if (clickedNode)
      u(clickedNode);

   var send = "tags=" + encodeURIComponent(tags);
   for (var i = 0; i < tags; i ++) {
      send += "&tag[" + i + "]=" + encodeURIComponent(tag[i + 1]);
   }

   $.post("/update.php", send, function() {
      alert("Changes saved!");
   });
   console.log(send);

   hasEdits = false;
   if (stopAfter)
      stopEditing();
}

function stopEditing() {
   if (hasEdits) {
      if (!confirm("You have unsaved changes. Do you want to discard them?"))
         return;
      stopAfter = true;
      saveEdits();
      return;
   }

   if (window.reset) {
      reset();
   }

   var editLI = document.getElementById("editLI");
   var stopLI = document.getElementById("stopLI");

   stopLI.parentNode.parentNode.removeChild(stopLI.parentNode);

   editLI.innerHTML = "Edit This Page";
   editLI.setAttribute("onclick", "loadEdit();");
   editLI.parentNode.removeChild(editLI.parentNode.lastChild);

   window.location.reload();
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
   if (element.tagName.toUpperCase() === "FOOTER")
      return false;
   if (element.className === "caret")
      return false;
   return true;
}

$('body').mousemove(function(event) {
   if (clickedNode != null)
      return;
   if (!editorOK(event.target))
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
   if (event.target !== clickedNode) {
      u(currentNode);
      clickedNode = null;
   }
});

$('body').dblclick(function(event) {
   if (!editorOK(event.target))
      return;
   if (event.target !== clickedNode) {
      if (clickedNode != null) {
         u(currentNode);
      }
      clickedNode = event.target;
      h(currentNode);
      e(currentNode);
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
      As[i].href = null;
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

/* Main.js */

/* Stylesheet hax */
function changecss(myclass,element,value) {
	var CSSRules
	if (document.all) {
		CSSRules = 'rules'
	}
	else if (document.getElementById) {
		CSSRules = 'cssRules'
	}
	for (var j = 0; j < document.styleSheets.length; j ++) {
	   if (document.styleSheets[j][CSSRules] == null)
	      continue;
      for (var i = 0; i < document.styleSheets[j][CSSRules].length; i++) {
         if (document.styleSheets[j][CSSRules][i].selectorText == myclass) {
            document.styleSheets[j][CSSRules][i].style[element] = value;
            break;
         }
      }
	}
}

var updateTimeout;

function update() {
   updateTimeout = setTimeout("update();", 500);
   //Change max-height properties for the navbar menus
   changecss(".navbar .dropdown-menu", "max-height", document.body.clientHeight - 154 + "px");
}

//Don't forget to call it!
update();
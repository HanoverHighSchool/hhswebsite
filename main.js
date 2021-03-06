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
   if (document.body)
      changecss(".navbar .dropdown-menu", "max-height", document.body.clientHeight - 154 + "px");
}

//Don't forget to call it!
update();

function checkElement(element) {
   if (element == null)
      return;
   for (var i = 0; i < element.childNodes.length; i ++)
      checkElement(element.childNodes[i]);
   if (!(element.getAttribute))
      return;
   if (element.getAttribute("tag") !== null) {
      var val = tag[parseInt(element.getAttribute("tag"))];
      val = val.replace("\n", "<br>");

      //Buttons
      val = val.replace(/\[([0-9a-f]*)\|([^\]]*)\|([^\]]*)\]/gi,
                        "<a class=\"btn\" href=\"$2\">$3</a>");

      element.innerHTML = val;
   }
}

//http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
/**
 * Converts an RGB color value to HSL. Conversion formula
 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
 * Assumes r, g, and b are contained in the set [0, 255] and
 * returns h, s, and l in the set [0, 1].
 *
 * @param   Number  r       The red color value
 * @param   Number  g       The green color value
 * @param   Number  b       The blue color value
 * @return  Array           The HSL representation
 */
function rgbToHsl(r, g, b){
    r /= 255, g /= 255, b /= 255;
    var max = Math.max(r, g, b), min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;

    if(max == min){
        h = s = 0; // achromatic
    }else{
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch(max){
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }

    return [h, s, l];
}

/**
 * Converts an HSL color value to RGB. Conversion formula
 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
 * Assumes h, s, and l are contained in the set [0, 1] and
 * returns r, g, and b in the set [0, 255].
 *
 * @param   Number  h       The hue
 * @param   Number  s       The saturation
 * @param   Number  l       The lightness
 * @return  Array           The RGB representation
 */
function hslToRgb(h, s, l){
    var r, g, b;

    if(s == 0){
        r = g = b = l; // achromatic
    }else{
        function hue2rgb(p, q, t){
            if(t < 0) t += 1;
            if(t > 1) t -= 1;
            if(t < 1/6) return p + (q - p) * 6 * t;
            if(t < 1/2) return q;
            if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        }

        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }

    return [r * 255, g * 255, b * 255];
}
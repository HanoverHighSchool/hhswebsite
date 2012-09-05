jQuery(document).ready(function($) {
  if (typeof operamini != 'undefined') {  //check to see if operamini a JS var added by Opera Mini browser exists so other browsers won't error
    if(operamini) { //this should only work for Opera Mini
      $('#menuButton').click(function(e){ //bind click which Opera Mini likes better
        e.preventDefault(); //prevent default action
        $(this).collapse({ //manually add collapse to the targeted button
          toggle:true
        });
      });
    }
  }
});

$(document).ready(function() {
  if(operamini) {
    $('#menuButton').click(function() {});
  }
});
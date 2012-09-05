Mousetrap.bind('enter', function() { parseString(document.getElementById('bigrectangle').value); });

//resize the textbox on window resize
function resizeTo() {
	var bigRect = document.getElementById('bigrectangle');
	var searchBtn = document.getElementById('endbtn');
	var searchIcon = document.getElementById('sicon');
	if (document.body.offsetWidth > 1000) {
		//enough space to have big fonts
		bigRect.style.cssText = bigRect.style.cssText + 'font-size:90px !important; width:'+($('#hero-unit').width()+4)+'px !important;';
		searchBtn.style.cssText = searchBtn.style.cssText + 'font-size:91px !important; line-height:93px !important; width:118px !important; height:118px !important;';
		searchIcon.style.cssText = "margin-left: 0px !important;";
	}
	else if (document.body.offsetWidth > 400 && document.body.offsetWidth < 1000) {
		bigRect.style.cssText = bigRect.style.cssText + 'font-size:50px !important; width:'+($('#hero-unit').width()+50)+'px !important;';
		searchBtn.style.cssText = searchBtn.style.cssText + 'font-size:51px !important; line-height:53px !important; width:70px !important; height:70px !important;';
		searchIcon.style.cssText = "margin-left: 0px !important;";
	}
	else {
		bigRect.style.cssText = bigRect.style.cssText + 'font-size:25px !important; width:'+($('#hero-unit').width()+81)+'px !important;';
		searchBtn.style.cssText = searchBtn.style.cssText + 'font-size:25px !important; line-height:26px !important; width:40px !important; height:40px !important;';
		searchIcon.style.cssText = "margin-left: -4px !important;";
	}
}

//On document ready, fix text box to screen size
$(document).ready(function() {
	resizeTo();
});

//On window resize, fix text box to screen size
$(window).resize(function() {
  resizeTo();
});

//When the document is ready, use Mixpanel and add an event handler to the BigRectanle
$(document).ready(function() {
	mixpanel.track("PageLoad", {"screenheight":screen.height, "screenwidth":screen.width, "useragent": navigator.userAgent, "windowheight": $(window).height(), "windowwidth": $(window).width(), "browser": BrowserDetect.browser, "browsernum": BrowserDetect.version, "os": BrowserDetect.OS});
    $("#bigrectangle").keyup(function(event){
		if(event.keyCode == 13) {
			parseString(document.getElementById('bigrectangle').value);
		}
	});
});
(function ($) {
    "use strict";
	/* slick nav */
	$('#main-menu').slicknav({prependTo:'#responsive-menu',label:'', closeOnClick:true});
	
	/* Stickey Header */
	window.onscroll = function() {myFunction()};
	var navbar = document.getElementById("main-navbar");
	var sticky = navbar.offsetTop;
	function myFunction(){
		if (window.pageYOffset >= sticky) {
			navbar.classList.add("sticky-header")
		} else {
			navbar.classList.remove("sticky-header");
		}
	}
	
	/* Top Menu */
	$(document).on('click','.navbar-nav li a, #responsive-menu ul li a',function(){
		if($(this).hasClass("has-popup")) return false;
		var id = $(this).attr('href');
		if($(id).length) {
			var h = parseFloat($(id).offset().top);
			$('body,html').stop().animate({
				scrollTop: h - 70
			}, 800);
			return false;
		}
		
	});
})(jQuery);
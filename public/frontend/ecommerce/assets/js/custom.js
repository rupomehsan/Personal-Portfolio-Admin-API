$(document).ready(function ($) {


	$('.testimonial-carousel').owlCarousel({
		loop:true,
		margin: 25,
		nav:true,
		dots: false,
		navText: ["<i class='ti-arrow-left'></i>","<i class='ti-arrow-right'></i>"],
		responsive:{
			 0:{
				  items:1
			 },
			 1200:{
				  items:3
			 }
		}
  })
	$(".close_icon ").click(function () {
		$(".sideNav").toggleClass("open");
	});

	$(".vdo-link").YouTubePopUp()

	$(".menu_call").click(function () {
		$(".sideNav").toggleClass("open");
	});

	$(".mega-dropdown-toggle").click(function () {
		$(".mega-dropdown-toggle").toggleClass("open");
		$(".dropdown-menu").removeClass("show");
	});

	jQuery(function ($) {

		$('#mega-dropdown-menu').click(function (e) {
			var getClass = $(".mega-dropdown-toggle").hasClass("open");

			if (getClass == true) {
				$(".mega-dropdown-menu").addClass("open");
				e.stopPropagation();
			} else {
				//$(".mega-dropdown-toggle").addClass("open");
				$(".mega-dropdown-toggle").removeClass("open");
				$(".mega-dropdown-menu").removeClass("open");
				e.stopPropagation();
			}

			e.stopPropagation(); // Prevent bubbling
		});
		$(document).click(function () {
			$(".mega-dropdown-toggle").removeClass("open");
			$(".mega-dropdown-menu").removeClass("open");
		});
		$(document).click(function () {
			$(".mega-dropdown-toggle").addClass("close");
			$(".mega-dropdown-menu").addClass("close");
		});
	});

	// Sticky menu optimization with throttling
	var scrollThreshold = 80;
	var headerSticky = false;
	var lastScrollTime = 0;
	var scrollDelay = 50; // Throttle scroll events to every 50ms

	$(window).scroll(function () {
		var currentTime = new Date().getTime();

		// Throttle scroll event
		if (currentTime - lastScrollTime < scrollDelay) {
			return;
		}
		lastScrollTime = currentTime;

		var scroll = $(window).scrollTop();

		// Show/hide sticky menu based on scroll threshold
		if (scroll > scrollThreshold) {
			if (!headerSticky) {
				$(".header_area").addClass("bg-white end-0 navbar_fixed position-fixed start-0 top-0");
				headerSticky = true;
			}
		} else {
			if (headerSticky) {
				$(".header_area").removeClass("bg-white end-0 navbar_fixed position-fixed start-0 top-0");
				headerSticky = false;
			}
		}

		if (scroll > 300) {
			$('.gotoTop').addClass('show');
		} else {
			$('.gotoTop').removeClass('show');
		}
	});

	$('.gotoTop').on('click', function (e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, '300');
	});
	
});
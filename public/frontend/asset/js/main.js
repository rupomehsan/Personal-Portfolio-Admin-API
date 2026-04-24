/**
 * Main JavaScript File - techparkit Website
 * Handles: Mega Dropdown, Video Modals, Tabs, and Owl Carousel
 */

(function ($) {
  "use strict";

  $(document).ready(function () {
    console.log("Main.js loaded - jQuery version:", $.fn.jquery);
    console.log(
      "Owl Carousel available:",
      typeof $.fn.owlCarousel !== "undefined",
    );
    console.log("Bootstrap available:", typeof bootstrap !== "undefined");

    // ==========================================
    // 1. MEGA DROPDOWN MENU FUNCTIONALITY
    // ==========================================

    // Toggle mega dropdown menu on single click
    $(".mega-dropdown-toggle").on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      var $parent = $(this).closest(".mega-dropdown");
      var $menu = $parent.find(".mega-dropdown-menu");
      var isCurrentlyOpen = $menu.hasClass("show");

      // Close all other dropdowns first
      $(".mega-dropdown")
        .not($parent)
        .find(".mega-dropdown-menu")
        .removeClass("show");
      $(".mega-dropdown-toggle").not(this).attr("aria-expanded", "false");

      // Toggle the current dropdown
      if (isCurrentlyOpen) {
        $menu.removeClass("show");
        $(this).attr("aria-expanded", "false");
      } else {
        $menu.addClass("show");
        $(this).attr("aria-expanded", "true");
      }
    });

    // Close dropdown when clicking outside
    $(document).on("click", function (e) {
      if (!$(e.target).closest(".mega-dropdown").length) {
        $(".mega-dropdown-menu").removeClass("show");
        $(".mega-dropdown-toggle").attr("aria-expanded", "false");
      }
    });

    // Prevent dropdown from closing when clicking inside
    $(".mega-dropdown-menu").on("click", function (e) {
      e.stopPropagation();
    });

    // ==========================================
    // 2. STICKY HEADER FUNCTIONALITY
    // ==========================================

    $(window).on("scroll", function () {
      if ($(window).scrollTop() > 100) {
        $(".header_area").addClass("scrolled");
      } else {
        $(".header_area").removeClass("scrolled");
      }
    });

    // ==========================================
    // 3. MOBILE MENU FUNCTIONALITY
    // ==========================================

    // Create overlay if it doesn't exist
    if ($(".mobile-menu-overlay").length === 0) {
      $("body").append('<div class="mobile-menu-overlay"></div>');
    }

    var menuScrollPosition = 0;

    function lockBodyScroll() {
      menuScrollPosition =
        window.pageYOffset || document.documentElement.scrollTop;
      $("body")
        .addClass("menu-open")
        .css("top", -menuScrollPosition + "px");
    }

    function unlockBodyScroll() {
      $("body").removeClass("menu-open").css("top", "");
      window.scrollTo(0, menuScrollPosition);
    }

    // Open mobile menu
    $(".menu_call").on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(".sideNav").addClass("open");
      $(".mobile-menu-overlay").addClass("active");
      lockBodyScroll();
    });

    // Close mobile menu
    function closeMobileMenu() {
      $(".sideNav").removeClass("open");
      $(".mobile-menu-overlay").removeClass("active");
      unlockBodyScroll();
    }

    // Close button
    $(".close_icon").on("click", function (e) {
      e.preventDefault();
      closeMobileMenu();
    });

    // Close when clicking overlay
    $(".mobile-menu-overlay").on("click", function () {
      closeMobileMenu();
    });

    // Close on escape key
    $(document).on("keydown", function (e) {
      if (e.key === "Escape" && $(".sideNav").hasClass("open")) {
        closeMobileMenu();
      }
    });

    // Close mobile menu when clicking on links
    $(".mobileNav a").on("click", function (e) {
      var href = $(this).attr("href") || "";
      var hashIndex = href.indexOf("#");
      var hash = hashIndex >= 0 ? href.slice(hashIndex) : "";

      // Close menu after a short delay for better UX
      setTimeout(function () {
        closeMobileMenu();
      }, 300);

      // Handle internal links (anchors)
      if (hash && hash.length > 1) {
        e.preventDefault();
        var target = $(hash);
        if (target.length) {
          closeMobileMenu();
          setTimeout(function () {
            var headerOffset = $(".header_area").outerHeight() || 0;
            $("html, body").animate(
              {
                scrollTop: target.offset().top - headerOffset - 10,
              },
              800,
            );
          }, 400);
        } else {
          window.location.href = href;
        }
      }
    });

    // Close mobile menu when clicking on collapsible buttons
    $(".mobileNav .btn-toggle").on("click", function (e) {
      // Just toggle collapse, don't close menu
      e.stopPropagation();
    });

    // ==========================================
    // 4. VIDEO POPUP / MODAL FUNCTIONALITY
    // ==========================================

    var currentVideoUrl = "";
    var currentVideoId = "";

    if ($("#videoModal").length === 0) {
      $("body").append(
        '<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">' +
          '<div class="modal-dialog modal-dialog-centered modal-xl">' +
          '<div class="modal-content border-0" style="background:#000;">' +
          '<div class="modal-header border-0 pb-0">' +
          '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>' +
          "</div>" +
          '<div class="modal-body p-2 pt-0">' +
          '<div id="videoPlayerWrap" class="ratio ratio-16x9 bg-black">' +
          '<div id="ytPlayer"></div>' +
          "</div>" +
          '<div id="videoPreviewFallback" class="d-none text-center text-white">' +
          '<div class="ratio ratio-16x9 overflow-hidden rounded-3 bg-dark mb-4">' +
          '<img id="videoPreviewImage" src="" alt="Video preview" class="w-100 h-100" style="object-fit:cover;">' +
          "</div>" +
          '<h4 class="mb-2 text-white">Video preview available</h4>' +
          '<p id="videoPreviewMessage" class="text-secondary mb-4">This video cannot be played inline in the current environment.</p>' +
          '<a id="watchOnYoutubeBtn" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-danger px-4 py-2">Watch on YouTube</a>' +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>",
      );
    }

    $("#videoModal").on("hidden.bs.modal", function () {
      resetVideoModal();
    });

    $(document).on("click", ".vdo-link", function (e) {
      e.preventDefault();

      currentVideoUrl = $(this).attr("href") || "";
      currentVideoId = extractYouTubeId(currentVideoUrl);

      if (!currentVideoId) {
        window.open(currentVideoUrl, "_blank", "noopener,noreferrer");
        return;
      }

      var modal = new bootstrap.Modal(document.getElementById("videoModal"));
      modal.show();

      $("#videoModal").one("shown.bs.modal", function () {
        if (window.location.protocol === "file:") {
          showVideoPreview(
            currentVideoUrl,
            currentVideoId,
            "YouTube blocks embedded playback when the site is opened as a local file. Run the site from http://localhost or your live domain.",
          );
          return;
        }

        loadYoutubeApi(function () {
          loadYTPlayer(currentVideoId, currentVideoUrl);
        });
      });
    });

    function resetVideoModal() {
      if (window._ytPlayer) {
        try {
          window._ytPlayer.destroy();
        } catch (error) {
          console.log("YT destroy error:", error);
        }
        window._ytPlayer = null;
      }

      currentVideoUrl = "";
      currentVideoId = "";
      $("#ytPlayer").replaceWith('<div id="ytPlayer"></div>');
      $("#videoPlayerWrap").removeClass("d-none");
      $("#videoPreviewFallback").addClass("d-none");
      $("#videoPreviewImage").attr("src", "");
      $("#watchOnYoutubeBtn").attr("href", "#");
    }

    function loadYoutubeApi(callback) {
      if (typeof YT !== "undefined" && typeof YT.Player !== "undefined") {
        callback();
        return;
      }

      window._onYoutubeApiReadyQueue = window._onYoutubeApiReadyQueue || [];
      window._onYoutubeApiReadyQueue.push(callback);

      if (!document.getElementById("youtubeIframeApiScript")) {
        var script = document.createElement("script");
        script.id = "youtubeIframeApiScript";
        script.src = "https://www.youtube.com/iframe_api";
        document.head.appendChild(script);
      }

      window.onYouTubeIframeAPIReady = function () {
        var queue = window._onYoutubeApiReadyQueue || [];
        while (queue.length) {
          var queuedCallback = queue.shift();
          if (typeof queuedCallback === "function") {
            queuedCallback();
          }
        }
      };
    }

    function loadYTPlayer(videoId, videoUrl) {
      var playerVars = {
        autoplay: 1,
        rel: 0,
        modestbranding: 1,
        playsinline: 1,
      };

      if (window.location.origin && window.location.protocol !== "file:") {
        playerVars.origin = window.location.origin;
      }

      window._ytPlayer = new YT.Player("ytPlayer", {
        videoId: videoId,
        playerVars: playerVars,
        events: {
          onError: function (event) {
            var errorMessage =
              event.data === 153
                ? "YouTube needs a valid website origin/referrer for this video. Use a live domain or local server, and make sure embedding is allowed in YouTube Studio."
                : "This YouTube video cannot be played inline right now. A preview is shown instead.";

            showVideoPreview(videoUrl, videoId, errorMessage);
          },
        },
      });
    }

    function showVideoPreview(videoUrl, videoId, message) {
      $("#videoPlayerWrap").addClass("d-none");
      $("#videoPreviewFallback").removeClass("d-none");
      $("#videoPreviewImage").attr(
        "src",
        "https://i.ytimg.com/vi/" + videoId + "/hqdefault.jpg",
      );
      $("#videoPreviewMessage").text(message);
      $("#watchOnYoutubeBtn").attr("href", videoUrl);
    }

    function extractYouTubeId(url) {
      var match = url.match(
        /(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/,
      );
      return match ? match[1] : null;
    }

    // ==========================================
    // 5. BOOTSTRAP TABS/PILLS FUNCTIONALITY
    // ==========================================

    // Manual tab initialization (if Bootstrap auto-init fails)
    $('[data-bs-toggle="pill"]').on("click", function (e) {
      e.preventDefault();
      var tab = new bootstrap.Tab(this);
      tab.show();
    });

    // Ensure first tab is active on page load
    $("#pills-tab .nav-link:first, #pills-megaMenu .nav-link:first").addClass(
      "active",
    );
    $(
      "#pills-tabContent .tab-pane:first, #pills-megaMenuContent .tab-pane:first",
    ).addClass("show active");

    // ==========================================
    // 6. OWL CAROUSEL INITIALIZATION
    // ==========================================

    if (typeof $.fn.owlCarousel !== "undefined") {
      // Client Testimonial Carousel (Customer Feedback)
      if ($(".client_carousel").length > 0) {
        $(".client_carousel").owlCarousel({
          loop: true,
          margin: 30,
          nav: true,
          dots: true,
          autoplay: true,
          autoplayTimeout: 5000,
          autoplayHoverPause: true,
          items: 1,
          navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>',
          ],
          responsive: {
            0: {
              items: 1,
              nav: true,
            },
            768: {
              items: 1,
              nav: true,
            },
            992: {
              items: 1,
              nav: true,
            },
          },
        });

        console.log("Client carousel initialized successfully");
      }

      // Generic owl carousel initialization for other carousels
      $(".owl-carousel:not(.client_carousel)").each(function () {
        var $carousel = $(this);
        var items = $carousel.data("items") || 4;
        var itemsTablet = $carousel.data("items-tablet") || 3;
        var itemsMobile = $carousel.data("items-mobile") || 1;
        var margin = $carousel.data("margin") || 30;
        var loop = $carousel.data("loop") !== false;
        var autoplay = $carousel.data("autoplay") !== false;
        var nav = $carousel.data("nav") !== false;
        var dots = $carousel.data("dots") !== false;

        $carousel.owlCarousel({
          loop: loop,
          margin: margin,
          nav: nav,
          dots: dots,
          autoplay: autoplay,
          autoplayTimeout: 5000,
          autoplayHoverPause: true,
          navText: [
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>',
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>',
          ],
          responsive: {
            0: {
              items: itemsMobile,
            },
            768: {
              items: itemsTablet,
            },
            992: {
              items: items,
            },
          },
        });
      });
    } else {
      console.warn("Owl Carousel plugin is not loaded");
    }

    // ==========================================
    // 7. WOW.JS ANIMATION INITIALIZATION
    // ==========================================

    if (typeof WOW !== "undefined") {
      new WOW().init();
      console.log("WOW.js initialized");
    } else {
      console.warn("WOW.js is not loaded");
    }

    // ==========================================
    // 8. SMOOTH SCROLL FOR ANCHOR LINKS
    // ==========================================

    $('a[href^="#"]').on("click", function (e) {
      var target = $(this.getAttribute("href"));
      if (target.length) {
        e.preventDefault();
        $("html, body")
          .stop()
          .animate(
            {
              scrollTop: target.offset().top - 100,
            },
            1000,
          );
      }
    });

    // ==========================================
    // 9. HEADER SCROLL EFFECT
    // ==========================================

    $(window).on("scroll", function () {
      if ($(this).scrollTop() > 100) {
        $("header").addClass("sticky");
      } else {
        $("header").removeClass("sticky");
      }
    });

    // ==========================================
    // 10. GO TO TOP BUTTON FUNCTIONALITY
    // ==========================================

    // Show / hide Go-to-top button and smooth-scroll to header
    var $goto = $(".gotoTop");

    console.log("gotoTop button found:", $goto.length);

    function toggleGoto() {
      var scrollTop = $(window).scrollTop();
      if (scrollTop > 300) {
        $goto.addClass("show");
        // console.log("Adding show class, scroll:", scrollTop);
      } else {
        $goto.removeClass("show");
        // console.log("Removing show class, scroll:", scrollTop);
      }
    }

    // initial check
    toggleGoto();

    $(window).on("scroll", function () {
      toggleGoto();
    });

    // Click -> smooth scroll to top
    $(document).on("click", ".gotoTop", function (e) {
      e.preventDefault();
      e.stopPropagation();
      console.log("gotoTop clicked!");

      // Use both methods for better compatibility
      $("html, body").stop().animate({ scrollTop: 0 }, 800, "swing");

      // Also use native smooth scroll as fallback
      window.scrollTo({
        top: 0,
        behavior: "smooth",
      });
    });

    // ==========================================
    // 11. QUOTE FORM VALIDATION & SUBMISSION
    // ==========================================

    // Bootstrap form validation
    var quoteForm = document.getElementById("quoteForm");

    if (quoteForm) {
      quoteForm.addEventListener("submit", function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (quoteForm.checkValidity()) {
          // Get form data
          var formData = {
            name: document.getElementById("quoteName").value,
            email: document.getElementById("quoteEmail").value,
            phone: document.getElementById("quotePhone").value,
            company: document.getElementById("quoteCompany").value,
            service: document.getElementById("quoteService").value,
            budget: document.getElementById("quoteBudget").value,
            message: document.getElementById("quoteMessage").value,
          };

          // Log form data (replace with actual submission logic)
          console.log("Quote Form Submitted:", formData);

          // Show success message
          showFormSuccess();

          // Reset form
          quoteForm.reset();
          quoteForm.classList.remove("was-validated");

          // Close modal after 2 seconds
          setTimeout(function () {
            var modal = bootstrap.Modal.getInstance(
              document.getElementById("appointment"),
            );
            if (modal) {
              modal.hide();
            }
          }, 2000);
        }

        quoteForm.classList.add("was-validated");
      });

      // Real-time validation feedback
      var inputs = quoteForm.querySelectorAll("input, select, textarea");
      inputs.forEach(function (input) {
        input.addEventListener("blur", function () {
          if (this.checkValidity()) {
            this.classList.remove("is-invalid");
            this.classList.add("is-valid");
          } else {
            this.classList.remove("is-valid");
            this.classList.add("is-invalid");
          }
        });
      });
    }

    // Show success message function
    function showFormSuccess() {
      var formContainer = document.querySelector("#quoteForm");
      if (!formContainer) return;

      var successMsg = document.createElement("div");
      successMsg.className = "alert alert-success text-center mb-0";
      successMsg.innerHTML =
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' +
        "<strong>Success!</strong> Your quote request has been submitted. We'll contact you within 24 hours.";

      // Temporarily hide form and show success
      formContainer.style.display = "none";
      formContainer.parentNode.insertBefore(successMsg, formContainer);

      setTimeout(function () {
        successMsg.remove();
        formContainer.style.display = "block";
      }, 2500);
    }
  }); // End document ready

  // ==========================================
  // 11. WINDOW LOAD EVENTS
  // ==========================================

  $(window).on("load", function () {
    // Add any window load specific code here
  });

  // ==========================================
  // 12. BANNER VIDEO MODAL FUNCTIONALITY
  // ==========================================

  // Handle video modal open
  var videoModal = document.getElementById("videoModal");
  if (videoModal) {
    videoModal.addEventListener("show.bs.modal", function (event) {
      // Set the YouTube video URL with autoplay parameter
      var videoFrame = document.getElementById("videoFrame");
      if (videoFrame) {
        // YouTube video ID - change this if needed
        var videoId = "mmXakm_koNk";
        videoFrame.src =
          "https://www.youtube.com/embed/" +
          videoId +
          "?autoplay=1&rel=0&modestbranding=1";
      }
    });

    // Handle video modal close - stop the video
    videoModal.addEventListener("hide.bs.modal", function (event) {
      var videoFrame = document.getElementById("videoFrame");
      if (videoFrame) {
        videoFrame.src = "";
      }
    });
  }
})(jQuery);

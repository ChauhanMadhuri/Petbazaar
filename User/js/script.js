(function($) {

  "use strict";

  var initPreloader = function() {
    $(document).ready(function($) {
    var Body = $('body');
        Body.addClass('preloader-site');
    });
    $(window).load(function() {
        $('.preloader-wrapper').fadeOut();
        $('body').removeClass('preloader-site');
    });
  }

  // init Chocolat light box
	var initChocolat = function() {
		Chocolat(document.querySelectorAll('.image-link'), {
		  imageSize: 'contain',
		  loop: true,
		})
	}

 var initSwiper = function() {

    var swiper = new Swiper(".main-swiper", {
  speed: 500,
  loop: true,

  autoplay: {
    delay: 3000,          // 3 second pachi slide change thase
    disableOnInteraction: false,
  },

  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});

    var bestselling_swiper = new Swiper(".bestselling-swiper", {
      slidesPerView: 4,
      spaceBetween: 30,
      speed: 500,
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        991: {
          slidesPerView: 4,
        },
      }
    });

    var testimonial_swiper = new Swiper(".testimonial-swiper", {
      slidesPerView: 1,
      speed: 500,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });

    var products_swiper = new Swiper(".products-carousel", {
      slidesPerView: 4,
      spaceBetween: 30,
      speed: 500,
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        991: {
          slidesPerView: 4,
        },

      }
    });

  }

  var initProductQty = function(){

    $('.product-qty').each(function(){

      var $el_product = $(this);
      var quantity = 0;

      $el_product.find('.quantity-right-plus').click(function(e){
          e.preventDefault();
          var quantity = parseInt($el_product.find('#quantity').val());
          $el_product.find('#quantity').val(quantity + 1);
      });

      $el_product.find('.quantity-left-minus').click(function(e){
          e.preventDefault();
          var quantity = parseInt($el_product.find('#quantity').val());
          if(quantity>0){
            $el_product.find('#quantity').val(quantity - 1);
          }
      });

    });

  }

  // init jarallax parallax
  var initJarallax = function() {
    jarallax(document.querySelectorAll(".jarallax"));

    jarallax(document.querySelectorAll(".jarallax-keep-img"), {
      keepImg: true,
    });
  }

  // Text and Accessibility Optimization
  var optimizeTextRendering = function() {
    // Improve text contrast and readability
    function adjustTextContrast() {
      var elements = document.querySelectorAll('p, span, div');
      elements.forEach(function(el) {
        el.style.fontSmoothing = 'antialiased';
        el.style.webkitFontSmoothing = 'antialiased';
        el.style.textRendering = 'optimizeLegibility';
      });
    }

    // Add ARIA labels for better accessibility
    function enhanceAccessibility() {
      var links = document.querySelectorAll('a');
      links.forEach(function(link) {
        if (!link.getAttribute('aria-label')) {
          link.setAttribute('aria-label', link.textContent || 'Link');
        }
      });
    }

    // Dynamic font loading indicator
    function fontLoadingIndicator() {
      document.documentElement.classList.add('fonts-loading');
      document.fonts.ready.then(function() {
        document.documentElement.classList.remove('fonts-loading');
        document.documentElement.classList.add('fonts-loaded');
      });
    }

    adjustTextContrast();
    enhanceAccessibility();
    fontLoadingIndicator();
  };

  // Device and Performance Optimization
  var initResponsiveHandlers = function() {
    // Touch and click event normalization
    var touchStartX = 0;
    var touchEndX = 0;

    document.addEventListener('touchstart', function(event) {
      touchStartX = event.changedTouches[0].screenX;
    }, false);

    document.addEventListener('touchend', function(event) {
      touchEndX = event.changedTouches[0].screenX;
      handleSwipe();
    }, false);

    function handleSwipe() {
      var swiped = 'swiped: ';
      if (touchEndX < touchStartX) {
        console.log(swiped + 'left!');
      }
      if (touchEndX > touchStartX) {
        console.log(swiped + 'right!');
      }
    }

    // Debounce function for performance
    function debounce(func, wait) {
      var timeout;
      return function() {
        var context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(function() {
          func.apply(context, args);
        }, wait);
      };
    }

    // Responsive resize handler
    var handleResize = debounce(function() {
      // Adjust Swiper configurations dynamically
      if (window.innerWidth < 768) {
        bestselling_swiper.params.slidesPerView = 1;
        bestselling_swiper.params.spaceBetween = 10;
      } else if (window.innerWidth < 991) {
        bestselling_swiper.params.slidesPerView = 3;
        bestselling_swiper.params.spaceBetween = 20;
      } else {
        bestselling_swiper.params.slidesPerView = 4;
        bestselling_swiper.params.spaceBetween = 30;
      }
      bestselling_swiper.update();
    }, 250);

    window.addEventListener('resize', handleResize);
  };

  // Enhanced Add to Cart Button Interactions
  var enhanceCartButtons = function() {
    var cartButtons = document.querySelectorAll('.cart-btn');
    
    cartButtons.forEach(function(button) {
      // Add ripple effect
      button.addEventListener('click', function(e) {
        var ripple = document.createElement('span');
        ripple.classList.add('ripple');
        
        var x = e.clientX - e.target.offsetLeft;
        var y = e.clientY - e.target.offsetTop;
        
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        
        this.appendChild(ripple);
        
        setTimeout(() => {
          ripple.remove();
        }, 300);
      });

      // Hover and focus states
      button.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
      });

      button.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
      });
    });
  };

  // Enhanced Button Container Interactions
  var setupButtonContainers = function() {
    var productItems = document.querySelectorAll('.product-item');
    
    productItems.forEach(function(item) {
      // Wrap existing buttons in a container if not already done
      var cartBtn = item.querySelector('.cart-btn');
      var wishlistBtn = item.querySelector('.wishlist-btn');
      
      if (cartBtn && wishlistBtn) {
        // Create button container if not exists
        var buttonContainer = document.createElement('div');
        buttonContainer.classList.add('button-container');
        
        // Wrap buttons
        cartBtn.parentNode.insertBefore(buttonContainer, cartBtn);
        buttonContainer.appendChild(cartBtn);
        buttonContainer.appendChild(wishlistBtn);
      }
      
      // Add subtle hover effects
      var buttons = item.querySelectorAll('.cart-btn, .wishlist-btn');
      buttons.forEach(function(button) {
        button.addEventListener('mouseenter', function() {
          this.style.transform = 'translateY(-3px)';
          this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
        
        button.addEventListener('mouseleave', function() {
          this.style.transform = 'translateY(0)';
          this.style.boxShadow = 'none';
        });
      });
    });
  };

  // document ready
  $(document).ready(function() {
    
    initPreloader();
    initSwiper();
    initProductQty();
    initJarallax();
    initChocolat();
    optimizeTextRendering();
    initResponsiveHandlers();
    enhanceCartButtons();
    setupButtonContainers();

        // product single page
        var thumb_slider = new Swiper(".product-thumbnail-slider", {
          spaceBetween: 8,
          slidesPerView: 3,
          freeMode: true,
          watchSlidesProgress: true,
        });
    
        var large_slider = new Swiper(".product-large-slider", {
          spaceBetween: 10,
          slidesPerView: 1,
          effect: 'fade',
          thumbs: {
            swiper: thumb_slider,
          },
        });

    window.addEventListener("load", (event) => {
      //isotope
      $('.isotope-container').isotope({
        // options
        itemSelector: '.item',
        layoutMode: 'masonry'
      });


      var $grid = $('.entry-container').isotope({
        itemSelector: '.entry-item',
        layoutMode: 'masonry'
      });


      // Initialize Isotope
      var $container = $('.isotope-container').isotope({
        // options
        itemSelector: '.item',
        layoutMode: 'masonry'
      });

      $(document).ready(function () {
        //active button
        $('.filter-button').click(function () {
          $('.filter-button').removeClass('active');
          $(this).addClass('active');
        });
      });

      // Filter items on button click
      $('.filter-button').click(function () {
        var filterValue = $(this).attr('data-filter');
        if (filterValue === '*') {
          // Show all items
          $container.isotope({ filter: '*' });
        } else {
          // Show filtered items
          $container.isotope({ filter: filterValue });
        }
      });

    });

  }); // End of a document

})(jQuery);

// RSKWORLD Custom JavaScript
(function($) {
  'use strict';

  // Dynamic Year in Copyright
  function updateCopyrightYear() {
    const currentYear = new Date().getFullYear();
    $('.copyright p').html(`&copy; ${currentYear} <a href="https://rskworld.in" target="_blank" 
      class="text-decoration-underline fw-bold text-black-50">PetBazaar</a> - All Rights Reserved`);
  }

  $(document).ready(function() {
    updateCopyrightYear();
  });

  // Add RSKWORLD specific tracking and customizations
  $(document).ready(function() {
    // Track contact information interactions
    $('.contact-info, .support-box').on('click', function() {
      console.log('PetBazaar Contact Info Viewed');
      // Log specific contact details
      console.log('Phone: +919330539277');
      console.log('Email: help@rskworld.in');
    });

    // Custom branding function
    function updateBranding() {
      $('title').text('PetBazaar');
      $('meta[name="description"]').attr('content', 'PetBazaar- Comprehensive online solutions');
    }

    updateBranding();
    initSwiper();
    initProductQty();
    initJarallax();
     initChocolat();

  });

  
})(jQuery);

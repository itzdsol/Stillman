AOS.init({
    // offset: 90,
    disable: 'mobile',
    duration: 500,
  });

$(window).scroll(function () {
  var sticky = $('header'),
      scroll = $(window).scrollTop();
  if (scroll >= 50) sticky.addClass('stickyHeader');
  else sticky.removeClass('stickyHeader');
});


var swiper = new Swiper(".mySwiper", {
  spaceBetween: 30,
  effect: "fade",
  loop: true,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
          renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "</span>";
          },
  },
});

var swiper = new Swiper(".mySwiper1", {
  slidesPerView: 3,
  spaceBetween: 10,
  // effect: "fade",
  loop: true,
    autoplay: {
      delay: 1000,
      disableOnInteraction: false,
    },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
   breakpoints: {
        // when window width is <= 499px
        320: {
            slidesPerView: 1,
            spaceBetweenSlides: 10
        },
        // when window width is <= 999px
        768: {
            slidesPerView: 2,
            spaceBetweenSlides: 10
        },
        1201: {
            slidesPerView: 3,
            spaceBetweenSlides: 10
        },
    },
});


$(".seller").click(function(){
  $(".seller").addClass("active");
  $(".collection").removeClass("active");
  $(".cellar").removeClass("active");
  $(".blog").removeClass("active");
});

$(".collection").click(function(){
  $(".collection").addClass("active");
  $(".seller").removeClass("active");
  $(".cellar").removeClass("active");
  $(".blog").removeClass("active");
});

$(".cellar").click(function(){
  $(".cellar").addClass("active");
  $(".collection").removeClass("active");
  $(".seller").removeClass("active");
  $(".blog").removeClass("active");
});

$(".blog").click(function(){
  $(".blog").addClass("active");
  $(".seller").removeClass("active");
  $(".collection").removeClass("active");
  $(".cellar").removeClass("active");
});

jQuery(function($) {
    $('.carousel-indicators > li').click(function() {
        $(this).siblings('li').removeClass('active');
        $(this).addClass('active');
    });
});




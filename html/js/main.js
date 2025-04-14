
AOS.init();

AOS.init({ disable: 'mobile' });
AOS.init({
    disable: function () {
        var maxWidth = 800;
        return window.innerWidth < maxWidth;
    }
});

$(document).ready( function () {
    //If your <ul> has the id "glasscase"
    $('.glasscase-slider').glassCase({ 
           'thumbsPosition': 'bottom', 
        'widthDisplayPerc' : 100,
        isDownloadEnabled: false,
    });
});

$(document).ready(function () {
    $(window).scroll(function () {
        if ($(window).scrollTop() >= 10) {
            $('.navbar').addClass('header-fixed');
        }
        else {
            $('.navbar').removeClass('header-fixed');
        }
    });
});

$('.banner-slider').owlCarousel({
    loop: true,
    margin: 0,
    nav: false,
    dots: true,
    dotsData: true,
    autoplay: true,
    //navText: ['<i class="fa-solid fa-angle-left"></i>', '<i class="fa-solid fa-angle-right"></i>'],
    autoplayTimeout: 7000,
    autoplaySpeed: 4000,
    //autoplay: true,
    //smartSpeed: 3000,
    // animateOut: 'pulseOut',
    // animateIn: 'pulseIn',
    autoplayHoverPause: false,
    responsiveClass: true,
    responsive: {
        0: {
            items: 1,
        },
        767: {
            items: 1,
        },
        992: {
            items: 1,
        },
        1200: {
            items: 1,
        }
    }
});

// ############# Custom Select Checkbox ###########

$(function () {
    $(".top-title-prt .filter-lst li").click(function () {
        $(this).toggleClass("active");
    });

});

/* search.js
 ========================================================*/
var searchToggle = $('.open-search'),
    inputAnime = $(".form-search"),
    body = $('body');

searchToggle.on('click', function (event) {
    event.preventDefault();

    if (!inputAnime.hasClass('active')) {
        inputAnime.addClass('active');
    } else {
        inputAnime.removeClass('active');
    }
});

body.on('click', function () {
    inputAnime.removeClass('active');
});

var elemBinds = $('.open-search, .form-search');
elemBinds.bind('click', function (e) {
    e.stopPropagation();
});

/* End search.js
========================================================*/

/*-------------------------
  showlogin toggle function
--------------------------*/
$('#showlogin').on('click', function () {
    $('#checkout-login').slideToggle(900);
});

/*-------------------------
showcoupon toggle function
--------------------------*/
$('#showcoupon').on('click', function () {
    $('#checkout_coupon').slideToggle(900);
});
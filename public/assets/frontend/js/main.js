
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
    // $('.glasscase-slider').glassCase({ 
    //        'thumbsPosition': 'bottom', 
    //     'widthDisplayPerc' : 100,
    //     isDownloadEnabled: false,
    // });
});
// $('#product-big-slider').slick({
//     slidesToShow: 1,
//     slidesToScroll: 1,
//     arrows: false,
//     fade: true,
//     asNavFor: '#product-thumbnail-slider'
// });

// $('#product-thumbnail-slider').slick({
//     slidesToShow: 4,
//     slidesToScroll: 1,
//     vertical:false,
//     asNavFor: '#product-big-slider',
//     dots: false,
//     focusOnSelect: true,
//     //verticalSwiping:true,
//     prevArrow: '<button class="slick-prev slick-arrow"><i class="fa-solid fa-angle-up"></i></button>',
//     nextArrow: '<button class="slick-next slick-arrow"><i class="fa-solid fa-angle-down"></i></button>',
//     responsive: [
//     {
//         breakpoint: 992,
//         settings: {
//             vertical: false,
//             slidesToShow: 4,
//         }
//     },
//     {
//         breakpoint: 768,
//         settings: {
//         vertical: false,
//         slidesToShow: 3,
//         }
//     },
//     {
//         breakpoint: 580,
//         settings: {
//         vertical: false,
//         slidesToShow: 3,
//         }
//     },
//     {
//         breakpoint: 380,
//         settings: {
//         vertical: false,
//         slidesToShow: 2,
//         }
//     }
//     ]
// });

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

$('.deal-slider').owlCarousel({
    loop: true,
    margin: 15,
    nav: true,
    navText: ['<i class="fa-solid fa-angle-left"></i>', '<i class="fa-solid fa-angle-right"></i>'],
    dots: false,
    autoplay: false,
    smartSpeed: 3000,
    autoplayHoverPause: true,
    responsiveClass: true,
    responsive: {
        0: {
            items: 1,
        },
        767: {
            items: 3,
        },
        992: {
            items: 4,
        },
        1000: {
            items: 4,
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
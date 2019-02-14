
$(document).ready( () => {

    $(".banner__slideshow").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        waitForAnimate: false,
        autoplay: false
    });


    let links = document.querySelectorAll('.banner__item')

    links.forEach( (link, index) => {
        link.addEventListener('mouseover', function(event){
            event.preventDefault
            $('.banner__slideshow').slick('goTo', index)
        })
    })

    $('.partners__slideshow').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        arrow: false,
        waitForAnimate: false,
        autoplay: true,
        autoplaySpeed: 5000,
        centerMode: true,
        nextArrow: '<i class="zmdi zmdi-chevron-right slick-next--partners"></i>',
        prevArrow: '<i class="zmdi zmdi-chevron-left slick-prev--partners"></i>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 475,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerPadding: '0px'

                }
            }

        ]
    });

    $('.product__slide--main').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.product__slide--nav'
    });

    $('.product__slide--nav').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.product__slide--main',
        focusOnSelect: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                slidesToShow: 3,
                slideToScroll: 1,
                infinite: true
                }
            }
        ]
    });

    $('.slideshow-advise').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        arrows: true,
        infinite: true,
        speed: 500,
        fade: false,
        cssEase: 'linear',
        nextArrow: '<i class="zmdi zmdi-long-arrow-right slick-next"></i>',
        prevArrow: '<i class="zmdi zmdi-long-arrow-left slick-prev"></i>',
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true
                }
            }
        ]
    });

    multiLevelMenu();

    $('.product-card__name').dotdotdot()
})

/**
 * Muli Level Menu
 */
function multiLevelMenu() {
    $menus = $('.multi-level-menu li > ul');

    $menus.each(function () {
        var $menu = $(this),
            $toggler = $menu.parent().children('a').first(),
            $anchors = $toggler.children('a');

        // find active items
        var $active_items = $menu.find('.active');

        if ($active_items.length) {
            $menu.show();

            if (!$toggler.parent().hasClass('active')) {
                $toggler.parent().addClass('active');
            }
        }

        $toggler.addClass('toggler');

        $anchors.on('click', function (event) {
            event.preventDefault();
        });

        $toggler.on('click', function () {
            $menu.slideToggle();
        });
    });
}

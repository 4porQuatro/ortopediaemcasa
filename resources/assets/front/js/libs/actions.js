
$(document).ready( () => {

    $(".banner__slideshow").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        waitForAnimate: false,
        autoplay: true,
        autoplaySpeed: 5000,
    });

    $('.banner__link').each((i) => {
        let $btn = $(this);

        $btn.on('click', () => {
            console.log(i);
            $('.banner__slideshow').slick('goTo', i);
        });
    });

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

    $('#accordion').find('.accordion-toggle').click(function(){

        //Expand or collapse this panel
        $(this).next().slideToggle('slow');

        //Hide the other panels
        $(".accordion-content").not($(this).next()).slideUp('slow');

        
    });

    $('#accordion-faqs').find('.accordion-toggle').click(function(){

        //Expand or collapse this panel
        $(this).next().slideToggle('slow');

        //Hide the other panels
        $(".accordion-content").not($(this).next()).slideUp('slow');

        if($('.faqs__toggle')){
            $(this).toggleClass('closed');

            if($(this).hasClass('closed')){
                $(this).text('Ver resposta');
            }else{
                $(this).text('fechar');
            }
        }

        
    });
});

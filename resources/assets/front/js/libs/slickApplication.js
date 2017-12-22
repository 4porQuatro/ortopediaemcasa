
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
        slidesToShow: 3,
        slidesToScroll: 1,
        arrow: false,
        waitForAnimate: false,
        autoplay: true,
        autoplaySpeed: 5000,
        centerMode: true,
        centerPadding: '250px'
    });
});
$(function() {
    $(".header__menu ul li").click(function(e) {
        e.stopPropagation();
        $(this).toggleClass("active");
    });
    $('.news-slider').owlCarousel({
        loop: true,
        margin: 10,
        navigation : true,
        slideSpeed : 300,
        paginationSpeed : 400,
        items : 1,
        autoplay: true,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false,
        dots: true
    });
});


$(window).scroll(function () {
    if ($(this).scrollTop() > 33 && $(this).scrollTop() < 66) /*height in pixels when the navbar becomes non opaque*/ {
        $('.opaque-navbar').addClass('semi-opaque');
        $('.opaque-navbar').removeClass('opaque');
    } else
    if ($(this).scrollTop() > 66) {
        $('.opaque-navbar').removeClass('semi-opaque');
        $('.opaque-navbar').addClass('opaque');
    } else
        $('.opaque-navbar').removeClass('semi-opaque');

});

setTimeout(function() {
    document.getElementById("error").style.display="none";
}, 5000);

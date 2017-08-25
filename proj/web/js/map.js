function initMap() {var pos = {lat: 36.7714698, lng: 10.2582734};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: pos,
        scrollwheel: false
    });
    var marker = new google.maps.Marker({
        position: pos,
        map: map
    });
}
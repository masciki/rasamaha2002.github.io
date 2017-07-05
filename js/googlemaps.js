// JavaScript Document


     function initialize() {
        var latlng = new google.maps.LatLng(-34.9034866251368, -56.13487958908081);
        var settings = {
            zoom: 15,
            center: latlng,
            mapTypeControl: false,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
            navigationControl: true,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
            mapTypeId: google.maps.MapTypeId.ROADMAP};
        var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
        
        var companyImage = new google.maps.MarkerImage('images/pin.png',
            new google.maps.Size(100,50),
            new google.maps.Point(0,0),
            new google.maps.Point(50,50)
        );

        var companyShadow = new google.maps.MarkerImage('images/pin.png',
            new google.maps.Size(130,50),
            new google.maps.Point(0,0),
            new google.maps.Point(65, 50));

        var companyPos = new google.maps.LatLng(-34.902650, -56.134644);

        var companyMarker = new google.maps.Marker({
            position: companyPos,
            map: map,
            icon: companyImage,
            shadow: companyShadow,
            title:"EmpireMoney",
            zIndex: 3});
        
        google.maps.event.addListener(companyMarker, 'click', function() {
            infowindow.open(map,companyMarker);
        });
    }
    
    $(document).ready(initialize);
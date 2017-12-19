/**
 *	Custom Google map
 */


function customGoogleMap(map_id, locations) {
    if(locations.length){
        var styleArray =[
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#444444"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#ffffff"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#dde6e8"
            },
            {
                "visibility": "on"
            }
        ]
    }
];


        var map_options = {
            disableDefaultUI: false,
            scrollwheel: false
        };

        /*
         * If there's only only one location set, we must set the zoom and
         * center the map according to the first location coordinates
         */
        if(locations.length == 1){
            map_options.zoom = 14;
            map_options.center = new google.maps.LatLng(locations[0][1], locations[0][2]);
        }

        var map = new google.maps.Map(document.getElementById(map_id), map_options);

        if(locations.length > 1)
        {
            var bounds = new google.maps.LatLngBounds();
        }

        for(i = 0; i < locations.length; i++)
        {
            var marker = new google.maps.Marker(
                {
                    title:      locations[i][0],
                    position:   new google.maps.LatLng(locations[i][1], locations[i][2]),
                    icon:       '',
                    map:        map
                }
            );

            if(locations.length > 1){
                bounds.extend(marker.position);
            }
        }

        if(locations.length > 1){
            map.fitBounds(bounds);
        }

        map.setOptions({
            styles: styleArray
        });
    }else{
        console.log('No locations found!');
    }
}

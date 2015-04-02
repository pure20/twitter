var map;
var markers = [];
var infoWindowStore = [];

$(document).ready(function() {
	var h = $(window).height();
    $('#map-canvas').height(h);

    $(window).resize(function() {
        h = $(this).height();
        $('#map-canvas').height(h);
    })
    
	$('#search').on('click', function(e) {
		    $('#address-form').submit();
		}
		else {
		}
		e.preventDefault();
	});

	$('#address-form').on('submit', function(e) {
        var address = $('#address').val();
		$('#search-key').text('TWEET ABOUT ' + address);
		getTweet(address);
		hist.pushState('main', address, '?q=' + address);
		e.preventDefault();
	})	
})

function initialize() {
	geocoder = new google.maps.Geocoder();
    var mapOptions = {
        center: { lat: -34.397, lng: 150.644},
        zoom: 11
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    var address = $('#address').val();
    if (address != '') {
    	getTweet(address);
    }
}

google.maps.event.addDomListener(window, 'load', initialize);

function getTweet(address) {
    geocoder.geocode( { 'address': address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
    	    map.setCenter(results[0].geometry.location);
    	    deleteMarkers();
    		$.post('/twitter/main/getTweet', { 'address': address, 'lat' : results[0].geometry.location.lat(), 'lng' : results[0].geometry.location.lng() }, function(data) {
    	        $.each(data.statuses, function(i, item) {
	                if (item.coordinates != null) {
	                	marker = addMarkers(item.coordinates, item.user.name, item.user.profile_image_url_https);

		                var infoWindow = new google.maps.InfoWindow(
		    	            {
			    	            content: 'Tweet: ' + item.text + '<br />When: ' + item.created_at
			    	        }
		    	        );

		                infoWindowStore.push(infoWindow);
		                google.maps.event.addListener(marker, 'click', function() {
		                    for (var j in infoWindowStore) {
			                    infoWindowStore[j].close();
			                }
		                    infoWindow.open(map, this);
		                });
	                }  
	            });
	        }, 'json')
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
        
        $('#address').val('');
    });
}

//Add a marker to the map and push to the array.
function addMarkers(location, name, icon)
{
	marker = new google.maps.Marker({
        position: new google.maps.LatLng(location.coordinates[1], location.coordinates[0]),
        map: map, 
        title: name,
        icon: icon,
  	});
	
	markers.push(marker);
	return marker;
}

//Sets the map on all markers in the array.
function setAllMap(map) {
    for (var i=0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setAllMap(null);
}

//Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}
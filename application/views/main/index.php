<div id="map-canvas"></div>
<div id="control-holder">
    <div id="input-holder">
        <div id="input-button-indent">
            <input type="text" id="address" name="address" class="input-box" placeholder="Country name" />
        </div>
    </div>
    <div id="button-holder">
        <input type="button" class="button float-left" id="search" value="Search" />
        <input type="button" class="button float-left" value="History" onclick="window.location.href='history'" />
    </div>
</div>
<script>
$(document).ready(function() {
	  var h = $(window).height();
    $('#map-canvas').height(h);

    $(window).resize(function() {
        h = $(this).height();
        $('#map-canvas').height(h);
    })
})

function initialize() {
	  geocoder = new google.maps.Geocoder();
    var mapOptions = {
        center: { lat: -34.397, lng: 150.644},
        zoom: 8
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

$('#search').on('click', codeAddress);
function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
    	  if (status == google.maps.GeocoderStatus.OK) {
        	  map.setCenter(results[0].geometry.location);
        	  var marker = new google.maps.Marker({
            	  map: map,
            	  position: results[0].geometry.location
            });
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}
</script>

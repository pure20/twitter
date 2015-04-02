<?php if ($address): ?>
    <div id="search-key" class="search-performed">TWEET ABOUT <?php echo htmlentities($address) ?></div>
<?php else: ?>
    <div id="search-key" class="search-performed"></div>
<?php endif ?>
<div id="map-canvas"></div>
<div id="control-holder">
    <form id="address-form">
        <div id="input-holder">
            <div id="input-button-indent">
                <input type="text" id="address" name="address" class="input-box input-pad" autocomplete="off" placeholder="Country name" />
            </div>
        </div>
        <div id="button-holder">
            <input type="submit" class="button float-left input-pad" id="search" value="Search" />
            <input type="button" class="button float-left input-pad" value="History" onclick="window.location.href='history'" />
        </div>
    </form>
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
        zoom: 11
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    <?php if ($address): ?>
	      getTweet('<?php echo $address ?>');
    <?php endif ?>
}

google.maps.event.addDomListener(window, 'load', initialize);

$('#search').on('click', function() {
	  $('#address-form').submit();
});

$('#address-form').on('submit', function(e) {
	  var address = $('#address').val();
	  $('#search-key').text('TWEET ABOUT ' + address);
	  hist.pushState('main', address, '?q=' + address);
	  getTweet(address);
	  e.preventDefault();
})
var markers = [];
var infoWindowStore = [];

function getTweet(address) {
    geocoder.geocode( { 'address': address}, function(results, status) {
    	  if (status == google.maps.GeocoderStatus.OK) {
    		    map.setCenter(results[0].geometry.location);
    		    $.post('/twitter/main/getTweet', { 'address': address, 'lat' : results[0].geometry.location.lat(), 'lng' : results[0].geometry.location.lng() }, function(data) {
    	          $.each(data.statuses, function(i, item) {
	                  if (item.coordinates != null) {
		                    markers[i] = new google.maps.Marker({
		    		                position: new google.maps.LatLng(item.coordinates.coordinates[1], item.coordinates.coordinates[0]),
		    		                map: map, 
		    		                title: item.user.name,
		    		                icon: item.user.profile_image_url_https,
		    		  	        });

		                    var infoWindow = new google.maps.InfoWindow(
		    	                  {
			    	                    content: 'Tweet: ' + item.text + '<br />When: ' + item.created_at
			    	                }
		    	              );

		                    infoWindowStore.push(infoWindow);
		                    google.maps.event.addListener(markers[i], 'click', function() {
		                    	  for (j in infoWindowStore) {
			                		      infoWindowStore[j].close();
			                	    }
		                        infoWindow.open(map, markers[i]);
		                    });
	                  }  
	              });
	          }, 'json')
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}
</script>

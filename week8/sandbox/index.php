<?php
include 'Mobile_Detect.php';
$detect = new Mobile_Detect();

if (!$detect->isMobile()) {
  header("Location: mobiletest/iphone.php?url=stanford.edu/~jmtai/cgi-bin/cs147/week8/sandbox/mobile.php");
}

?>

<!DOCTYPE html> 
<html>

<head>
	<title>MappityMap</title> 
	<meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
 	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="css/style.css" />
	<link rel="apple-touch-icon" href="appicon.png" />
	<link rel="apple-touch-startup-image" href="startup.png">

	<script src="js/jquery-1.8.2.min.js"></script>
	<script src="js/jquery.mobile-1.2.0.js"></script>
	
	<link rel="stylesheet" href="css/jquery.mobile-1.2.0.css" />
	<link href="css/ios_inspired/styles.css" rel="stylesheet" />
	
</head>

<body>
<div data-role="page" id="filter">

	<div data-role="header">
		<h1>Map!</h1>
	</div><!-- /header -->
	
	<div data-role="content" style="padding:0">	
	
		<!-- This is the magic script, usually I'd put it near the header -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		
		
		<div style="padding-left:10px;padding-right:10px;">
			<p>Enter a search location below.</p>
			<form id="blankenter" action="index.php">
				<input type="text" name="startingpoint" id="startingpoint" />
			</form>
		</div>
		
			
		<!-- This is where the map gets inserted -->
		<div id="mapcanvas2" style="height:315px;width:320px"></div>
		

		<script type="text/javascript">
		var map;
		var wayA;
		var wayB;
		var debug;
		
		$(function() {
			
			// If you search for a location, capture that location
			// and geocode it
			
			$("#blankenter").submit(function(event) {
				
				geocoder = new google.maps.Geocoder();
				geocoder.geocode({'address': $("#startingpoint").val() }, function(results, status) {
		          if (status == google.maps.GeocoderStatus.OK) {
		          	
		          	// Add debug code here
		          	console.log("This is the address the user put in");
		          	debug = results;
		          	console.log(debug);
		          	
		          	
		            
		            map.setCenter(results[0].geometry.location);
		            var marker = new google.maps.Marker({
		                map: map,
		                position: results[0].geometry.location
		            });
		          } else {
		            alert('Geocode was not successful for the following reason: ' + status);
		          }
				});
				return false;
			});
		});
		
		// This is the display window
		var infowindow = new google.maps.InfoWindow({
		    size: new google.maps.Size(150, 50)
		});
		
		
		// Create the marker
		function createMarker(latlng, name, html) {
		    var contentString = html;
		    var marker = new google.maps.Marker({
		        position: latlng,
		        map: map
		    });
		
		    google.maps.event.addListener(marker, 'click', function () {
		        infowindow.setContent(contentString);
		        infowindow.open(map, marker);
		    });
		    google.maps.event.trigger(marker, 'click');
		    return marker;
		}
		
		function success(position) {
			console.log("The user's position is at");
			debug = position;
		    console.log(debug);
		    
		    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
		    var myOptions = {
		        zoom: 15,
		        center: latlng,
		        mapTypeControl: false,
		        navigationControlOptions: {
		            style: google.maps.NavigationControlStyle.SMALL
		        },
		        mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
		    
		    map = new google.maps.Map(document.getElementById("mapcanvas2"), myOptions);
		
		    var marker = new google.maps.Marker({
		        position: latlng,
		        map: map,
		        title: "You are here!",
		        icon: 'beachflag.png'
		    });
		    
		    var renderer;
		    google.maps.event.addListener(map, 'click', function (event) {
		        if (!wayA) {
		        	// If a direction has been set, 
		        	// remove it.
		        	if (renderer) {
		        		renderer.setMap(null);
		        	}
		            wayA = new google.maps.Marker({
		
		                position: event.latLng,
		                map: map
		
		            });
		        } else {
		            wayB = new google.maps.Marker({
		
		                position: event.latLng,
		                map: map
		
		            });
					 
		            // Directions
		            renderer = new google.maps.DirectionsRenderer({
		                'draggable': true
		            });
		            renderer.setMap(map);
		
					// Uncomment the following to add a directions pane
		            //ren.setPanel(document.getElementById("directionsPanel"));
		            service = new google.maps.DirectionsService();
		
		            service.route({
		                'origin': wayA.getPosition(),
		                'destination': wayB.getPosition(),
		                'travelMode': google.maps.DirectionsTravelMode.DRIVING
		            }, function (result, status) {
		            	
		            	console.log("The route between the two points is");
		            	debug = result;
		    			console.log(debug);
		    			
		                if (status == 'OK') renderer.setDirections(result);
		                	wayA.setMap(null);
				            wayA = null;
				            wayB.setMap(null);
				            wayB = null;
		            })
		        }
		    });
		}
		
		function error(msg) {
		    var s = document.querySelector('#status');
		    s.innerHTML = typeof msg == 'string' ? msg : "failed";
		    s.className = 'fail';
		}
		
		if (navigator.geolocation) {
		    navigator.geolocation.getCurrentPosition(success, error);
		} else {
		    error('not supported');
		}	
		
	
		</script> 

		
		
		
	</div>
	<div data-role="footer" data-id="samebar" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" class="nav-glyphish-example" data-grid="b">
		<ul>
			<li><a href="overlay.html" id="home" data-icon="custom">Overlay</a></li>
			<li><a href="hello.html" id="key" data-icon="custom">Hello</a></li>
			<li><a href="index.php" id="map" class="ui-btn-active" data-icon="custom">Map</a></li>
			
		</ul>
		</div>
	</div>
</div>
</body>
</html>
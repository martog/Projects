<?php
	$con = mysql_connect("localhost", "root", "admin") or die(mysql_error());
	mysql_select_db("weather",$con);
	
	//$map_api_key = "AIzaSyCFr-f3l8dSUr3m37JWI42eEEDdxQirosc";	

	$coord_lon = $_SESSION['coord_lon'];
	$coord_lat = $_SESSION['coord_lon'];
	$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data");
	//echo $place_coord;

	$points_list = array();
	while($row = mysql_fetch_array($place_coord)){	
		//echo" lat: ".$row['coord_lat']." | lon: ".$row['coord_lon']." |";
		$points_list[] = array($row['coord_lat'],$row['coord_lon']);
	}
	echo json_encode($points_list);
	//echo $coord_lon, $coord_lat;
?>

<html>
	<head>
		<title> Map </title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<style>
			html, body {
				height: 100%;
				margin: 0;
				padding: 0;
			}
			#map {
				height: 100%;
			}
		</style>

	</head>

	<body>
		<div id="map"></div>

		<script>
		function initMap() {
			var myLatLng = {lat: 42.7, lng: 23.32};
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 7,
				center: myLatLng
			});

			var points_list = [<?php echo json_encode($points_list);?>];
			var i = 0, points_count = points_list[0].length;
			//window.alert(points_count);
			
	
			while(i <= points_count){
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(points_list[0][i][0], points_list[0][i][1]),
					map: map,
					title: 'Hello World!'
				});
				i++;
				//window.alert(points_list[0][1][1]);
			}
      		}
    		</script>

    		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFr-f3l8dSUr3m37JWI42eEEDdxQirosc&callback=initMap">
    		</script>


	</body>
</html>

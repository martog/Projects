<?php
	$con = mysql_connect("localhost", "root", "admin") or die(mysql_error());
	mysql_select_db("weather",$con);
	$cities_list = file_get_contents("cities.json");
	$cities_list = json_decode($cities_list,true);
	
	//FILTER example: $place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE country = 'TW'");


	$city = $_POST['city'];
	$country = $_POST['country'];
	$min_temp = $_POST['min_temp'];	
	$max_temp = $_POST['max_temp'];

	if(!empty($min_temp) && !empty($max_temp)){
		echo "weather:".$min_temp, $max_temp;	
	}else{
		echo "weather:empty";
	}

	if(!empty($city)){
		echo "city:".$city;	
	}else{
		echo "city:empty";
	}

	$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data");

	//echo $place_coord;

	$points_list = array();
	while($row = mysql_fetch_array($place_coord)){	
		//echo" lat: ".$row['coord_lat']." | lon: ".$row['coord_lon']." |";
		$points_list[] = array($row['coord_lat'],$row['coord_lon']);
	}

	//echo json_encode($points_list);
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
		<div id = "filter">
			<button onclick = "country_show()">Country</button>
			<button onclick = "city_show()">City</button>
			<button onclick = "weather_show()">Weather</button>
		
			<div id = "weather" style ="display:none;">
				<form action = "map.php" method = "POST">
					<input type = "number" placeholder = "Min temp" name = "min_temp">
					<input type = "number" placeholder = "Max temp" name = "max_temp">
					<input type = "submit" value = "Filter">
				</form>
			</div>

			<div id = "city" style ="display:none;">
				<form action = "map.php" method = "POST">
					<input type = "text" placeholder = "City name" name = "city">
					<input type = "submit" value = "Filter">
				</form>
			</div>

			<div id = "country" style ="display:none;">
				<form action = "map.php" method = "POST">
					<input type = "text" placeholder = "Country name" name = "country">
					<input type = "submit" value = "Filter">
				</form>
			</div>
		</div>

		<script>
			function country_show(){
				document.getElementById('country').style.display = "block";
				document.getElementById('city').style.display = "none";
				document.getElementById('weather').style.display = "none";
			}

			function city_show(){
				document.getElementById('city').style.display = "block";
				document.getElementById('weather').style.display = "none";
				document.getElementById('country').style.display = "none";
			}

			function weather_show(){
				document.getElementById('weather').style.display = "block";
				document.getElementById('city').style.display = "none";
				document.getElementById('country').style.display = "none";
			}
		</script>
		
		<div id="map"></div>

		<script>
		function initMap() {
			var myLatLng = {lat: 0.0, lng: 0.0};
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 3,
				center: myLatLng
			});

			var points_list = [<?php echo json_encode($points_list);?>];
			var i = 0, points_count = points_list[0].length;
			window.alert(points_count);
			
	
			while(i <= points_count){
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(points_list[0][i][0], points_list[0][i][1]),
					map: map,
					title: 'Place'
				});
				i++;
				//window.alert(points_list[1][i][0]);
			}
      		}
    		</script>

    		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFr-f3l8dSUr3m37JWI42eEEDdxQirosc&callback=initMap">
		//map_api_key = "AIzaSyCFr-f3l8dSUr3m37JWI42eEEDdxQirosc";
    		</script>


	</body>
</html>

<?php
	$con = mysql_connect("localhost", "root", "admin") or die(mysql_error());
	mysql_select_db("weather",$con);
	//$cities_list = file_get_contents("cities.json");
	//$cities_list = json_decode($cities_list,true);
	
	//FILTER example: $place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE country = 'TW'");


	//$city = $_POST['city'];
	//$country = $_POST['country'];
	
	
	$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data");

	/*if(!empty($min_temp) && !empty($max_temp)){
		$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE main_temp BETWEEN '$min_temp' AND '$max_temp'");	
	}else if(!empty($city)){
		$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE city = '$city'");	
	}else if(!empty($country)){
		$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE country = '$country'");	
	}else{
		$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data");
	}*/

	
	//echo json_encode($points_list);

?>

<html>
	<head>
		<title> Map </title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="mstyle.css ">
		<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
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
<!-- COUNTRY SELECT -->	
				<select name = "country" id = "country-list">
					<option value = "Country">Country</option>
		   			<?php 
							$countries_query = mysql_query("SELECT DISTINCT country FROM data");
							while($row = mysql_fetch_array($countries_query)){	
								echo"<option value='".$row['country']."'>".$row['country']."</option>";
							}
						?>
				</select>
				<script>
					 $(function(){
						// bind change event to select
						$('#country-list').on('change', function () {
							 var url = "?country=" +$(this).val(); // get selected value
							 if (url) { // require a URL
								  window.location = url; // redirect
							 }
							 return false;
						});
					 });
				</script>
<!-- COUNTRY SELECT END-->

<!-- CITY SELECT -->
			<div id = "city_select">
				<select name = "city" id = "city-list" onchange = "self.location=self.location+'&city='+this.options[this.selectedIndex].value">
					<option value = "City">City</option>
					<?php
						if(!empty($_GET['country'])) {
							$country = $_GET['country'];
							$cities_query = mysql_query("SELECT DISTINCT city FROM data WHERE country = '$country'");
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE country = '$country'");
						}else{
							$cities_query = mysql_query("SELECT DISTINCT city FROM data");
							if(!empty($_GET['city'])){
								echo "<script>self.location=self.location+'?city=".$_GET['city']."</script>";
								$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE city = '$city'");				
							}
								
						}
						
						while($ct_row = mysql_fetch_array($cities_query)){	
								echo"<option value='".$ct_row['city']."'>".$ct_row['city']."</option>";
						}

						if(!empty($_GET['city'])){
							$city = $_GET['city'];
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE city = '$city' AND country = '$country'");
						}

					?>
				</select>
			</div>
<!-- CITY SELECT END-->
<!-- WEATHER -->
			
		
			<div id = "weather" style ="display:block;">
				<form action = "map.php" method = "GET">
					<input type = "number" placeholder = "Min temp" name = "min_temp">
					<input type = "number" placeholder = "Max temp" name = "max_temp">
					<input type = "submit" value = "Filter" onclick ="self.location=self.location+'?min_temp='+min_temp+'&max_temp='+max_temp">
				</form>
			</div>
			<?php
				if(!empty($_GET['min_temp']) && !empty($_GET['min_temp'])){
					$min_temp = $_GET['min_temp'];
					$max_temp = $_GET['max_temp'];		
					$place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE main_temp BETWEEN '$min_temp' AND '$max_temp'");
				}	
			?>
<!-- WEATHER END -->


		<?php

			$points_list = array();
			while($row = mysql_fetch_array($place_coord)){	
				//echo" lat: ".$row['coord_lat']." | lon: ".$row['coord_lon']." |";
				$points_list[] = array($row['coord_lat'],$row['coord_lon']);
			}
		?>
		
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
				var contentString = '<h3>INFO</h3>';
				var infowindow = new google.maps.InfoWindow({
          		content: contentString
        		});
				marker.addListener('click', function() {
          			infowindow.open(map, marker);
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

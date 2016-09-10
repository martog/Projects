<?php
	$con = mysql_connect("localhost", "root", "admin") or die(mysql_error());
	mysql_select_db("weather",$con);
	//$cities_list = file_get_contents("cities.json");
	//$cities_list = json_decode($cities_list,true);
	
	//FILTER example: $place_coord = mysql_query("SELECT coord_lon, coord_lat FROM data WHERE country = 'TW'");


	//$city = $_POST['city'];
	//$country = $_POST['country'];
	
	
	$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data");
	//echo json_encode($points_list);

?>

<html>
	<head>
		<title> Map </title>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="mstyle.css ">
		<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>

<!-- SELECT2 -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<!-- SELECT2  END-->
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
				<select name = "country" id = "country-list" style = "min-width:180px;">
					<option></option>
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
						$('#country-list').select2({placeholder: "Select a country"}).on('change', function () {
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
				<select name = "city" id = "city-list">
					<option></option>
					<?php
						if(!empty($_GET['country'])) {
							$country = $_GET['country'];
							$cities_query = mysql_query("SELECT DISTINCT city FROM data WHERE country = '$country'");
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE country = '$country'");
						}else{
							$cities_query = mysql_query("SELECT DISTINCT city FROM data");
								
						}
						
						while($ct_row = mysql_fetch_array($cities_query)){	
								echo"<option value='".$ct_row['city']."'>".$ct_row['city']."</option>";
						}


					?>
				</select>
				<script>
					 $(function(){
						// bind change event to select
						$('#city-list').select2({placeholder: "Select a city"}).on('change', function () {
							var url = window.location.href;
							if(url.indexOf('?') > -1){
									url = url + "&city=" +$(this).val();
									//window.alert("true: " + url);
							}else{
									url = url + "?city=" +$(this).val();
									//window.alert("false: " + url);
							}
							if (url) { // require a URL
								 window.location = url; // redirect
							}
							return false;
						});
		
					});
				</script>
			<?php
					if(!empty($_GET['city'])){
						$city = $_GET['city'];
						if(!empty($_GET['country'])){
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE city = '$city' AND country = '$country'");
							//echo"country and city: ";
						}else{
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE city = '$city'");
							//echo"city: ".$city;
						}
				}
			?>
			</div>
<!-- CITY SELECT END-->
<!-- WEATHER -->
			
		
			<div id = "weather" style ="display:block;">
				<form action = "map.php" method = "GET">
					<input type = "number" placeholder = "Min temp" name = "min_temp">
					<input type = "number" placeholder = "Max temp" name = "max_temp">
					<input type = "hidden" value = "<?php if(!empty($_GET['country'])){echo $country;}?>" name = "country" >
					<input type = "hidden" value = "<?php if(!empty($_GET['city'])){echo $city;}?>" name = "city" >
					<input type = "submit" value = "Filter">
				</form>
			</div>
			<?php
				if(!empty($_GET['min_temp']) && !empty($_GET['min_temp'])){
					$min_temp = $_GET['min_temp'];
					$max_temp = $_GET['max_temp'];

					if(!empty($_GET['country'])){
						$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE main_temp BETWEEN '$min_temp' AND '$max_temp' AND country = '$country'");
						if(!empty($_GET['city'])){
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE main_temp BETWEEN '$min_temp' AND '$max_temp' AND country = '$country' AND city = '$city'");
						}
							
						//echo "country set";
					}else{	
						if(!empty($_GET['city'])){
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE main_temp BETWEEN '$min_temp' AND '$max_temp' AND city = '$city'");
							//echo "city set";
						}else{
							$place_coord = mysql_query("SELECT DISTINCT coord_lon, coord_lat FROM data WHERE main_temp BETWEEN '$min_temp' AND '$max_temp'");
						}
						//echo "country not set";
							
					}
	

			
				}	
			?>
<!-- WEATHER END -->

<!-- FINAL POINT LIST -->	
		<?php
			$points_list = array();
			while($row = mysql_fetch_array($place_coord)){	
				//echo" lat: ".$row['coord_lat']." | lon: ".$row['coord_lon']." |";
				$points_list[] = array($row['coord_lat'],$row['coord_lon']);
			}
		?>
<!-- FINAL POINT LIST END-->

<!-- SHOW POINTS ON MAP-->
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
			var contentString = '<h3>INFO:</h3>lat: ' + points_list[0][i][0] + ' lon: ' + points_list[0][i][1];
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
<!-- SHOW POINTS ON MAP END-->

    		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCFr-f3l8dSUr3m37JWI42eEEDdxQirosc&callback=initMap">
		//map_api_key = "AIzaSyCFr-f3l8dSUr3m37JWI42eEEDdxQirosc";
    		</script>


	</body>
</html>

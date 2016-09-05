<?php
	$con = mysql_connect("localhost", "root", "admin") or die(mysql_error());
	mysql_select_db("weather",$con);

	//$i = 2172797;
	//while($i<=2172800){
		//echo "CITY: ".$cities_list['China'][$i];

	
	$city_name_search = $_POST['city'];
	$api_key = "01156208e1bd1e99995d141ee0b8f7f0";
	$lon = 23.32;
	$lat = 42.7;
		//for search use this: $jsondata = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$city_name_search."&APPID=".$api_key);


		$jsondata = file_get_contents("http://api.openweathermap.org/data/2.5/find?lat=".$lat."&lon=".$lon."&cnt=50&units=metric&appid=".$api_key);
		$data = json_decode($jsondata, true);
	$i = 0;
	while($i<50){	
		$id = $data['list'][$i]['id'];
		$city = $data['list'][$i]['name'];
		$country = $data['list'][$i]['sys']['country'];
		$coord_lon = $data['list'][$i]['coord']['lon'];
		$coord_lat = $data['list'][$i]['coord']['lat'];
		$weather_id = $data['list'][$i]['weather'][0]['id'];
		$weather_main = $data['list'][$i]['weather'][0]['main'];
		$weather_description = $data['list'][$i]['weather'][0]['description'];
		$main_temp = $data['main']['temp'];
		$main_temp_min = $data['list'][$i]['main']['temp_min'];
		$main_temp_max = $data['list'][$i]['main']['temp_max'];
		$main_pressure = $data['list'][$i]['main']['pressure'];
		$wind_speed = $data['list'][$i]['wind']['speed'];
		$wind_deg = $data['wind']['deg'];
		$clouds_all = $data['list'][$i]['clouds']['all'];

	
	
		/*CREATE TABLE data(id INT(11), city VARCHAR(30), country VARCHAR(30), coord_lon FLOAT(22), coord_lat FLOAT(22), weather_id INT(11), weather_main VARCHAR(30), weather_description VARCHAR(50), main_temp FLOAT(22), main_temp_min FLOAT(22), main_temp_max FLOAT(22), main_pressure FLOAT(22), wind_speed FLOAT(22), wind_deg FLOAT(22), clouds_all INT(11));
		*/

		$query = "INSERT INTO data(id, city, country, coord_lon, coord_lat, weather_id, weather_main, weather_description, main_temp, main_temp_min, main_temp_max, main_pressure, wind_speed, wind_deg, clouds_all) VALUES ('$id', '$city', '$country', '$coord_lon', '$coord_lat', '$weather_id', '$weather_main', '$weather_description', '$main_temp', '$main_temp_min', '$main_temp_max', '$main_pressure', '$wind_speed', '$wind_deg', '$clouds_all')";

		if(!mysql_query($query,$con)){
			die('Error : ' . mysql_error());
		}
		$i++;
	}

?>

<html>
	<head>
		<title>Weather</title>
		<link rel="stylesheet" type="text/css" href="wstyle.css ">
	</head>

	<body>
		<div align = "center">
			<form action = "index.php" method = "POST">
				<input type = "search" name = "city" placeholder = "Search for city" id = "sbox" required/>
				<input type = "submit" value = "Search">
			</form>
		</div>
		<?php
			echo "<center><h3>| id: ".$id." | city: ".$city." | country: ".$country." | coord_lon: ".$coord_lon." | coord_lat: ".$coord_lat." | weather_id: ".$weather_id." | weather_main: ".$weather_main." |<br>| weather_description: ".$weather_description." | main_temp: ".$main_temp." | main_temp_min: ".$main_temp_min." |<br>| main_temp_max: ".$main_temp_max." | main_pressure: ".$main_pressure." | wind_speed: ".$wind_speed." | wind_deg: ".$wind_deg." | clouds_all: ".$clouds_all." |</h3></center>";
		?>
		
	</body>
</html>

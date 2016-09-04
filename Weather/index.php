<?php
	$con = mysql_connect("localhost", "root", "admin") or die(mysql_error());
	mysql_select_db("weather",$con);

	$cities_list = file_get_contents("cities.json");
	$cities_list = json_decode($cities_list,true);
	$i = 0;
	while($i<=50){
		echo "CITY: ".$cities_list['China'][$i];

	
		$city_name_search = $_POST['city'];
		$api_key = "01156208e1bd1e99995d141ee0b8f7f0";
		$jsondata = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$cities_list['China'][$i]."&APPID=".$api_key);
		$data = json_decode($jsondata, true);

		$id = $data['id'];
		$city = $data['name'];
		$country = $data['sys']['country'];
		$coord_lon = $data['coord']['lon'];
		$coord_lat = $data['coord']['lat'];
		$weather_id = $data['weather'][0]['id'];
		$weather_main = $data['weather'][0]['main'];
		$weather_description = $data['weather'][0]['description'];
		$main_temp = (($data['main']['temp'] - 32) * (5/9));
		$main_temp_min = (($data['main']['temp_min'] - 32) * (5/9));
		$main_temp_max = (($data['main']['temp_max'] - 32) * (5/9));
		$main_pressure = $data['main']['pressure'];
		$wind_speed = $data['wind']['speed'];
		$wind_deg = $data['wind']['deg'];
		$clouds_all = $data['clouds']['all'];

	
	
		/*CREATE TABLE data(id INT(11), city VARCHAR(30), country VARCHAR(30), coord_lon FLOAT(22), coord_lat FLOAT(22), weather_id INT(11), weather_main VARCHAR(30), weather_description VARCHAR(50), main_temp FLOAT(22), main_temp_min FLOAT(22), main_temp_max FLOAT(22), main_pressure FLOAT(22), wind_speed FLOAT(22), wind_deg FLOAT(22), clouds_all INT(11));
		*/

		$query = "INSERT INTO data(id, city, country, coord_lon, coord_lat, weather_id, weather_main, weather_description, main_temp, main_temp_min, main_temp_max, main_pressure, wind_speed, wind_deg, clouds_all) VALUES ('$id', '$city', '$country', '$coord_lon', '$coord_lat', '$weather_id', '$weather_main', '$weather_description', '$main_temp', '$main_temp_min', '$main_temp_max', '$main_pressure', '$wind_speed', '$wind_deg', '$clouds_all')";

		if(!mysql_query($query,$con)){
			die('Error : ' . mysql_error());
		}
		$i++;
	}

//da suzdam otdelna stranica s vizualizirane na bazata danni i vuzmojnost za filtraciq
//da se zapi6e v database  V
//da se vizualizirat koordinatite na google maps, prez apito na google, kato se filtrirat
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

<?php
	session_start();
	require('config.php');
	$dbs = mysql_query("SELECT * FROM horoscope;");
		$submit_flag = 0;
		if(isset($_POST["submit_b_date"])){
			$submit_flag = 1;
			if(!empty($_POST["b_date"])){
				$b_date = $_POST["b_date"];
				$date = explode("-",$b_date);
				$day = $date[2];
				$month = $date[1];
			}
			//echo "day: ".$day." | month: ".$month;
			//date between date min and date max
		$url_sign = 0;
		if(($day >= 21 && $month == 3) || ($day <= 20 && $month == 4)){
			$h_sign = "aries"; //oven
			$url_sign = 1;
		}else if(($day >= 21 && $month == 4) || ($day <= 21 && $month == 5)){
			$h_sign = "taurus"; //telec
			$url_sign = 2;
		}else if(($day >= 22 && $month == 5) || ($day <= 21 && $month == 6)){
			$h_sign = "gemini"; //bliznaci
			$url_sign = 3;
		}else if(($day >= 22 && $month == 6) || ($day <= 22 && $month == 7)){
			$h_sign = "cancer"; //rak
			$url_sign = 4;
		}else if(($day >= 23 && $month == 7) || ($day <= 23 && $month == 8)){
			$h_sign = "leo"; //luv
			$url_sign = 5;
		}else if(($day >= 24 && $month == 8) || ($day <= 23 && $month == 9)){
			$h_sign = "vigro"; //deva
			$url_sign = 6;
		}else if(($day >= 24 && $month == 9) || ($day <= 23 && $month == 10)){
			$h_sign = "libra"; //vezni
			$url_sign = 7;
		}else if(($day >= 24 && $month == 10) || ($day <= 22 && $month == 11)){
			$h_sign = "scorpio"; //scorpion
			$url_sign = 8;
		}else if(($day >= 23 && $month == 11) || ($day <= 21 && $month == 12)){
			$h_sign = "sagittarius"; //strelec
			$url_sign = 9;
		}else if(($day >= 22 && $month == 12) || ($day <= 20 && $month == 1)){
			$h_sign = "capricorn"; //kozirog
			$url_sign = 10;
		}else if(($day >= 21 && $month == 1) || ($day <= 19 && $month == 2)){
			$h_sign = "aquarius"; //vodolei
			$url_sign = 11;
		}else if(($day >= 20 && $month == 2) || ($day <= 20 && $month == 3)){
			$h_sign = "pisces"; //ribi
			$url_sign = 12;
		}
	}

?>

<html>
	<head>
		<title>Zodiac</title>
		<link rel="stylesheet" type="text/css" href="zstyle.css ">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/resources/demos/style.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		<script>
			$( function() {
				$( "#b_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
				$( "#s_date_min" ).datepicker({ dateFormat: 'yy-mm-dd' });
				$( "#s_date_max" ).datepicker({ dateFormat: 'yy-mm-dd' });
			} );
		</script>
	</head>

	<body>

		<center>
			<h1>ZODIAC</h1>
			<form action = "index.php" method = "POST">
				<select id = "show_content">
					<option>Select option</option>
					<option value = "1">Check horoscope</option>
					<option value = "2">Check signs in range</option>
				</select><br><br>
				<div id = "check_horoscope" style ="display:none">
					<h3>Check horoscope</h3>
					<input type = "date" id = "b_date" name = "b_date" placeholder = "Select date of birth">
					<input type = "submit" value = "Check" name = "submit_b_date">
				</div>
				<div id = "check_signs_in_range" style ="display:none">
					<h3>Check signs in range</h3>
					<input type = "date" id = "s_date_min" name = "s_date_min" placeholder = "Select begin date">
					<input type = "date" id = "s_date_max" name = "s_date_max" placeholder = "Select end date">
					<input type = "submit" value = "Check" name = "submit_s_date">
				</div>
			</form>
		</center>
			<script>
				$(function(){
					$('#show_content').on('change', function () {
						if($(this).val() == 1){
							//window.alert("check_horoscope");
							document.getElementById('check_horoscope').style.display = "block";
							document.getElementById('check_signs_in_range').style.display = "none";
						}else if($(this).val() == 2){
							document.getElementById('check_signs_in_range').style.display = "block";
							document.getElementById('check_horoscope').style.display = "none";
						}else{
							document.getElementById('check_horoscope').style.display = "none";
							document.getElementById('check_signs_in_range').style.display = "none";
						}
					});
				});
			</script>

		<?php

			if($submit_flag == 1){
				echo"<div class = 'box'><center><h1>$h_sign</h1></center>";
				//echo"u entered date:".$b_date;
				$dbs = mysql_query("SELECT * FROM `horoscope` WHERE `sign` = '$h_sign';");
				
				while($row = mysql_fetch_array($dbs)){
				$horoscope = $row['horoscope'];
				$day = $row['day'];
				if($day == 0){
					echo "<h3>Yesterday</h3><h5>$horoscope</h5>";
				}else if($day == 1){
					echo "<h3>Today</h3><h5>$horoscope</h5>";
				}else if($day == 2){
					echo "<h3>Tomorrow</h3><h5>$horoscope</h5>";		
				}
		
				}
			echo"</div>";
			$submit_flag = 0;
		}else{
			if(isset($_POST["submit_s_date"])){
				if(!empty($_POST['s_date_min']) && !empty($_POST['s_date_max'])){
					$s_date_min = $_POST['s_date_min'];
					$s_date_max = $_POST['s_date_max'];
				}
				$dbs = mysql_query("SELECT DISTINCT sign FROM `horoscope` WHERE `date_min` BETWEEN '$s_date_min' AND '$s_date_max' AND `date_max` BETWEEN '$s_date_min' AND '$s_date_max';");

				echo"<div class = 'box'><center><h1>All signs in this range are:</h1></center>";
				while($row = mysql_fetch_array($dbs)){
					echo "<h3>".$row['sign'],"</h3>";
				}
				echo"</div>";
			}
		}
			
		?>		
	</body>

<html/>

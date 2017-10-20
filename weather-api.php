<!DOCTYPE html>
<html lang="en">
	<head>
		<title>World Weather</title>

	    <!-- Required meta tags always come first -->
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta http-equiv="x-ua-compatible" content="ie=edge">
	    <!-- Bootstrap CSS -->
	     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

	    <style type="text/css">
	    	html { 
  				background: url(images/weatherbg.jpg) no-repeat center center fixed; 
  				-webkit-background-size: cover;
  				-moz-background-size: cover;
  				-o-background-size: cover;
  				background-size: cover;
			}	
	    	body {
	    		background: none;
	    		text-align: center;
	    	}
	    	h1 {
	    		margin-top: 80px;
	    		font-size: 175%;
	    	}
	    	h6 {
	    		margin-top: 25px;
	    		margin-bottom: 25px;
	    	}
	    	#city {
	    		margin-top: 20px;
	    		width: 275px;
	    		margin: 0 auto;
	    		float: none;
	    	}
			#submitForm {
				margin-top: 10px;
			}
			#cityMsg {
				padding-top: 25px;
				width: 300px;
				margin: 0 auto;
	    		float: none;
			}
	    </style>
	</head>

	<body>
		<?php
			$cityError = "";
			$city = "";
			$cityMsg = $weatherMsg = "";
			$failError = false;

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (empty($_POST["city"])) {
					$cityError = "<strong>Please enter a valid city name</strong>";
				} else {
					$city = clean_input($_POST["city"]);
				}
				// replace space with a dash
				$city = str_replace(' ', '%20', $city);
				if (empty($cityError)) {
					$failError = false;
					//$urlContents = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$city."&appid=1214c3475103f824386a0b50b1dda0f6");
					$urlContents = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$city."&units=metric&appid=1214c3475103f824386a0b50b1dda0f6");
					$weatherArray = json_decode($urlContents, true);

					$weatherMsg = "";
					$cityError = $weatherArray['message'];
					if ($weatherArray['cod'] != 200) {
						$failError = true;
					} else {
						$cityName = $weatherArray['name'];
						$cityWeather = "Conditions are: ".$weatherArray['weather'][0]['description'];
						//$currTemp = "".round($weatherArray['main']['temp'] - 273.15)."&deg;C";
						$currTemp = "".round($weatherArray['main']['temp'])."&deg;C";
						$weatherMsg = $cityWeather."</br>"."The current temperature is ".$currTemp;
					}
					
					if ($failError) {
						$cityMsg = '<div class="alert alert-danger fade show" role="alert">Cannot find weather for: '.$city.'</br>'.$cityError.'</div>';
					} else {
						$cityMsg = '<div class="alert alert-success fade show" role="alert"><strong>Current weather for: '.$cityName.'</strong></br>'.$weatherMsg.'</div>';	
					}
				} else {
					$cityMsg = '<div class="alert alert-danger fade show" role="alert">'.$cityError.'</div>';
				}
			}

			function clean_input($data)
			{
				$data = trim($data);
  				$data = stripslashes($data);
  				$data = htmlspecialchars($data);
  				return $data;
			}
		?>

		<div class="container">
			<h1>What's the Current Weather like?</h1>
			<h6>Enter the name of a city.</h6>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<fieldset class="form-group">
					<input type="text" class="form-control" id="city" name="city" placeholder="Enter city name. Eg. London, Paris" value="<?php echo $_POST["city"]; ?>">
				</fieldset>
				<button type="submit" id="submitForm" class="btn btn-primary">Submit</button>
			</form>
			<div id="cityMsg">
				<? echo $cityMsg; ?>
			</div>
		</div>

		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
    	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

	    <script type="text/javascript">
	    	$("form").submit(function(e) {
	    		return(validateForm(e));
	    	});

	    	function validateForm(e) {
				var errorMessage = "";

				if ($("#city").val() == "") {
					errorMessage = "<strong>Please enter a valid city name.</strong>"
				}

				if (errorMessage != "") {					
					$("#cityMsg").html('<div class="alert alert-danger fade show" role="alert">' + errorMessage + '</div>');
					return false;
				} else {
					return true;
				}
			}

	    </script>

	</body>

</html>
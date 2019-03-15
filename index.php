<?php 
/**
 * Prueba técnica para Internet República
 * @author Héctor Martín Solís <hecmartinsol@gmail.com>
 * 
 * LA aplicación simplemente crea una instancia de 
 * WeatherApi.php (que implementa a Api.php)
 * estableciendo los parámetros necesarios para obtener la información del API.
 * 
 * Una vez obtenidos, sencillamente se pintan en una tabla (con Bootstrap)
 */


require_once "WeatherApi.php";

$weatherApi = new WeatherApi();
$weatherApi->setProduct("observation");
$weatherApi->setName("Berlin-Tegel");
$weatherApi->setOutputFormat("object");

$res = $weatherApi->getForecast();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Weather Api</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body class="p-5">

	<div class="container">
		<h3>Weather forecast  in <?= $weatherApi->getName() ?></h3>
		<h4><small>(<?= $weatherApi->getProduct() ?>)</small></h4>
	</div>
	<div class="container">
		<table class="table table-hover">
			<thead>
				<tr class="table-primary">
					<th scope="col">city</th>
					<th scope="col">description</th>
					<th scope="col">latitude</th>
					<th scope="col">longitude</th>
					<th scope="col">highTemperature</th>
					<th scope="col">lowTemperature</th>
					<th scope="col">humidity</th>
					<th scope="col">windSpeed</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$rows = "";
				foreach ($res->observations->location as $element) {
					if (!property_exists($element, "observation") && ! isset($element->observation[0])) continue;

					$obs = $element->observation[0];
					$rows .= "
						<tr>
							<th>{$obs->city}</th>
							<td>{$obs->description}</td>
							<td>{$obs->latitude}</td>
							<td>{$obs->longitude}</td>
							<td>{$obs->highTemperature}</td>
							<td>{$obs->lowTemperature}</td>
							<td>{$obs->humidity}</td>
							<td>{$obs->windSpeed}</td>
						</tr>
					";
				} 
				echo $rows;
				?>
			</tbody>
		</table>
	</div>
</body>
</html>
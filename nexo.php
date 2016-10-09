<html>
<head>
	<meta charset="UTF-8">
	<title>Estacionamiento</title>    
	<link rel="stylesheet" href="css/reset.css">
	<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>    
	<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
	<link rel="stylesheet" href="css/style.css">
	<?php include_once "clases.php";?>
</head>

<body>
	<div class="pen-title">
	  <h1>Estacionamiento</h1>
	</div>


	<div class="container">
		<div class="card"></div>
		<div class="card">
			<h1 class="title">Ingreso de datos</h1>
			<form action="index.php" id="FormIngreso">
				<div class="input-container">
					<?php
						//include_once "clases.php";
						$patente = $_POST['patente'];
						$accion = $_POST['accion'];
						if ($accion == "Estacionar") {
							if (Estacionamiento::Guardar($patente)) {
								header("location:index.php");
							}
						} else {
							$autos = Estacionamiento::Sacar($patente);
					}?>

					<div class="bar"></div>
				</div>
				<div class="button-container">
					<button type="submit" value="Volver" name="volver"><span>Volver</span></button>
				</div>
			</form>
		</div>
	</div>

			<?php //Estacionamiento::ImprimirTablas()?>

	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script src="js/index.js"></script>

</body>

</html>


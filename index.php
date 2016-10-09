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

	<div class="container" style="max-width:90%;padding:10px;">
		<div class="container" style="float:left">
			<div class="card"></div>
			<div class="card">
				<h1 class="title">Ingreso de datos</h1>
				<form action="nexo.php" method="post" id="FormIngreso">
					<div class="input-container">
						<input type="text" name="patente" id="patente" required="required" />
						<label for="patente">Patente</label>
						<div class="bar"></div>
					</div>
					<div class="button-container">
						<button type="submit" value="Estacionar" name="accion"><span>Estacionar</span></button>
						<button type="submit" value="Salir" name="accion"><span>Salir</span></button>
					</div>
				</form>
			</div>
		</div>

		<div class="container" style="float:right;max-width:700px;">
			<div class="card"></div>
			<div class="card">
				<h1 class="title">Tablas</h1>
				<?php Estacionamiento::ImprimirTablas()?>
				
			</div>
		</div>
	</div>


	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	<script src="js/index.js"></script>

</body>
</html>
<!--

* Dibujar en index.php los listados de autos estacionados y autos que se retiraron recientemente (últimos 10, por ejemplo).

* 

-->
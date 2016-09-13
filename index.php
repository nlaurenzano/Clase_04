<html>
<head>
	<title>Clase 4</title>
	<link rel="stylesheet" href="estilo.css">
	<?php include_once "clases.php";?>
</head>
<body>

	<!-- <form style="background:<?php echo $_GET['color']; ?>" action="nexo.php" method="post"> -->
	<form action="nexo.php" method="post" id="FormIngreso">

			<label for="patente">Patente: </label>
			<input type="text" name="patente" />
			<input type="submit" value="Estacionar" class="MiBotonUTNMenuInicio" name="accion" />
			<input type="submit" value="Salir" class="MiBotonUTNMenuInicio" name="accion" />

	</form>
			<?php Estacionamiento::ImprimirTablas()?>

</body>
</html>
<!--

* Dibujar en index.php los listados de autos estacionados y autos que se retiraron recientemente (últimos 10, por ejemplo).

* 

-->
<html>
<head>
	<title>Clase 4</title>
	<link rel="stylesheet" href="estilo.css">
	<?php include_once "clases.php";?>
</head>
<body>

	<form action="index.php" id="FormIngreso">

		<?php
		//include_once "clases.php";
		$patente = $_POST['patente'];
		$accion = $_POST['accion'];
		if ($accion == "Estacionar") {
			Estacionamiento::Guardar($patente);
			header("location:index.php");
		} else {
			$autos = Estacionamiento::Sacar($patente);
		}?>

		<input type="submit" value="Volver" class="MiBotonUTNMenuInicio" name="volver" />

	</form>

</body>
</html>


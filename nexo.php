<html>
<head>
	<title>Clase 4</title>

	
</head>
<body>

	<form>




	</form>

</body>
</html>

<?php

include_once "clases.php";

$patente = $_POST['patente'];
$accion = $_POST['accion'];

//echo $patente."  ".$accion;


if ($accion == "Estacionar") {
	Estacionamiento::Guardar($patente);
	header("location:index.php");
} else {
	$autos = Estacionamiento::Sacar($patente);

}

?>
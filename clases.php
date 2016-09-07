<?php

/**
* 
*/
class Estacionamiento
{
	
	function __construct()
	{
		# code...
	}

	public static function Guardar($patente) {
		echo "<p>Listo, estacionado.</p>";

		$miarchivo = fopen("estacionados.txt", "a");	//http://www.w3schools.com/php/func_filesystem_fopen.asp

		//$fecha = date(DATE_W3C);
		$fecha = date("Y-m-d H:i:s");
		$renglon = $patente." - $fecha"."\n";

		fwrite($miarchivo, $renglon);	//Crea el archivo y guarda la patente

		fclose($miarchivo);

	}

	public static function Leer() {
		//echo "<p>Listo, estacionado.</p>";

		$autos = array();
		$miarchivo = fopen("estacionados.txt", "r");	//http://www.w3schools.com/php/func_filesystem_fopen.asp

		while (!(feof($miarchivo))) {
			$renglon = fgets($miarchivo);
			$renglonArray = explode(" - ", $renglon);

			//$autos[$renglonArray[0]] = $renglonArray[1];
			if ($renglonArray[0] != "") {
				$autos[] = $renglonArray;
			}
			//echo '<br />'.$renglonArray[0];
		}
		fclose($miarchivo);
		return $autos;

	}

	public static function Sacar($patente) {
		$estacionados = Estacionamiento::Leer();

		foreach ($estacionados as $auto) {
			
			if ($auto[0] == $patente) {
				// Tenemos el auto estacionado
				$inicio = $auto[1];					// Fecha y hora de ingreso
				$ahora = date("Y-m-d H:i:s");		// Fecha y hora actuales

				$diferencia = strtotime($ahora) - strtotime($inicio);
				$importe = $diferencia * 10;	// Se guarda en ticket.txt				****************************************************

				echo 'Costo = $'.$importe;

			}

		}




	}
}
?>
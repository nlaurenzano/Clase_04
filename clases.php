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
		
		$miarchivo = fopen("estacionados.txt", "a");	//http://www.w3schools.com/php/func_filesystem_fopen.asp



		if (Estacionamiento::ValidarPatente($patente)) {
			if (Estacionamiento::BuscarEstacionado($patente)) {
				echo "La patente '$patente' pertenece a un vehículo que ya registrado en el estacionamiento.";
				return false;
			} else {
				$patente = str_ireplace(" ", "", $patente);
				$patente = strtoupper(chunk_split($patente, 3, " "));

				//$fecha = date(DATE_W3C);
				$fecha = date("Y-m-d H:i:s");
				$renglon = $patente." - $fecha"."\n";

				fwrite($miarchivo, $renglon);	//Crea el archivo y guarda la patente

				fclose($miarchivo);
			}
		} else {
			echo "La patente '$patente' no es válida. El formato aceptado es 'ABC 123'";
			return false;
		}
		return true;
	}

	public static function Leer() {
		$autos = array();
		$miarchivo = fopen("estacionados.txt", "r");	//http://www.w3schools.com/php/func_filesystem_fopen.asp

		while (!(feof($miarchivo))) {
			$renglon = rtrim(fgets($miarchivo));
			$renglonArray = explode(" - ", $renglon);

			if ($renglonArray[0] != "") {
				$autos[] = $renglonArray;
			}
			//echo '<br />'.$renglonArray[0];
		}
		fclose($miarchivo);
		return $autos;

	}

	public static function LeerTickets() {
		$tickets = array();
		$miarchivo = fopen("tickets.txt", "r");	//http://www.w3schools.com/php/func_filesystem_fopen.asp

		while (!(feof($miarchivo))) {
			$renglon = rtrim(fgets($miarchivo));
			$renglonArray = explode(" - ", $renglon);

			if ($renglonArray[0] != "") {
				$tickets[] = $renglonArray;
			}
		}
		fclose($miarchivo);
		return $tickets;

	}

	public static function Sacar($patente) {
		$estacionados = Estacionamiento::Leer();
		$hallado = false;
		$patente = str_ireplace(" ","",$patente);

		foreach ($estacionados as $key => $auto) {
			
			if (strcasecmp(str_ireplace(" ","",$auto[0]), $patente) == 0) {	// Comparación case insensitive
				// Tenemos el auto estacionado
				$hallado = true;

				Estacionamiento::CalcularPrecio($auto);
				Estacionamiento::Eliminar($estacionados, $key);

				break;
			}
		}

		if (!$hallado) {
			echo "La patente '$patente' no pertenece a ningún vehículo registrado en el estacionamiento.";
		}
	}

	// $inicio = Fecha y hora de ingreso
	public static function CalcularPrecio($auto) {
		$inicio = $auto[1];
		$ahora = date("Y-m-d H:i:s");		// Fecha y hora actuales

		$diferencia = strtotime($ahora) - strtotime($inicio);
		$importe = $diferencia * 10;	// Se guarda en ticket.txt

		$miarchivo = fopen("tickets.txt", "a");
		$renglon = "$auto[0] - $inicio - $ahora - $importe\n";
		fwrite($miarchivo, $renglon);	//Crea el archivo y guarda la patente
		fclose($miarchivo);

		echo 'Costo = $'.$importe;

	}

	public static function Eliminar($estacionados,$key) {
		// Elimina el elemento del array de autos estacionados
		unset($estacionados[$key]);

		// Reescribe el archivo de autos estacionados, sin el elemento que se acaba de eliminar
		$miarchivo = fopen("estacionados.txt", "w");	//http://www.w3schools.com/php/func_filesystem_fopen.asp

		foreach ($estacionados as $auto) {
			//$renglon = "$patente - $fecha"."\n";
			$renglon = "$auto[0] - $auto[1]"."\n";
			/*
TKT 896 - 2016-09-07 01:32:12
LMR 798 - 2016-09-07 01:32:26
AAA 111 - 2016-09-07 01:32:32
BBB 222 - 2016-09-07 02:16:56
tkt 896 - 2016-09-12 21:05:11
ppp 123 - 2016-09-12 21:39:00
ccccccc - 2016-09-12 22:01:28

			*/
			fwrite($miarchivo, $renglon);	//Crea el archivo y guarda la patente
		}
		fclose($miarchivo);
	}

	public static function BuscarEstacionado($patente) {
		$estacionados = Estacionamiento::Leer();
		$hallado = false;
		$patente = str_ireplace(" ","",$patente);

		foreach ($estacionados as $key => $auto) {
			
			if (strcasecmp(str_ireplace(" ","",$auto[0]), $patente) == 0) {	// Comparación case insensitive
				// Tenemos el auto estacionado
				$hallado = true;
			}
		}
		return $hallado;
	}

	public static function ValidarPatente($patente) {
		// Validar que el formato de la patente ingresada sea 'ABC123'
		$patente = strtoupper(str_ireplace(" ","",$patente));
		
		if ($patente == "")
			return false;

		$partes = str_split($patente,3);
		
		if (strlen($partes[0]) != 3)
			return false;

		foreach (str_split($partes[0]) as $letra) {
			if (ord($letra) < 65 or ord($letra) > 90 ) {
				return false;
			}
		}
				
		if (isset($partes[1])) {
			if (strlen($partes[1]) != 3)
				return false;

			foreach (str_split($partes[1]) as $numero) {
				if (ord($numero) < 48 or ord($numero) > 57 ) {
					return false;
				}
			}
		}
		return true;
	}

	public static function ImprimirTablas() {

		$estacionados = Estacionamiento::Leer();
		$cobrados = Estacionamiento::LeerTickets(10);

		echo '<div style="padding:10px;">';
		
		echo '<div style="float:left;margin-right: 30px;">';		// Tabla de estacionados
		echo MiHTML::Titulo_2("Vehículos estacionados:");
		echo '<table>';
		echo MiHTML::Fila(MiHTML::Celda("Patente") . MiHTML::Celda("Entrada"));
		foreach ($estacionados as $auto) {
			echo MiHTML::Fila(MiHTML::Celda($auto[0]) . MiHTML::Celda($auto[1]));
		}
		echo '</table></div>';						// Tabla de estacionados

		echo '<div>';		// Tabla de tickets
		echo MiHTML::Titulo_2("Vehículos ya cobrados:");
		echo '<table>';
		echo MiHTML::Fila(MiHTML::Celda("Patente<br />Precio") . MiHTML::Celda("Entrada<br />Salida"));
		foreach ($cobrados as $ticket) {
			$fila = MiHTML::Celda($ticket[0] . "<br />$ " . $ticket[3]) . MiHTML::Celda($ticket[1] . "<br />" . $ticket[2]);
			echo MiHTML::Fila($fila);
		}
		echo '</table></div>';							// Tabla de tickets

		echo "</div>";


	}

}

/**
* 
*/
class MiHTML
{
	
	function __construct()
	{
		# code...
	}

	// Devuelve el parámetro envuelto en tags td
	public static function Celda($contenido) {
		return "<td>$contenido</td>";
	}
	// Devuelve el parámetro envuelto en tags tr
	public static function Fila($contenido) {
		return "<tr>$contenido</tr>";
	}
	// Devuelve el parámetro envuelto en tags h2
	public static function Titulo_2($contenido) {
		return "<label>$contenido</label>";
	}

}
?>
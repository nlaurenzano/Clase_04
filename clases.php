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

				// Agregar foto, si se subió una
				$foto = "Sin foto";
				if(isset($_FILES["foto"])) {
					// Validaciones para el archivo de imagen
					$foto = Estacionamiento::GuardarFoto($patente);
				}

				$renglon = $patente." - $fecha"." - $foto\n";

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
		$renglon = "$auto[0] - $inicio - $ahora - $importe - $auto[2]\n";
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
			$renglon = "$auto[0] - $auto[1] - $auto[2]"."\n";
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
		echo MiHTML::Fila(MiHTML::Celda("Patente") . MiHTML::Celda("Entrada") . MiHTML::Celda("Foto"));
		foreach ($estacionados as $auto) {
			echo MiHTML::Fila(MiHTML::Celda($auto[0]) . MiHTML::Celda($auto[1]) . MiHTML::Celda('<img src="fotos/'.$auto[2].'" style="max-width:100px;">'));
		}
		echo '</table></div>';						// Tabla de estacionados

		echo '<div>';		// Tabla de tickets
		echo MiHTML::Titulo_2("Vehículos ya cobrados:");
		echo '<table>';
		echo MiHTML::Fila(MiHTML::Celda("Patente<br />Precio") . MiHTML::Celda("Entrada<br />Salida") . MiHTML::Celda("Foto"));
		foreach ($cobrados as $ticket) {
			$col1 = MiHTML::Celda($ticket[0] . "<br />$ " . $ticket[3]);
			$col2 = MiHTML::Celda($ticket[1] . "<br />" . $ticket[2]);
			$col3 = MiHTML::Celda('<img src="fotos/'.$ticket[4].'" style="max-width:100px;">');
			$fila = $col1 . $col2 . $col3;
			echo MiHTML::Fila($fila);
		}
		echo '</table></div>';							// Tabla de tickets

		echo "</div>";


	}

	public static function GuardarFoto($patente) {

		if($_FILES["foto"]['error'])
		{
			//error de imagen
			return false;
		}
		else
		{
			$tamanio = $_FILES['foto']['size'];
    		if($tamanio>1024000)
    		{
    			// "Error: archivo muy grande!"."<br>";
    			return false;
    		}
    		else
    		{
    			//OBTIENE EL TAMAÑO DE UNA IMAGEN, SI EL ARCHIVO NO ES UNA
				//IMAGEN, RETORNA FALSE
				$esImagen = getimagesize($_FILES["foto"]["tmp_name"]);
				if($esImagen === FALSE) 
				{
					//NO ES UNA IMAGEN
					return false;
				}
				else
				{
					$NombreCompleto = explode(".", $_FILES['foto']['name']);
					$Extension = end($NombreCompleto);
					$arrayDeExtValida = array("jpg", "jpeg", "gif", "bmp","png");  //defino antes las extensiones que seran validas
					if(!in_array($Extension, $arrayDeExtValida))
					{
					   //"Error archivo de extension invalida";
						return false;
					}
					else
					{
						$destino = "fotos/$patente.$Extension";
						$foto=$patente.".".$Extension;

						// MUEVO EL ARCHIVO DEL TEMPORAL AL DESTINO FINAL
    					if (move_uploaded_file($_FILES["foto"]["tmp_name"],$destino))
    					{		
      						 //echo "ok";
    						return $foto;
      					}
      					else
      					{   
      						// algun error;
      						return false;
      					}

					}
				}
    		}			
		}
		return true;
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
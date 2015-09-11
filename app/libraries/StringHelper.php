<?php

class StringHelper
{

	/**
	* Convierte la fecha para hacer un insert en una tabla mysql.
	* ADVERTENCIA! :  El formato de la fecha que ingresa como parámetro debe ser del tipo dd/mm/aaaa
	*
	*	$string -> el string a formatear
	*	$tipo -> numerico o alfanumerico.
	*	$long -> la longitud que debemos devolver.
	*
	*	Si es numerico alinea a la derecha y rellena con 0. ejemplo: 0000002365
	*	En cambio si es alfanumerico alinea a la izquierda y rellana con espacios. Ejemplo: "876hgh      "
	*
	* @access	public
	* @return	string (el string ingresado ya de manera formateada)
	*/
	static function getDataPad($string, $tipo, $long)
	{

		if ( $tipo == 'numerico' )
		{ // NUMERICO
			$new_string = str_pad($string, $long, "0", STR_PAD_LEFT);
		} else
		{ // ALFANUMERICO
			if (strlen($string) > $long)
			{ // Achico la cadena.
				$string = substr($string, 0, $long);
			}
			$new_string = str_pad($string, $long, " ", STR_PAD_RIGHT);

		}

		return $new_string;

	}



	/**
	* Nos devuelve una letra, según el número que le pasemos.
	* Nos sirve en este caso para poder aplicar un array a las celdas de un excel.
	* OJO, hay que mejorarla, si le pasamos un número mayor a 25 no nos sirve para el excel.
	**/
	static function num_to_letter($num, $uppercase = TRUE)
	{
	//$num -= 1;

	$letter = 	chr(($num % 26) + 97);
	$letter .= 	(floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
	return 		($uppercase ? strtoupper($letter) : $letter);
	}



	static function parseCodMovimiento ( $cod_movimiento )
	{

	}





}
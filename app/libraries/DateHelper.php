<?php

class DateHelper
{

	/**
	 * Convierte la fecha para hacer un insert en una tabla mysql.
	 * ADVERTENCIA! :  El formato de la fecha que ingresa como parámetro debe ser del tipo dd/mm/aaaa
	 *
	 * @access	public
	 * @return	string (la fecha para poder insertarla en la base)
	 */
	static function convertToPhp($fecha)
	{
		$fecha_split = explode("/", $fecha);
		$dia 		= $fecha_split[0];
		$mes 		= $fecha_split[1];
		$anio 		= $fecha_split[2];
		$fecha_php= $anio . '-' . $mes . '-' . $dia;

		$fecha_correcta=date('Y-m-d',strtotime($fecha_php));

		return $fecha_correcta;
	}



	/**
	 * Convierte de formato AAAAMMDD -> dd/mm/YYYY
	 **/
	static function convertEspaniol( $fecha_junta )
	{

		$cant_car = strlen($fecha_junta);

		if ( $cant_car == 8 && is_numeric($cant_car) )
		{

			$anio 	= substr($fecha_junta, 0, 4);
			$mes 	= substr($fecha_junta, 4, 2);
			$dia 	= substr($fecha_junta, 6, 2);

			$fecha_convertida = $dia . '/' . $mes . '/' . $anio;

		} else {
			$fecha_convertida =  '00/00/0000';
		}

		return $fecha_convertida;

	}




}

<?php



class PagomiscuentasController extends BaseController
{



	/**
	 * Constructor. Va a controlar que siga logueado el usuario.
	 *
	 **/
	public function __construct()
	{
		// No lo toma en cuenta el constructor, nosé porque.
		if ( 	!Auth::check() )
		{
			return View::make('template_tisa/login');
		}

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		SecurityHelper::isLogin();


		$data 				= Input::all();

		// VALIDACIONES
		$rules 		=  array('file'=>'required|mimes:txt',
							'horario'=>'required');
		$messages = array(
								'required' 	=> 'El campo :attribute debe ser completado.',
								'mimes' 	=> 'El archivo es incorrecto. Su extensión debe ser .txt.'
							);
		$validator 	= Validator::make($data, $rules, $messages);
		if ($validator->fails())
		{
			return Redirect::route('pmc_post')->withInput()->withErrors($validator);
		}
		// FIN VALIDACIONES

		$save_name_file 	= date('Y.m.d.H_m_s') . Input::file('file')->getClientOriginalName();
		$file_move 			= Input::file('file')->move(public_path() . '/uploads/pmc_envios/',$save_name_file);


		if ( $file_move )
		{
			$gen_file = $this->generateFileIntranet( $save_name_file ); // Nos va a devolver el nombre correcto de archivo con el que se envía a pagomiscuentas.
			if ( $gen_file != NULL )
			{
				$insert_file 		= $this->_insertFile( $gen_file );
				//$send_email 	= $this->_sendEmail($gen_file);
				if ( $insert_file ) {
					return Redirect::to('homepage')->with('success', 'El archivo para subir a Pagomiscuentas fue cargado en la base de datos.');
				}
			} else {
				return Redirect::to('homepage')->with('error', 'No se pudo subir el archivo de Pagomiscuentas. . . .');
			}

		} else {
			return Redirect::to('homepage')->with('error', 'No se pudo subir el archivo de Pagomiscuentas al servidor para codificarlo. . . .');
		}

	}


	/**
	 * Va a generar el archivo a partir del devuelto del excel
	 * que está separado por tabulaciones.
	 *
	 * @team 	Allytech
	 * @author 	Juan Pablo Sosa <juans@allytech.com>
	 * @date 	23-feb-2015
	 *
	 * @param       String
	 * @return      Array()
	 **/
	public function generateFileIntranet( $file_name )
	{
		$handle = fopen(public_path() . '/uploads/pmc_envios/' . $file_name, 'r');
		if ($handle)
		{
			$nro_reg 		= 0;
			$importe_total 	= 0;
			$dw_content 	= ""; // Acá va a ir el contenido de todas las facturas.
			while (( ($line = fgets($handle)) !== false))
			{


					$arr_csv = str_getcsv( $line, "\t");

					if ($nro_reg != 0 && strlen($line) > 10)
					{
						$cod_cliente 		= $arr_csv[0];
						$nombre_cliente	= $arr_csv[1];
						$comprobante		= $arr_csv[2];
						$detalle				= $arr_csv[3];
						$fecha_venc		= $arr_csv[4];
						$fecha_venc 		= $this->_putWellTheDate($fecha_venc);
						$total				= $arr_csv[5];
						$total 				= (float) $total;
						$total 				= number_format($total, 2, '.', ''); // Y esto para poner dos decimales en el total.

						$total_validate 			= (float)$total;
						// Si es negativo no lo sumo en el total.
						if ( $total_validate > 0)
						{
							$importe_total += $total;
						}

						// Controlo que el importe no sea valor 0. Si es así no debo completar el registro por que no tiene nada para pagar.
						if ( $total_validate != 0 && $total_validate > 0)
						{

							$dw_content 		.= $this->_getContentFile($cod_cliente, $comprobante, $detalle, $fecha_venc, $total);
							$nro_reg++;
						}

					}

					if ( $nro_reg == 0) {
						$nro_reg++;
					}

			}

		} else {
			// error opening the file.
		}
		fclose($handle);

		$nro_reg--; // Agrego este parche porque cuenta un registro demás por el encabezado. De esta manera queda bien.
		$importe_total = number_format($importe_total, 2, '.', ''); // Y esto para poner dos decimales en el total.


		$dw_header 	= $this->_getHeaderFile();
		$dw_footer 	= $this->_getFooterFile( $nro_reg, $importe_total);

		$dw_all_file 	= $dw_header . $dw_content . $dw_footer;





		$name_file = $this->_nameFile( Input::get('horario') );

		$write_file = File::put(public_path() . '/uploads/pmc_envios/' . $name_file, $dw_all_file);
		if ($write_file === false)
		{
		    return NULL; // No pudo escribir el archivo.
		}

		return $name_file;
	}


	/**
	 * Genera el archivo para subir a pagomiscuentas.
	 * Me retorna el nombre del archivo que grabó en el servidor. O NULL si no pudo.
	 * -----------------------
	 * Ahora ya este método no lo uso más por que no pasa más por la intranet.
	 * Directamente sube al panel el excel separado por tabulaciones
	 **/
	// public function generateFile( $file_name )
	// {

	// 	$handle = fopen(public_path() . '/uploads/pmc_envios/' . $file_name, 'r');
	// 	if ($handle)
	// 	{
	// 		$nro_reg 		= 0;
	// 		$importe_total 	= 0;
	// 		$dw_content 	= ""; // Acá va a ir el contenido de todas las facturas.
	// 		while (($line = fgets($handle)) !== false)
	// 		{

	// 			if ($nro_reg != 0 && strlen($line) > 10)
	// 			{
	// 				$cod_cliente 		= trim(substr($line, 0, 8));
	// 				$nombre_cliente	= trim(substr($line, 8, 36));
	// 				$comprobante		= trim(substr($line, 44, 10));
	// 				$detalle				= trim(substr($line, 54, 120));
	// 				$fecha_venc		= trim(substr($line, 174, 10));
	// 				$fecha_venc 		= $this->_putWellTheDate($fecha_venc);
	// 				$total				= trim(substr($line, 184, 10));
	// 				$total 				= (float) $total;
	// 				$total 				= number_format($total, 2, '.', ''); // Y esto para poner dos decimales en el total.

	// 				$total_validate 			= (float)$total;
	// 				// Si es negativo no lo sumo en el total.
	// 				if ( $total_validate > 0)
	// 				{
	// 					$importe_total += $total;
	// 				}

	// 				// Controlo que el importe no sea valor 0. Si es así no debo completar el registro por que no tiene nada para pagar.
	// 				if ( $total_validate != 0 && $total_validate > 0)
	// 				{

	// 					$dw_content 		.= $this->_getContentFile($cod_cliente, $comprobante, $detalle, $fecha_venc, $total);
	// 					$nro_reg++;
	// 				}

	// 			}

	// 			if ( $nro_reg == 0) {
	// 				$nro_reg++;
	// 			}


	// 		}

	// 	} else {
	// 		// error opening the file.
	// 	}
	// 	fclose($handle);

	// 	$nro_reg--; // Agrego este parche porque cuenta un registro demás por el encabezado. De esta manera queda bien.
	// 	$importe_total = number_format($importe_total, 2, '.', ''); // Y esto para poner dos decimales en el total.


	// 	$dw_header 	= $this->_getHeaderFile();
	// 	$dw_footer 	= $this->_getFooterFile( $nro_reg, $importe_total);

	// 	$dw_all_file 	= $dw_header . $dw_content . $dw_footer;





	// 	$name_file = $this->_nameFile( Input::get('horario') );

	// 	$write_file = File::put(public_path() . '/uploads/pmc_envios/' . $name_file, $dw_all_file);
	// 	if ($write_file === false)
	// 	{
	// 	    return NULL; // No pudo escribir el archivo.
	// 	}

	// 	return $name_file;
	// }



	/**
	 * Nos devuelve el nombre del archivo en función del horario
	 * que se va a subir a pagomiscuentas.
	 **/
	private function _nameFile( $horario )
	{

		if ( $horario == 'despues' )

{			$fecha = date('Y-m-d');
			$fecha = date('Y-m-d', strtotime($fecha. ' + 1 days'));
			$fecha = date('dmy', strtotime($fecha));
		} else { // Antes de las 14.30hs
			$fecha = date('dmy');
		}

		$name_file = 'FAC3363.' . $fecha;

		return $name_file;

	}


	/**
	 * Nos devuelve lo que va a ser el header del archivo.
	 **/
	private function _getHeaderFile()
	{


		$date 		= date('Ymd');
		$horario 	= Input::get('horario');
		if ( $horario == 'despues')
		{
			$date = date('Ymd', strtotime($date. ' + 1 days'));
		}

		$init 			= "0";
		$banelco		= "400";
		$codempresa	= "3363";
		$filler			= StringHelper::getDataPad('0', 'numerico', 264);
		$header 		= $init . $banelco . $codempresa . $date . $filler . "\n";


		return $header;
	}

	// $this->_getContentFile($cod_cliente, $comprobante, $detalle, $fecha_venc, $total)




	private function _getContentFile( $cod_cliente, $comprobante, $detalle, $fecha_venc, $total)
	{

		$total 					= str_replace(".", "", $total);
		$total 					= str_replace("-", "", $total);

		$detalle 				= strtoupper($detalle);

		$dw_inicio_cont 		= "5";
		$dw_nro_referencia 	= StringHelper::getDataPad($cod_cliente, 'alfanumerico', 19);
		$dw_id_factura 		= StringHelper::getDataPad($comprobante, 'alfanumerico', 20);
		$dw_cod_moneda 		= "0";
		$dw_fecha_1er_venc 	= StringHelper::getDataPad($fecha_venc, 'numerico', 8);
		$dw_import_1er_venc 	= StringHelper::getDataPad($total, 'numerico', 11);
		$dw_fecha_2do_venc 	= StringHelper::getDataPad('0', 'numerico', 8);
		$dw_import_2do_venc = StringHelper::getDataPad('0', 'numerico', 11);
		$dw_fecha_3er_venc 	= StringHelper::getDataPad('0', 'numerico', 8);
		$dw_import_3er_venc 	= StringHelper::getDataPad('0', 'numerico', 11);
		$dw_cont_filler_uno	= StringHelper::getDataPad('0', 'numerico', 19);
		$dw_nro_referencia_ant= StringHelper::getDataPad($cod_cliente, 'alfanumerico', 19);
						// Esto salvo que hayamos modificado el id de la empresa, se debe mantener el mismo
						// caso contrario debemos avisar a pagomiscuentas y acá poner el viejo id que tenia solamente la primera vez
		$dw_mens_tick			= StringHelper::getDataPad('FACTURA NRO. ' . $comprobante, 'alfanumerico', 40); // Ancho: 40 (AN)
		$dw_mens_pant		= StringHelper::getDataPad('FACT ' . $comprobante, 'alfanumerico', 15);
		$dw_codigo_barra 		= StringHelper::getDataPad('', 'alfanumerico', 60); 	// 60 carácteres, empieza en 192, termina en 251
		$dw_filler_dos			= StringHelper::getDataPad('0', 'numerico', 29);

		$dw_contenido 	= $dw_inicio_cont . $dw_nro_referencia . $dw_id_factura . $dw_cod_moneda . $dw_fecha_1er_venc . $dw_import_1er_venc
							. $dw_fecha_2do_venc . $dw_import_2do_venc . $dw_fecha_3er_venc . $dw_import_3er_venc . $dw_cont_filler_uno
							. $dw_nro_referencia_ant . $dw_mens_tick . $dw_mens_pant . $dw_codigo_barra . $dw_filler_dos . "\n";




		return $dw_contenido;


	}



	/**
	 * Nos devuelve lo que va a ser el footer del archivo.
	 **/
	private function _getFooterFile( $cant_reg, $tot_importe)
	{
		$cant_reg 			= (string) $cant_reg;
		$expl_importe 		= explode(".", $tot_importe);
		$importe_unido 	= $expl_importe[0] . $expl_importe[1];
		$tot_importe		= (string) $importe_unido;


		// Controlo fecha.
		$date 		= date('Ymd');
		$horario 	= Input::get('horario');
		if ( $horario == 'despues')
		{
			$date = date('Ymd', strtotime($date. ' + 1 days'));
		}

		$dw_end 			= "9";
		$banelco			= "400";
		$codempresa		= "3363";
		$dw_cant_reg		= StringHelper::getDataPad($cant_reg, 'numerico', 7 );	// Acá vamos a tener que calcularlos.
		$dw_filler_uno 		= StringHelper::getDataPad("0", 'numerico', 7);
		$dw_tot_importe	= StringHelper::getDataPad($tot_importe, 'numerico', 11);

		$dw_filler_dos		= StringHelper::getDataPad("0", 'numerico', 239);
		$footer 			= $dw_end . $banelco . $codempresa . $date . $dw_cant_reg . $dw_filler_uno . $dw_tot_importe . $dw_filler_dos . "\n";

		return $footer;
	}




	/**
	 * Pongo en el formato que la necesito la fecha.
	 * ATENCION! Debo pasar como parámetro la fecha tipo "dd/mm/aaaa" Y nos devuelve "Ymd"
	 **/
	private function _putWellTheDate( $fecha )
	{

		$nueva_fecha = DateHelper::convertToPhp( $fecha );
		$nueva_fecha = str_replace("-", "", $nueva_fecha);

		return $nueva_fecha;
	}


	/**
	 * Envio el email al usuario logueado.
	 *
	 * @team 	Allytech
	 * @author 	Juan Pablo Sosa <juans@allytech.com>
	 * @date 	default
	 *
	 * @param       String
	 * @return      String
	 **/
	private function _sendEmail( $filename )
	{

		// Datos para el envío del email
		$data['filename']	= $filename;
		$data['email'] 		= Auth::user()->email;
		$data['name'] 		= Auth::user()->name;
		$data['horario'] 	= Input::get('horario');


		if ( $data['horario'] == 'antes' ) {
			$data['template_email'] = 'emails/pmc_gen_archivo_antes';
		} else {
			$data['template_email'] = 'emails/pmc_gen_archivo_despues';
		}
		// Fin de datos para el envío

		Mail::send($data['template_email'], array('ppe'=>'eppe'), function ($message) use ($data)
		{
			$message->from('admin@admin.com', 'Pagomiscuentas');
			$message->to($data['email'], $data['name'])->subject('Para enviar a Pagomiscuentas');
			$message->attach(public_path() . '/uploads/pmc_envios/' . $data['filename']);
		});

		return true;

	}




	public function toExport()
	{
		try {

			SecurityHelper::isLogin();

			$data 				= Input::all();
			// VALIDACION
			$messages = array(
					'validate_file_cobranzas' 			=> 'El archivo de cobranzas es incorrecto.',
					// 'validate_file_cobranzas_content' 	=> 'El archivo de cobranzas se encuentra sin ningún pago.',
					'required' 							=> 'El campo :attribute debe ser completado'
				);
			$rules = array(
				'file' 		=> array('validate_file_cobranzas', 'required')
			);
			$validator = Validator::make($data, $rules, $messages);
			if ($validator->fails())
			{
				return Redirect::to('pmc_export_excel')->withErrors($validator);
			}
			// FIN DE VALIDACION


			$data_array 	= $this->_createArray();

			$create_excel 	= $this->_createExcel( $data_array );



			if ( $create_excel != NULL )
			{
				$insert_file_db 	= $this->_insertFile( $create_excel, 'cobranzas');
				//$send_email = $this->_sendEmailExcelCobranzas($create_excel);
				if ( $insert_file_db ) {
					return Redirect::to('homepage')->with('success', 'El excel generado de cobranzas de Pagomiscuentas fue insertado en la base.');
				}
			} else {
				return Redirect::to('homepage')->with('error', 'No se pudo generar el excel de cobranzas de Pagomiscuentas. . . .');
			}








		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}




	/**
	 * Va a generar un array en función de los datos del archivo de cobranzas,
	 * para que luego se pueda pasar a excel.
	 **/
	private function _createArray()
	{


		$save_name_file = date('Y_m_d_H_m_s') . '_' . Input::file('file')->getClientOriginalName();
		$file_up 	= Input::file('file');
		$file_move 	= $file_up->move(public_path() . '/uploads/pmc_cobranzas/', $save_name_file);



		if ( $file_move )
		{
			// TITULOS
			$data_array[0][0] 		= '';
			$data_array[0][1] 		= 'Ref.';
			$data_array[0][2] 		= 'Factura';
			$data_array[0][3] 		= 'Vencimiento';
			//$data_array[0][4] 	= 'Cod Moneda';
			$data_array[0][4] 		= 'Aplicacion';
			$data_array[0][5] 		= 'Importe';
			$data_array[0][6] 		= 'Cod Movimiento';
			$data_array[0][7] 		= 'Acreditacion';
			$data_array[0][8] 		= 'Canal';
			$data_array[0][9] 		= 'Control';
			// $data_array[0][11] 	='Codigo de provincia';


			$handle = fopen(public_path() . '/uploads/pmc_cobranzas/' . $save_name_file, 'r');
			$cant_lineas = file(public_path() . '/uploads/pmc_cobranzas/' . $save_name_file);
			$cant_lineas = count($cant_lineas);
			if ($handle)
			{
				$nro_reg 		= 0;
				while (($line = fgets($handle)) !== false)
				{

					if ($nro_reg != 0 && strlen($line) > 10 && $nro_reg < ($cant_lineas - 1))
					{
						$comienzo 		= trim(substr($line, 0, 1));
						$nro_referencia	= trim(substr($line, 1, 19));
						$id_factura			= trim(substr($line, 20, 20));
						$fecha_venc		= DateHelper::convertEspaniol(trim(substr($line, 40, 8)));
						$cod_moneda		= trim(substr($line, 48, 1));
						$fecha_aplicacion	= DateHelper::convertEspaniol(trim(substr($line, 49, 8)));
						$importe			= number_format(trim(substr($line, 57, 11)), 2, '.', '') / 100;
						$cod_movimiento	= trim(substr($line, 68, 1));
						if ( $cod_movimiento == '2') {
							$cod_movimiento = "Con Factura";
						} else {
							$cod_movimiento = "Sin Factura";
						}

						$fecha_acred		= DateHelper::convertEspaniol(trim(substr($line, 69, 8)));
						$canal_pago		= trim(substr($line, 77, 2));
						if ( $canal_pago == 'PC' ) {
							$canal_pago = 'Pagomiscuentas';
						} else if ( $canal_pago == 'HB' ) {
							$canal_pago = 'Home Banking';
						} else if ( $canal_pago == 'S1') {
							$canal_pago = 'ATM';
						} else {
							$canal_pago = "No especifica";
						}
						$nro_control		= trim(substr($line, 79, 4));
						$cod_prov			= trim(substr($line, 83, 3));
						$filler				= trim(substr($line, 86, 14));
						$data_array[$nro_reg][0]	= '';
						$data_array[$nro_reg][1] 	= $nro_referencia;
						$data_array[$nro_reg][2] 	= $id_factura;
						$data_array[$nro_reg][3] 	= $fecha_venc;
						// $data_array[$nro_reg][4] 	= $cod_moneda;
						$data_array[$nro_reg][4] 	= $fecha_aplicacion;
						$data_array[$nro_reg][5] 	= $importe;
						$data_array[$nro_reg][6] 	= $cod_movimiento;
						$data_array[$nro_reg][7] 	= $fecha_acred;
						$data_array[$nro_reg][8] 	= $canal_pago;
						$data_array[$nro_reg][9] =$nro_control;
						// $data_array[$nro_reg][11] =$cod_prov;
						$nro_reg++;
					}

					if ( $nro_reg == 0) {
						$nro_reg++;
					}


			}

		} else {
			// error opening the file.
		}
		fclose($handle);

		// $nro_reg--;
		// unset($data_array[$nro_reg]);

		return $data_array;


		} else {
			return Redirect::to('homepage')->with('error', 'No se pudo subir el archivo de Pagomiscuentas al servidor para codificarlo. . . .');
		}

	}


	/**
	 * Genera el excel con el array que le pasamos
	 **/
	private function _createExcel( $data_array )
	{

		define('BORDER_THIN', 'thin');

		$cant_filas = (count($data_array) - 1);

		// Agrego el numero de celda y dejo su valor, para poder darle formato a cada celda en particular.
		// Y a la vez creo una variable con el valor de la último celda; me va a servir para el formato.
		$fila_end = false;
		foreach($data_array AS $k_col=>$lista)
		{
			if ($k_col == $cant_filas)	{$fila_end = true;}

			foreach( $data_array[$k_col] AS $k_fila=>$fila)
			{
				$data_array[$k_col][$k_fila] = array();
				$data_array[$k_col][$k_fila]['celda'] = (string)(StringHelper::num_to_letter($k_fila)) . ($k_col + 4);
				$data_array[$k_col][$k_fila]['valor'] = $fila;
				if ( $fila_end && !isset($data_array[$k_col][$k_fila+1]))
				{
					$rango_total 			= "A3:" . 	$data_array[$k_col][$k_fila]['celda'];
					$letra_ultima_columna 	= (string)(StringHelper::num_to_letter($k_fila));
				}
			}
		}

		$excel 		= new PHPExcel();

		$planilla 	= $excel->getActiveSheet();


		// AutoWidth en todas las columnas
		// $i = 0;
		// do
		// {
		// 	$excel->getActiveSheet()->getColumnDimension(num_to_letter($i))->setAutoSize(true);
		// 	$i++;
		// } while ( num_to_letter($i) != $letra_ultima_columna);
		// $excel->getActiveSheet()->getColumnDimension(num_to_letter($i))->setAutoSize(true);

		// Vuevlo a recorrer el array para darle formato.
		foreach($data_array AS $k_fila=>$filas)
		{
			foreach( $data_array[$k_fila] AS $k_col=>$columnas)
			{
				$celda 	= $data_array[$k_fila][$k_col]['celda'];
				$valor 	= $data_array[$k_fila][$k_col]['valor'];
				$planilla->getCell($celda)->setValue($valor);
			}
		}

		$planilla->getColumnDimension('B')->setAutoSize(true);
		$planilla->getColumnDimension('C')->setAutoSize(true);
		$planilla->getColumnDimension('D')->setAutoSize(true);
		$planilla->getColumnDimension('E')->setAutoSize(true);
		$planilla->getColumnDimension('F')->setAutoSize(true);
		$planilla->getColumnDimension('G')->setAutoSize(true);
		$planilla->getColumnDimension('H')->setAutoSize(true);
		$planilla->getColumnDimension('I')->setAutoSize(true);
		$planilla->getColumnDimension('J')->setAutoSize(true);

		// Horizontal
		$planilla->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$planilla->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$planilla->getPageSetup()->setFitToPage(true);


		// Titulo de la Lista de Precios
		$titulo = 'Pagomiscuentas pagos por clientes.';
		$estiloTituloColumnas = array(
									'font' => array(
													'name'  => 'Arial',
													'bold'  => true,
													'color' => array(
													'rgb' => 'FFFFFF'
													)
												),
									'fill' => array(
													'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
													'rotation'   => 90,
													'startcolor' => array(
													'rgb' => 'c47cf2'
													),
													'endcolor' => array(
													'argb' => 'FF431a5d'
													)
												),
									'borders' => array(
													'top' => array(
													'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
													'color' => array(
													'rgb' => '143860'
													)
													),
													'bottom' => array(
													'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
													'color' => array(
													'rgb' => '143860'
													)
													)
												),
									'alignment' =>  array(
													'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER,
													'wrap'      => TRUE
												)
									);
		$planilla->getStyle('A2:F2')->applyFromArray($estiloTituloColumnas);
		$planilla->getCell('A2')->setValue($titulo);

		$planilla->mergeCells('A2:H2'); // Merge de las celdas para que el titulo entre bien.
		unset($estiloTituloColumnas);
		// Fin de titulo de la lista de precios



		// Agrego el borde
		$styleArray = array(
						'borders' => array(
											'allborders' => array(
																'style' => PHPExcel_Style_Border::BORDER_THIN
																)
											)
							);


		$planilla->getStyle($rango_total)->applyFromArray($styleArray);
		unset($styleArray);
		// FIN borde


		$nombre_archivo 	= date('Y.m.d.H_m_s_') . 'excel_pmc.xlsx';
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer->save(public_path() . '/uploads/pmc_cobranzas/' . $nombre_archivo);




		return $nombre_archivo;

	}






	/**
	 * Envio el email al usuario logueado.
	 *
	 * @team 	Allytech
	 * @author 	Juan Pablo Sosa <juans@allytech.com>
	 * @date 	default
	 *
	 * @param       String
	 * @return      String
	 **/
	private function _sendEmailExcelCobranzas( $filename )
	{

		// Datos para el envío del email
		$data['filename']	= $filename;
		$data['email'] 		= Auth::user()->email;
		$data['name'] 		= Auth::user()->name;
		// Fin de datos para el envío

		Mail::send('emails/pmc_gen_excel_cobranzas', array('ppe'=>'eppe'), function ($message) use ($data)
		{
			$message->from('admin@admin.com', 'Pagomiscuentas');
			$message->to($data['email'], $data['name'])->subject('Cobranzas de Pagomiscuentas');
			$message->attach(public_path() . '/uploads/pmc_cobranzas/' . $data['filename']);
		});

		return true;

	}

	/**
	 * Inserto en la tabla files el archivo guardado en el servidor
	 * Si es que ya existe va a modificar el campo updated_at
	 **/
	private function _insertFile( $file_name, $type = 'facturacion' )
	{
		if ( $type != 'facturacion' ) {
			$type = 'cobranzas';
		}

		$id_user 		= (int)Auth::id();
		$file_search 	= DB::table('files')->where('name', $file_name)->first(); // Controlo si existe en la tabla.

		if ( isset($file_search))
		{
			$id_file 			= $file_search->id;
			$file 			= TheFile::find($id_file);
			$file->user_id 	= $id_user;
			$file->updated_at = date('Y-m-d G:i:s');
		} else {
			$file = new TheFile();
			$file->name 			= $file_name;
			$file->date_generated 	= date('Y-m-d');
			$file->user_id 			= $id_user;
			$file->type 				= $type;
		}

		if($file->save()) {
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Controlador del listado de las facturas.
	 **/
	public function listado_facturas()
	{

		$all_files_facturas = TheFile::where('type', '=', 'facturacion')->orderBy('id', 'DESC')->paginate(10);


		foreach ($all_files_facturas AS $k=>$file) {
			$all_files_facturas[$k]->nombre_usuario = User::find($file->user_id)->email;
		}

		return View::make('template_tisa/listado_facturas', array('files'=>$all_files_facturas));
	}


	/**
	 * Controlador del listado de cobranzas.
	 **/
	public function listado_cobranzas()
	{

		$all_files_facturas = TheFile::where('type', '=', 'cobranzas')->orderBy('id', 'DESC')->paginate(10);


		foreach ($all_files_facturas AS $k=>$file) {
			$all_files_facturas[$k]->nombre_usuario = User::find($file->user_id)->email;
		}

		return View::make('template_tisa/listado_cobranzas', array('files'=>$all_files_facturas));
	}



}

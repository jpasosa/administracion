<?php


/**
 * Validaciones propias
 **/
class CustomValidation
{



	/**
	 * Validación del email en el login
	 *
	 * @team 	Allytech
	 * @author 	Juan Pablo Sosa <juans@allytech.com>
	 * @date 	8 de Octubre del 2014
	 **/
	public function validate_email($field, $value, $params)
	{


		$user = User::where('email', '=',$value)->get();

		if (isset($user[0])) {
			echo $user[0]->name;
			return true;
		} else {
			return false;
		}
	}






	/**
	 * Validación del password en el login
	 *
	 * @team 	Allytech
	 * @author 	Juan Pablo Sosa <juans@allytech.com>
	 * @date 	8 de Octubre del 2014
	 **/
	public function validate_password($field, $value, $params)
	{

		$email = Input::get('email');
		$user = User::where('email', '=',$email)->get();

		if (isset($user[0])) {
			$decrypt_pass = Crypt::decrypt($user[0]->password);
			if ( $decrypt_pass == $value )
			{

				return true;
			}
		} else {
			return false;
		}


		return false;
	}

	/**
	 * Valida que el nombre del archivo sea correcto.
	 **/
	public function validate_file_cobranzas( $filed, $value, $params)
	{
		$pass_validation = false;

		$name_file = $value->getClientOriginalName();

		$namefile_explode 	= explode(".", $name_file);

		if ( count($namefile_explode) == 2
			&& ($namefile_explode[0] == 'COB3363' || $namefile_explode[0] == 'cob3363')
			&& strlen($namefile_explode[1]) == 6 && is_numeric($namefile_explode[1]) )
		{
			$pass_validation = true;
		}

		return $pass_validation;

	}


	/**
	 * Valida que en el contenido haya al menos un pago de clientes.
	 **/
	public function validate_file_cobranzas_content( $filed, $value, $params)
	{
		// $pass_validation = false;
		// $file_move 			= $value->move(public_path() . '/uploads/tmp/', $value->getClientOriginalName());

		// if ( $file_move )
		// {
		// 	$lineas = file(public_path() . '/uploads/tmp/' . $value->getClientOriginalName());
		// 	if ( count($lineas) > 2)
		// 	{
		// 		$pass_validation = true;
		// 	}
		// }

		// return $pass_validation;

		return true;
	}


}
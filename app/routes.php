<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/cargo_archivo', function()
{
	$usuario = User::find(2);

	$archivo = new TheFile;
	$archivo->name = 'file_name_archivo.txt';

	$archivo->user_id = $usuario->id;
	$archivo->type = 'cobranzas';
	// $archivo->file()->associate($usuario);
	$archivo->save();
});

Route::get('/', function()
{
	// return View::make('hello');
	if ( 	Auth::check() )
	{
		return View::make('template_tisa/homepage');
	} else {
		return View::make('template_tisa/login');
	}
});



Route::get('login', array('as'=>'login_get', function()
{
	if ( 	Auth::check() )
	{

		return View::make('template_tisa/homepage');
	} else {
		return View::make('template_tisa/login');
	}

}));


/**
 * PERFIL
 **/
Route::get('perfil', array('as'=>'perfil_get', function()
{
	$id = Auth::id();
	$user = User::find($id);
	return View::make('template_tisa/perfil', array('user'=>$user));
}));

/**
** PERFIL POST
**/
Route::post('perfil', array('as' => 'perfil_post', 'uses'=>'PerfilController@index'));







Validator::extend('validate_email', 'CustomValidation@validate_email');
Validator::extend('validate_password', 'CustomValidation@validate_password', array('var1'=>'pepe'));
Validator::extend('validate_file_cobranzas', 'CustomValidation@validate_file_cobranzas', array('var1'=>'pepe'));
// Validator::extend('validate_file_cobranzas_content', 'CustomValidation@validate_file_cobranzas_content', array('var1'=>'pepe'));




/**
 * POST del login
 **/
Route::post('login', array('before' => 'csrf', function()
{
	// DATOS
	$data = Input::all();
	// Mensajes de validación
	$messages = array(
			'validate_email' 			=> 'El email ingresado no es valido.',
			'validate_password' 	=> 'La clave ingresada es incorrecta.',
			'required' 				=> 'El campo :attribute debe ser completado'
		);
	// Reglas de validación
	$rules = array(
		'email' 		=> array('validate_email', 'required'),
		'password' 	=> array('validate_password', 'required')
	);
	$validator = Validator::make($data, $rules, $messages);

	if ($validator->passes() || Auth::check() )
	{

		// $user_login = new User();
		User::getIdByName(Input::get('email'));

		if (Auth::check())
		{
			$id 				= Auth::id();
			$user 			= User::find($id);
			$image_name 	= $user->image;
			Session::put('image_user', $image_name);

			return Redirect::to('homepage');
		} else {
			return Redirect::to('homepage');
		}




	} else { // ERROR. Clave o email incorrectos.
		return Redirect::to('login')->withErrors($validator);
	}

}));






Route::get('homepage', array( function()
{
	if ( Auth::check() )
	{
		return View::make('template_tisa/homepage');
	} else {
		return View::make('template_tisa/login');
	}
}));



/**
** Pagomiscuentas. Vista de generar archivo.
**/
Route::get('pmc', array( 'as'=>'pmc_get', function() {
	return View::make('template_tisa/pmc');
}));
/**
** Pagomiscuentas. Cuando hice click en "Generar Archivo" en el menú de "Generar archivo para entregar"
**/
Route::post('pmc', array('as' => 'pmc_post', 'uses'=>'PagomiscuentasController@index'));


Route::get('pmc_export_excel', array( 'as'=>'pmc_export_excel', function() {
	return View::make('template_tisa/pmc_export');
}));

/**
** Pagomiscuentas. Cuando hice click en "Generar Archivo" en el menú de "Generar archivo para entregar"
**/
Route::post('pmc_export_excel_post', array('as' => 'pmc_export_excel_post', 'uses'=>'PagomiscuentasController@toExport'));



Route::get('logout', 'LoginController@logout');





Route::get('email', function()
{
	Mail::send('emails/pmc_gen_archivo_antes', array('name'=>'pepe'), function ($message) {
		$message->to('juanpablososa@gmail.com', 'Juan Pablo')->subject('Titulo');
	});
});


Route::get('/test_eloq', function()
{
    $user = User::find(2);

    $nombre = $user->name;

});


/**
** Pagomiscuentas. Listado de las facturas.
**/
Route::get('listado_facturas', array( 'as'=>'listado_facturas', 'uses'=>'PagomiscuentasController@listado_facturas'));


/**
** Pagomiscuentas. Listado de cobranzas.
**/
Route::get('listado_cobranzas', array( 'as'=>'listado_cobranzas', 'uses'=>'PagomiscuentasController@listado_cobranzas'));


/**
 * Baja el archivo de la lista
 **/
Route::get('download_file/{name_file}/{tipo_file}', function( $name_file, $type_file )
{
	if ( $type_file == 'cobranzas') {
		$dir_name = 'pmc_cobranzas';
	} else {
		$dir_name = 'pmc_envios';
	}
	return Response::download('uploads/' . $dir_name . '/' . $name_file, '', array(
	             'Content-type: text/plain'));
});

/**
 * Marcar el archivo como que fue subido al servidor de pagomiscuentas
 **/
Route::get('marcar_subido/{id}/{tipo}', function( $id, $type )
{
	// marco como subido;
	TheFile::where('id', '=', $id)->update(array('upload' => 1));
	if ( $type == 'cobranzas' ) {
		$redirect = 'listado_cobranzas';
	} else {
		$redirect = 'listado_facturas';
	}
	return Redirect::to($redirect);

});

/**
 * Marcar el archivo como que todavía no fue subido al servidor de pagomiscuentas
 **/
Route::get('desmarcar_subido/{id}/{tipo}', function( $id, $type )
{
	// marco como subido;
	TheFile::where('id', '=', $id)->update(array('upload' => 0));
	if ( $type == 'cobranzas' ) {
		$redirect = 'listado_cobranzas';
	} else {
		$redirect = 'listado_facturas';
	}
	return Redirect::to($redirect);

});


/**
 * Para insertar los usuarios que necesitemos
 **/
Route::get('insert_users', function()
{

	$date_at = DateTime::createFromFormat('Y-m-d H:i:s', '2014-10-08 14:40:08');

	$pass = Crypt::encrypt('123456');

	$user = new User;
	$user->name 		= 'Juan Pablo';
	$user->last_name 	= 'Sosa.';
	$user->email 		= 'juanpablososa@gmail.com';
	$user->password 	= $pass;
	$user->access_at 	= $date_at;

	$user->save();

});














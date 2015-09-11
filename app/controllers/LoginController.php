<?php

class LoginController extends BaseController {


	public function index()
	{
		$data_post = Input::all();

		if (count($data_post) == 0)
		{
			$data_post['email'] 			= '';
			$data_post['password']	= '';
		}

		return View::make('login/login', $data_post);

	}



	/*
	| 	Valido al usuario que está intentando ingresar
	|	Route::get('/sarasa', 'LoginController@validate');
	*/
	public function validate( $email, $password)
	{
		$data_post['email'] 			= $email;
		$data_post['password'] 	= $password;

		// Controla el email y el password y redirecciona.
		if (true) {
			return Redirect::to('admin');
		} else {
			return redirect::to('login', array('data_post' => $data_post));
		}
	}




	/*
	| 	Hago un logout
	*/
	public function logout()
	{
		Session::flush(); // removes all session data

		// Auth::logout();
		return Redirect::to('login');
	}






	// public function validateEmail( $attribute, $value, $parameter)

}

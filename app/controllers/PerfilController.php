<?php

class PerfilController extends BaseController {


	public function index()
	{

		SecurityHelper::isLogin();

		$data 				= Input::all();






		// VALIDACIONES
		$rules 		=  array(	'name'		=>'required',
								'lastname'	=>'required',
								'email'		=>'required|email',
								'password'	=>'required|min:8',
								'file'			=>'mimes:jpeg,bmp,png|max:1000|min:1');
		$messages = array(
								'required' 	=> 'El campo :attribute debe ser completado.',
								'email' 		=> 'El mail es incorrecto.',
								'min'		=> 'La clave debe tener al menos 8 caracteres.',
								'mimes'		=> 'El archivo debe ser de un formato de imágen ( jpeg, bmp, png)',
								'max'		=> 'La imágen no puede ser superior a 1Mega'
							);
		$validator 	= Validator::make($data, $rules, $messages);
		if ($validator->fails())
		{
			$user = new User();
			$user->setName 		= Input::get('name');
			$user->setLastname 	= Input::get('lastname');
			$user->setEmail 		= Input::get('email');
			$user->setPassword	= Crypt::encrypt(Input::get('password'));

			return Redirect::route('perfil_post', array('user'=>$user))->withInput()->withErrors($validator);
		}
		// FIN VALIDACIONES


		// Controlo si subió o NO una imágen.
		if (Input::hasFile('file'))
		{
			$update_perfil = $this->_updatePerfil('with_new_image');
		} else {
			$update_perfil = $this->_updatePerfil('no_image');
		}

		// Controlo si pude hacer el update correctamente.
		if ( $update_perfil )
		{

			$id 				= Auth::id();
			$user 			= User::find($id);
			$image_name 	= $user->image;
			Session::put('image_user', $image_name);

			return Redirect::to('homepage')->with('success', 'Su perfil fue editado correctamente.');
		} else {
			return Redirect::to('homepage')->with('error', 'No se pudo subir la imágen seleccionada.');
		}




	}


	private function _updatePerfil( $status_image )
	{
		if ( $status_image == 'no_image' )
		{

			// No subió ninguna imágen
			$id 		= Auth::id();
			$user 	= User::find($id);
			$user->name 		= Input::get('name');
			$user->last_name 	= Input::get('lastname');
			$user->email 		= Input::get('email');
			$user->password 	= Crypt::encrypt(Input::get('password'));

			$user->save();

			return true;


		} else
		{ // Subió una imágen nueva.

			$save_name_file 	= date('Y.m.d.H_m_s') . Input::file('file')->getClientOriginalName();
			$file_move 			= Input::file('file')->move(public_path() . '/uploads/perfil/',$save_name_file);

			if ( $file_move )
			{

				$id 		= Auth::id();
				$user 	= User::find($id);

				$user->name 		= Input::get('name');
				$user->last_name 	= Input::get('lastname');
				$user->email 		= Input::get('email');
				$user->password 	= Crypt::encrypt(Input::get('password'));
				$user->image 		= $save_name_file;

				$user->save();

				return true;

			} else {

				return false;
			}
		}
	}


}

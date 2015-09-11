<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

// class User extends Eloquent implements UserInterface, RemindableInterface {
// Cambié esto por que estoy haciendo manualmente la tabla de user


class User extends Eloquent implements UserInterface, RemindableInterface
{

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');



	/**
	 * Método estático. Lo uso para que me devuelva el id del usuario que está ingresando
	 **/
	static function getIdByName( $name )
	{
		$user = User::where('email', '=', $name)->get();

		if (isset($user[0])) {
			$id = $user[0]->id;
			Auth::loginUsingId($id);
			return true;
		}

		return Redirect::to('login_get');

	}

	// public function _files()
	// {
	// 	return $this->hasMany('File');
	// }

}

<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

// class User extends Eloquent implements UserInterface, RemindableInterface {
// Cambié esto por que estoy haciendo manualmente la tabla de user


class TheFile extends Eloquent
{

	protected $table = 'files';


	public function user() {
		return $this->belongsTo('User');
	}
}

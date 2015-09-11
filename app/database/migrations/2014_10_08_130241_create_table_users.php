<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


/**
 * Creo la tabla users, para que se puedan loguear y modificar su perfil.
 *
 * @team 	Allytech
 * @author 	Juan Pablo Sosa <juans@allytech.com>
 * @date 	8 de Octubre del 2014
 *
 **/


class CreateTableUsers extends Migration {



	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('name', 128);
			$table->string('last_name', 128);
			$table->string('email', 128);
			$table->string('pass', 255);
			$table->string('image', 255);
			$table->timestamp('access_at', 128);
			$table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}

}

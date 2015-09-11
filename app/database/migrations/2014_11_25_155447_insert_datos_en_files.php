<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDatosEnFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		$file_insert = new TheFile;
		$file_insert->name = 'FAC3363.201114';
		$file_insert->date_generated = '2014-11-20';
		$file_insert->user_id = 2;
		$file_insert->upload = 0;
		$file_insert->type = 'facturacion';
		$file_insert->save();

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}

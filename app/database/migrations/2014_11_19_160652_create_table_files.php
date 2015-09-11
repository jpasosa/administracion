<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function( $table) {
			$table->increments('id');
			$table->string('name', 256);
			$table->date('date_generated');
			$table->integer('user_id')->unsigned();
	            $table->foreign('user_id')->references('id')->on('users');
	            $table->tinyInteger('upload')->default(0);
			$table->enum('type', array('facturacion', 'cobranzas'));
	            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::drop('files');
	}

}

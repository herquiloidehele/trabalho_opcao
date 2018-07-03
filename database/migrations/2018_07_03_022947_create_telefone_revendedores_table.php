<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTelefoneRevendedoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('telefone_revendedores', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('designacao', 45)->nullable();
			$table->integer('revendedores_id')->index('fk_telefones_revendedores1_idx');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('telefone_revendedores');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProdutoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('produtores', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('users_id')->index('fk_produtores_users1_idx');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('localizacoes_id')->index('fk_produtores_localizacao1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('produtores');
	}

}

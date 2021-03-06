<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToProdutoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('produtores', function(Blueprint $table)
		{
			$table->foreign('distritos_id', 'fk_produtores_distritos1')->references('id')->on('distritos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('users_id', 'fk_produtores_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('produtores', function(Blueprint $table)
		{
			$table->dropForeign('fk_produtores_distritos1');
			$table->dropForeign('fk_produtores_users1');
		});
	}

}

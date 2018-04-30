<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOfertasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ofertas', function(Blueprint $table)
		{
			$table->foreign('produtos_id', 'fk_ofertas_produtos1')->references('id')->on('produtos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('unidades_medidas_id', 'fk_ofertas_unidades_medidas1')->references('id')->on('unidades_medidas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('produtores_id', 'fk_produtores_has_produtos_unidades_mendidas_produtores2')->references('id')->on('produtores')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ofertas', function(Blueprint $table)
		{
			$table->dropForeign('fk_ofertas_produtos1');
			$table->dropForeign('fk_ofertas_unidades_medidas1');
			$table->dropForeign('fk_produtores_has_produtos_unidades_mendidas_produtores2');
		});
	}

}
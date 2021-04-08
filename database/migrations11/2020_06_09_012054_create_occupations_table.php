<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOccupationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('occupations', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('name', 65535)->comment('職種名');
			$table->integer('sortno')->comment('ソート順');
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
		Schema::drop('occupations');
	}

}

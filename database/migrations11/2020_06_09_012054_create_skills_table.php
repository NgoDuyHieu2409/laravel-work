<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSkillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('skills', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('name', 65535)->comment('スキル名');
			$table->bigInteger('occupation_id')->unsigned()->index('skills_occupation_id_foreign')->comment('職種ID');
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
		Schema::drop('skills');
	}

}

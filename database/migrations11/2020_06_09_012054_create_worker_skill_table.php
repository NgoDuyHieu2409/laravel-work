<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkerSkillTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('worker_skill', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('worker_skill_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('skill_id')->unsigned()->index('worker_skill_skill_id_foreign')->comment('スキルID');
			$table->bigInteger('home_id')->unsigned()->index('worker_skill_home_id_foreign')->comment('施設ID');
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
		Schema::drop('worker_skill');
	}

}

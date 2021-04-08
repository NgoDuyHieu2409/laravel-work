<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkQualificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_qualification', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('work_id')->unsigned()->index('work_qualification_work_id_foreign')->comment('仕事ID');
			$table->bigInteger('qualification_id')->unsigned()->index('work_qualification_qualification_id_foreign')->comment('資格ID');
			$table->string('required_yn', 1)->comment('必須フラグ');
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
		Schema::drop('work_qualification');
	}

}

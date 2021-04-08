<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('works', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('works_user_id_foreign');
			$table->text('title', 65535)->nullable();
			$table->text('content', 65535)->nullable();
			$table->text('address', 65535)->nullable();
			$table->text('access', 65535)->nullable();
			$table->string('contact_name')->nullable();
			$table->string('contact_tel')->nullable();
			$table->text('things_to_bring1', 65535)->nullable();
			$table->text('things_to_bring2', 65535)->nullable();
			$table->text('things_to_bring3', 65535)->nullable();
			$table->text('things_to_bring4', 65535)->nullable();
			$table->text('things_to_bring5', 65535)->nullable();
			$table->text('notes', 65535)->nullable();
			$table->text('condition1', 65535)->nullable();
			$table->text('condition2', 65535)->nullable();
			$table->text('condition3', 65535)->nullable();
			$table->text('condition4', 65535)->nullable();
			$table->text('condition5', 65535)->nullable();
			$table->integer('status')->nullable();
			$table->dateTime('worktime_start_at')->nullable();
			$table->dateTime('worktime_end_at')->nullable();
			$table->dateTime('resttime_start_at')->nullable();
			$table->dateTime('resttime_end_at')->nullable();
			$table->integer('resttime_minutes')->nullable();
			$table->integer('deadline_type')->nullable();
			$table->integer('recruitment_person_count')->nullable();
			$table->integer('hourly_wage')->nullable();
			$table->integer('ovetime_extra_percentages')->nullable();
			$table->integer('night_extra_percentages')->nullable();
			$table->integer('transportation_fee')->nullable();
			$table->dateTime('recruitment_start_at')->nullable();
			$table->text('working_conditions_pdf_url', 65535)->nullable();
			$table->softDeletes();
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
		Schema::drop('works');
	}

}

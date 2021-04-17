<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('email');
			$table->string('remember_token')->nullable();
			$table->string('name');
			$table->string('name_english');
			$table->string('contact_name');
			$table->string('contact_name_english');
			$table->string('tel');
			$table->string('zipcode');
			$table->integer('pref');
			$table->string('city');
			$table->string('address');
			$table->integer('mf_approve_status')->nullable();
			$table->text('website_url', 65535)->nullable();
			$table->string('representative_name');
			$table->string('corporate_number')->nullable();
			$table->text('description', 65535)->nullable();
			$table->dateTime('admin_approval_at')->nullable();
			$table->integer('credit_limit')->nullable();
			$table->text('logo')->nullable();
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
		Schema::drop('companies');
	}

}

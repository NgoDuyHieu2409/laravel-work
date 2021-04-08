<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->text('name', 65535)->comment('名前');
			$table->string('email')->comment('メールアドレス');
			$table->string('password')->comment('パスワード');
			$table->string('remember_token')->nullable()->comment('TOKEN');
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
		Schema::drop('admins');
	}

}

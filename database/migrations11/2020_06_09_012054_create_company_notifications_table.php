<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompanyNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_notifications', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('type')->comment('通知タイプ');
			$table->text('content', 65535)->nullable()->comment('内容');
			$table->text('url', 65535)->nullable()->comment('URL');
			$table->text('photo_url', 65535)->nullable()->comment('画像URL');
			$table->text('data', 65535)->nullable()->comment('オブジェクトデータ');
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
		Schema::drop('company_notifications');
	}

}

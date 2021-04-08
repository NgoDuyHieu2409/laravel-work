<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->integer('category')->comment('カテゴリID');
			$table->text('title', 65535)->comment('タイトル');
			$table->text('content', 65535)->comment('内容');
			$table->integer('nortificate_to')->comment('通知先');
			$table->string('notificate_yn', 1)->comment('通知有無');
			$table->dateTime('notificated_at')->nullable()->comment('通知日時');
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
		Schema::drop('news');
	}

}

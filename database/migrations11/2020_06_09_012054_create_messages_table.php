<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->comment('施設ID');
			$table->bigInteger('work_id')->unsigned()->comment('仕事ID');
			$table->text('comment', 65535)->nullable()->comment('コメント');
			$table->string('from_worker_yn', 1)->nullable()->comment('ワーカーからのコメントかどうか');
			$table->dateTime('read_at');
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
		Schema::drop('messages');
	}

}

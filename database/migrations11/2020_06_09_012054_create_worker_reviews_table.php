<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkerReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('worker_reviews', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('worker_reviews_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->index('worker_reviews_home_id_foreign')->comment('施設ID');
			$table->string('good_yn', 1)->nullable()->comment('グッド評価');
			$table->text('comment', 65535)->nullable()->comment('コメント');
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
		Schema::drop('worker_reviews');
	}

}

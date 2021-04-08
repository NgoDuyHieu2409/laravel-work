<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomeReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('home_reviews', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('home_reviews_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->index('home_reviews_home_id_foreign')->comment('施設ID');
			$table->text('comment', 65535)->nullable()->comment('コメント');
			$table->string('good_yn1', 1)->nullable()->comment('勤務時間は予定通りでしたか？');
			$table->string('good_yn2', 1)->nullable()->comment('掲載されていた業務内容通りでしたか？');
			$table->string('good_yn3', 1)->nullable()->comment('またここで働きたいですか？');
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
		Schema::drop('home_reviews');
	}

}

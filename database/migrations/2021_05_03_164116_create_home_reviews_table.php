<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index('home_reviews_worker_id_foreign');
			$table->bigInteger('user_id')->unsigned()->index('home_reviews_home_id_foreign');
            $table->bigInteger('work_id')->unsigned();
			$table->text('comment', 65535)->nullable();
			$table->string('good_yn1', 1)->nullable();
			$table->string('good_yn2', 1)->nullable();
			$table->string('good_yn3', 1)->nullable();
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
        Schema::dropIfExists('home_reviews');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index('worker_reviews_worker_id_foreign');
			$table->bigInteger('user_id')->unsigned()->index('worker_reviews_home_id_foreign');
            $table->integer('work_id')->nullable();
			$table->smallInteger('liked')->nullable();
			$table->text('comment')->nullable();
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
        Schema::dropIfExists('worker_reviews');
    }
}

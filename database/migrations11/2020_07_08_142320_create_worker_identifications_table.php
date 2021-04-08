<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkerIdentificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_identifications', function (Blueprint $table) {
	    $table->bigInteger('id', true)->unsigned();
	    $table->bigInteger('worker_id')->unsigned()->comment('ワーカーID');
	    $table->integer('kind')->unsigned()->comment('種別');
	    $table->string('front_photo_url')->nullable()->comment('ファイル表面のURL');
	    $table->string('back_photo_url')->nullable()->comment('ファイル表面のURL');
	    $table->dateTime('admin_approval_at')->nullable()->comment('管理者承認日時');
	    $table->text('memo')->nullable()->comment('メモ');
	    $table->integer('status')->nullable()->comment('承認ステータス');
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
        Schema::dropIfExists('worker_identifications');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_work_histories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('position')->comment('Chức vụ');
            $table->string('company')->comment('Tên côn ty');
            $table->date('from_date')->nullable()->comment('ngày bắt đầu công việc');
            $table->date('to_date')->nullable()->comment('ngày kết thúc công việc');
            $table->iteger('current_job')->default(0)->comment('có là công việc hiện tại: 0: không, 1: có');
            $table->text('descriptions')->nullable();
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
        Schema::dropIfExists('user_work_histories');
    }
}

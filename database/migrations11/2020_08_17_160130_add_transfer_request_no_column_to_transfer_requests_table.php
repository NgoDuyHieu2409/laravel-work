<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferRequestNoColumnToTransferRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transfer_requests', function (Blueprint $table) {
            //
            $table->string('transfer_request_no', 255)->after('id')->comment('振込申請NO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer_requests', function (Blueprint $table) {
            //
        });
    }
}

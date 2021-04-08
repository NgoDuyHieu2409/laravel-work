<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeadlineAtColumnToTransferRequestsTable extends Migration
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
            $table->datetime('deadline_at')->nullable()->after('manual_yn')->comment('振込期日');
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

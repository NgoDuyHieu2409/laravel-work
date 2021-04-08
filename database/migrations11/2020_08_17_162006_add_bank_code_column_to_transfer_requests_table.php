<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankCodeColumnToTransferRequestsTable extends Migration
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
            $table->string('branch_code', 3)->after('transfer_fee')->comment('支店コード');
            $table->string('bank_code', 4)->after('transfer_fee')->comment('銀行コード');
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

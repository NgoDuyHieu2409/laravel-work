<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFixedAtToWorkRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_records', function (Blueprint $table) {
            //
	    $table->string('fixed_yn', 1)->nullable()->after('commissoin_fee_tax_rate')->comment('確定済みフラグ');
	    $table->dateTime('fixed_at')->nullable()->after('fixed_yn')->comment('確定日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_records', function (Blueprint $table) {
            //
        });
    }
}

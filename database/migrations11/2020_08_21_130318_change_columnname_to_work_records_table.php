<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnnameToWorkRecordsTable extends Migration
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
	    $table->renameColumn('commissoin_fee', 'commission_fee');
	    $table->renameColumn('commissoin_fee_tax', 'commission_fee_tax');
	    $table->renameColumn('commissoin_fee_tax_rate', 'commission_fee_tax_rate');

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

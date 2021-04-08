<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBankCommissionFeeToWorkRecordsTable extends Migration
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
	    $table->integer('bank_commission_fee_tax')->nullable()->after('commission_fee_tax_rate')->comment('銀行振込代行手数料消費税');	    
	    $table->integer('bank_commission_fee')->nullable()->after('commission_fee_tax_rate')->comment('銀行振込代行手数料');

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

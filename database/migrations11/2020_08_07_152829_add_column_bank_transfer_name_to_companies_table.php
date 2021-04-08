<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBankTransferNameToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
	    $table->string('bank_company_code', 255)->nullable()->change();
	    $table->string('bank_account_no', 255)->nullable()->change();
	    $table->string('bank_account_name', 255)->nullable()->change();
	    $table->string('bank_transfer_name', 255)->nullable()->after('bank_account_name')->comment('依頼人名');
	    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}

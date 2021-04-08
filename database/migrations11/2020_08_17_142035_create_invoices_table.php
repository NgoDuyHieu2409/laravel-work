<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
	    $table->string('invoice_no', 255)->comment('請求NO');
	    $table->bigInteger('company_id')->unsigned()->comment('企業ID');
	    $table->integer('price')->comment('金額');
	    $table->datetime('deadline_at')->comment('お支払い期日');
	    $table->integer('status')->comment('ステータス');
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
        Schema::dropIfExists('invoices');
    }
}

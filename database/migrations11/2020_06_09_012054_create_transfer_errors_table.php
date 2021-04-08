<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransferErrorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transfer_errors', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('transfer_errors_worker_id_foreign')->comment('ワーカーID');
			$table->integer('requested_amount')->comment('振込金額');
			$table->integer('transfer_fee')->comment('振込手数料');
			$table->text('bank_name', 65535)->comment('銀行名');
			$table->text('bank_kind', 65535)->comment('銀行種別');
			$table->text('bank_shop_name', 65535)->comment('銀行支店名');
			$table->text('bank_account_name', 65535)->comment('銀行口座名');
			$table->text('bank_account_no', 65535)->comment('銀行口座番号');
			$table->integer('reason')->nullable()->comment('理由');
			$table->text('memo', 65535)->nullable()->comment('メモ');
			$table->integer('gmo_api_status')->nullable()->comment('GMO APIステータス');
			$table->dateTime('transfer_requested_at')->nullable()->comment('振込申請日時');
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
		Schema::drop('transfer_errors');
	}

}

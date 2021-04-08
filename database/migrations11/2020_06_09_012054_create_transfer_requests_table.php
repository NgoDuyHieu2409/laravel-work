<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransferRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transfer_requests', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('transfer_requests_worker_id_foreign')->comment('ワーカーID');
			$table->integer('amount')->comment('振込金額');
			$table->integer('transfer_fee')->comment('振込手数料');
			$table->text('bank_name', 65535)->comment('銀行名');
			$table->text('bank_kind', 65535)->comment('銀行種別');
			$table->text('bank_shop_name', 65535)->comment('銀行支店名');
			$table->text('bank_account_name', 65535)->comment('銀行口座名');
			$table->text('bank_account_no', 65535)->comment('銀行口座番号');
			$table->integer('gmo_api_status')->nullable()->comment('GMO APIステータス');
			$table->char('manual_yn', 1)->nullable()->comment('申請方法');
			$table->dateTime('transfered_at')->nullable()->comment('振込完了日時');
			$table->integer('status')->nullable()->comment('ステータス');
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
		Schema::drop('transfer_requests');
	}

}

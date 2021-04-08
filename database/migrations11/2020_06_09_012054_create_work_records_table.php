<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkRecordsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('work_records', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('invoice_id')->unsigned()->nullable()->comment('請求ID');
			$table->bigInteger('worker_id')->unsigned()->index('work_records_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('home_id')->unsigned()->index('work_records_home_id_foreign')->comment('施設ID');
			$table->bigInteger('work_id')->unsigned()->index('work_records_work_id_foreign')->comment('仕事ID');
			$table->text('title', 65535)->nullable()->comment('タイトル');
			$table->date('work_date')->nullable()->comment('勤務日');
			$table->dateTime('scheduled_worktime_start_at')->nullable()->comment('予定開始日時');
			$table->dateTime('scheduled_worktime_end_at')->nullable()->comment('予定終了日時');
			$table->dateTime('worktime_start_at')->nullable()->comment('勤務開始日時');
			$table->dateTime('worktime_end_at')->nullable()->comment('勤務終了日時');
			$table->dateTime('scheduled_resttime_start_at')->nullable()->comment('予定休憩開始日時');
			$table->dateTime('scheduled_resttime_end_at')->nullable()->comment('予定休憩終了日時');
			$table->dateTime('resttime_start_at')->nullable()->comment('予定休憩時間開始');
			$table->dateTime('resttime_end_at')->nullable()->comment('予定休憩時間終了');
			$table->integer('resttime_minutes')->nullable()->comment('休憩時間');
			$table->string('worker_family_name')->comment('ワーカー姓');
			$table->string('worker_last_name')->comment('ワーカー名');
			$table->string('worker_family_name_kana')->comment('ワーカーセイ');
			$table->string('worker_last_name_kana')->comment('ワーカーメイ');
			$table->integer('worker_sex')->comment('ワーカ-性別');
			$table->integer('base_wage')->comment('基本報酬');
			$table->integer('ovetime_percentages')->nullable()->comment('法定時間外割増手当のパーセンテージ');
			$table->integer('nighttime_percentages')->nullable()->comment('深夜割増手当のパーセンテージ');
			$table->integer('ovetime_wage')->nullable()->comment('法定時間外割増手当金額');
			$table->integer('nighttime_wage')->nullable()->comment('深夜割増手当金額');
			$table->integer('transportation_fee')->nullable()->comment('交通費');
			$table->integer('total_wage')->comment('ワーカーへの合計支給額');
			$table->integer('transfer_request_status')->comment('申請ステータス');
			$table->dateTime('transfer_requested_at')->nullable()->comment('振込申請日時');
			$table->dateTime('transfered_at')->nullable()->comment('報酬振込日時');
			$table->integer('commissoin_fee')->comment('手数料');
			$table->integer('commissoin_fee_tax')->comment('手数料税額');
			$table->integer('commissoin_fee_tax_rate')->comment('手数料税率');
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
		Schema::drop('work_records');
	}

}

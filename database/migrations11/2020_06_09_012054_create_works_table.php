<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('works', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned()->comment('ID');
			$table->bigInteger('home_id')->unsigned()->index('works_home_id_foreign')->comment('施設ID');
			$table->text('title', 65535)->nullable()->comment('タイトル');
			$table->text('content', 65535)->nullable()->comment('内容');
			$table->text('address', 65535)->comment('簡易住所');
			$table->text('access', 65535)->nullable()->comment('アクセス');
			$table->string('tel')->nullable()->comment('電話番号');
			$table->text('things_to_bring1', 65535)->nullable()->comment('持ち物1');
			$table->text('things_to_bring2', 65535)->nullable()->comment('持ち物2');
			$table->text('things_to_bring3', 65535)->nullable()->comment('持ち物3');
			$table->text('things_to_bring4', 65535)->nullable()->comment('持ち物4');
			$table->text('things_to_bring5', 65535)->nullable()->comment('持ち物5');
			$table->text('notes', 65535)->nullable()->comment('注意事項');
			$table->text('pdf1', 65535)->nullable()->comment('PDF1');
			$table->text('pdf2', 65535)->nullable()->comment('PDF2');
			$table->text('pdf3', 65535)->nullable()->comment('PDF3');
			$table->text('pdf4', 65535)->nullable()->comment('PDF4');
			$table->text('pdf5', 65535)->nullable()->comment('PDF5');
			$table->text('condition1', 65535)->nullable()->comment('条件1');
			$table->text('condition2', 65535)->nullable()->comment('条件2');
			$table->text('condition3', 65535)->nullable()->comment('条件3');
			$table->text('condition4', 65535)->nullable()->comment('条件4');
			$table->text('condition5', 65535)->nullable()->comment('条件5');
			$table->integer('status')->nullable()->comment('仕事ステータス');
			$table->dateTime('worktime_start_at')->nullable()->comment('業務開始日時');
			$table->dateTime('worktime_end_at')->nullable()->comment('業務修了日時');
			$table->dateTime('resttime_start_at')->nullable()->comment('休憩開始日時');
			$table->dateTime('resttime_end_at')->nullable()->comment('休憩修了日時');
			$table->integer('resttime_minutes')->nullable()->comment('休憩時間(分)');
			$table->integer('deadline_type')->nullable()->comment('募集締切時間タイプ');
			$table->dateTime('deadline_at')->nullable()->comment('募集締切日時');
			$table->integer('recruitment_person_count')->nullable()->comment('募集人数');
			$table->integer('hourly_wage')->nullable()->comment('時給');
			$table->integer('base_wage')->nullable()->comment('基本報酬');
			$table->integer('ovetime_extra_percentages')->nullable()->comment('法定時間外割増手当');
			$table->integer('night_extra_percentages')->nullable()->comment('深夜割増手当');
			$table->integer('transportation_fee')->nullable()->comment('交通費');
			$table->text('share_url', 65535)->nullable()->comment('ワーカー向け共有URL');
			$table->text('working_conditions_pdf_url', 65535)->nullable()->comment('労働条件通知書.pdf');
			$table->text('layoff_reason', 65535)->nullable()->comment('解雇理由');
			$table->integer('publish_type')->nullable()->comment('公開設定');
			$table->dateTime('recruitment_start_at')->nullable()->comment('募集開始日時');
			$table->dateTime('canceled_at')->nullable()->comment('キャンセル日時');
			$table->softDeletes();
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
		Schema::drop('works');
	}

}

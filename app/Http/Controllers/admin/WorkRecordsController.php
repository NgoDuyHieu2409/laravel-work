<?php

namespace App\Http\Controllers\Home;

use App\Exports\WorkRecordExport;
use App\Http\Controllers\Controller;
use App\Services\WorkRecordService;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class WorkRecordsController extends Controller
{
    protected $workRecordService;

    public function __construct(WorkRecordService $workRecordService)
    {
        $this->middleware('auth:home');
        $this->workRecordService = $workRecordService;
    }

    public function index()
    {
        $work_records = $this->workRecordService->getList();
        $total_piad_now = $this->workRecordService->getDataMonthNow();

        return view('home.work_records.index')->with(compact('work_records', 'total_piad_now'));
    }

    public function csv($month)
    {
        $record_by_month = $this->workRecordService->getByMonth($month);
        $file_name = 'WorkRecord-' . $month . '.csv';

        // ファイル生成
        $stream = fopen('php://output', 'w');
        fwrite($stream, pack('C*',0xEF,0xBB,0xBF)); // BOM をつける
        // ヘッダー
        fputcsv($stream, [
            '案件名',
            '案件日',
            'チェックイン時間',
            'チェックアウト時間',
            '休憩開始時間',
            '休憩終了時間',
            '姓',
            '名',
            '性別',
            '報酬額',
            '交通費',
            '支給合計',
            '振込申請日時',
            '報酬振込日',
            'サービス利用料',
            '消費税',
        ]);
            
        foreach ($record_by_month['record_by_month'] as $records){
            foreach ($records as $record){
                fputcsv($stream, [
                    $record->work->title,
                    Carbon::parse($record->work_date)->format('Y/m/d'),
                    Carbon::parse($record->worktime_start_at)->format('H:i'),
                    Carbon::parse($record->worktime_end_at)->format('H:i'),
                    Carbon::parse($record->resttime_start_at)->format('H:i'),
                    Carbon::parse($record->resttime_end_at)->format('H:i'),
                    $record->worker->first_name,
                    $record->worker->last_name,
                    $record->worker->sex ? '男性' : '女性',
                    number_format($record->base_wage),
                    number_format($record->transportation_fee),
                    number_format($record->total_wage),
                    Carbon::parse($record->transfer_requested_at)->format('Y/m/d'),
                    Carbon::parse($record->transfered_at)->format('Y/m/d'),
                    number_format($record->commissoin_fee),
                    number_format($record->commissoin_fee_tax),
                ]);
            }
        }
        return response(stream_get_contents($stream), 200)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', 'attachment; filename=' . $file_name);

        // return Excel::download(new WorkRecordExport($month, $record_by_month['record_by_month']), $file_name);
    }

    public function detail($month)
    {
        $work_records = $this->workRecordService->getByMonth($month);
        return view('home.work_records.detail')->with(compact('work_records', 'month'));
    }

}

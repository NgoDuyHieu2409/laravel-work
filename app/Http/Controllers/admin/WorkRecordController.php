<?php

namespace App\Http\Controllers\Admin;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Http\Request;
use App\Http\Services\WorkRecordService;
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\WorkRecordExport;

class WorkRecordController extends VoyagerBaseController
{
    protected $workRecordService;

    public function __construct(WorkRecordService $workRecordService)
    {
        $this->workRecordService = $workRecordService;
    }

    public function index(Request $request)
    {
        $slug = $this->getSlug($request);
        $user_id = Auth::id();

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $work_records = $this->workRecordService->getList();
        $total_piad_now = $this->workRecordService->getDataMonthNow();

        $view = 'voyager::bread.browse';
        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view)->with(compact('work_records', 'total_piad_now', 'dataType'));
    }

    public function csv($month)
    {
        $record_by_month = $this->workRecordService->getByMonth($month);
        $file_name = 'WorkRecord-' . $month . '.csv';

        // Create file
        $stream = fopen('php://output', 'w');
        fwrite($stream, pack('C*',0xEF,0xBB,0xBF));
        // header
        fputcsv($stream, [
            'Work name',
            'Date',
            'Worktime start',
            'Worktime end',
            'Resttime start',
            'Resttime end',
            'Worker name',
            'Gender',
            'Base wage',
            'Total wage',
            'Transportation-fee',
            'Transfer request',
            'Transfered',
            'Commission-fee',
            'Commission-fee-tax',
        ]);
            
        foreach ($record_by_month['record_by_month'] as $records){
            foreach ($records as $record){
                fputcsv($stream, [
                    $record->work->title,
                    $record->work_date,
                    $record->worktime_start_at,
                    $record->worktime_end_at,
                    $record->resttime_start_at,
                    $record->resttime_end_at,
                    $record->worker->name,
                    $record->worker->contact->sex ? 'Nam' : 'Nữ',
                    $record->base_wage,
                    $record->transportation_fee,
                    $record->total_wage,
                    $record->transfer_requested_at,
                    $record->transfered_at,
                    $record->commission_fee,
                    $record->commission_fee_tax,
                ]);
            }
        }
        return response(stream_get_contents($stream), 200)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', 'attachment; filename=' . $file_name);

        // return Excel::download(new WorkRecordExport($month, $record_by_month['record_by_month']), $file_name);
    }

    public function detail(Request $request, $month)
    {
        $dataType = Voyager::model('DataType')->where('slug', '=', 'worker-reviews')->first();
        $work_records = $this->workRecordService->getByMonth($month);
        return Voyager::view('voyager::work-records.detail')->with(compact('work_records', 'month', 'dataType'));
    }

}
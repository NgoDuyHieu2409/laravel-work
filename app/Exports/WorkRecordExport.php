<?php

namespace App\Exports;

use App\Models\WorkRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use TCG\Voyager\Facades\Voyager;

class WorkRecordExport implements FromView
{
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     return WorkRecord::all();
    // }

    public function __construct($month, $record_by_month){
        $this->month = $month;
        $this->record_by_month = $record_by_month;
    }

    public function view(): View
    {
        return Voyager::view('voyager::work-records.csv',[
            'record_by_month' => $this->record_by_month,
        ]);
    }
}

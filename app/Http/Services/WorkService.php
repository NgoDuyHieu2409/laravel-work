<?php

namespace App\Http\Services;

use App\Enums\WorkApplicationStatus;
use App\Enums\WorkConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Enums\WorkRequired;
use App\Enums\WorkStatus;
use App\Models\User as Worker;
use App\Models\WorkPhoto;
use App\Models\WorkQualification;
use App\Models\WorkRecord;
use App\Models\WorkSkill;
use App\Models\WorkTag;
use App\Models\WorkApplication;
use Carbon\Carbon;

class WorkService
{
    /**
     * Update status for work
     *
     * @param  $work
     * @return void
     */
    public function setStatusApplication($work)
    {
        $date = Carbon::now();

        $recruitment_start = Carbon::parse($work->recruitment_start_at);
        $recruitment_end = Carbon::parse($work->deadline_at);

        $worktime_start = Carbon::parse($work->worktime_start_at);
        $worktime_end = Carbon::parse($work->worktime_end_at);

        // 1:Trước khi tuyển dụng
        if (!$work->recruitment_start_at || $recruitment_start > $date) {
            $status = WorkStatus::RECRUITMENT_START;
        }

        // 2:Đang tuyển dụng
        if ($recruitment_start <= $date && $recruitment_end > $date) {
            $status = WorkStatus::WANTED;
        }

        // 3:Kết thúc tuyển dụng
        if ($recruitment_end <= $date) {
            $status = WorkStatus::RECRUITMENT_END;
        }

        // 4:Đã tuyển được người làm
        $applications = $work->work_applications()
            ->where('status', WorkApplicationStatus::ASSIGNED)
            ->count();
        if ($work->recruitment_person_count == $applications) {
            $status = WorkStatus::SELECTED;
        }

        // 5:Công việc đang diễn ra
        if ($worktime_start <= $date && $worktime_end >= $date) {
            $status = WorkStatus::WORKING;
        }

        // 6:Kết thúc công việc
        if ($worktime_end < $date) {
            $status = WorkStatus::BUSINESS;
        }

        // 7:Có yêu cầu từ người làm
        $modify_request_null = $work->modify_request()->whereNull('approved_at')->count();
        if ($modify_request_null > 0) {
            $status = WorkStatus::REQUESTING;
        }

        // 8:Đã thanh toán tiền lương
        $work_records = WorkRecord::where('work_id', $work->id)->count();
        if ($work_records > 0){
            $status = WorkStatus::FINISH;
        }

        return $status;
    }

    public function getTagIds($work_id)
    {
        $tag_ids = WorkTag::where('work_id', $work_id)->pluck('tag_id')->toArray();
        return $tag_ids;
    }

    public function getQualificationIds($work_id)
    {
        $qualification_ids = WorkQualification::where('work_id', $work_id)
            ->where('required_yn', 'y')
            ->pluck('qualification_id')->toArray();
        return $qualification_ids;
    }

    public function getSkillIds($work_id)
    {
        $skill_ids = WorkSkill::where('work_id', $work_id)->pluck('skill_id')->toArray();
        return $skill_ids;
    }

    public function createWorkTag($work_id, $data)
    {
        WorkTag::where('work_id', $work_id)->delete();

        if ($data) {
            foreach ($data as $tag_id) {
                $tag = new WorkTag();
                $tag->fill([
                    'work_id' => $work_id,
                    'tag_id'  => $tag_id,
                ])->save();
            }
        }
    }

    public function createWorkQualification($work_id, $data)
    {
        WorkQualification::where('work_id', $work_id)->delete();

        if ($data) {
            foreach ($data as $value) {
                $qualification = new WorkQualification();
                $qualification->fill([
                    'work_id'          => $work_id,
                    'qualification_id' => $value,
                    'required_yn'      => WorkRequired::REQUIRED_YES,
                ])->save();
            }
        }
    }

    public function createWorkPhoto($work_id, $data)
    {
        if ($data) {
            foreach ($data as $file) {
                $photo = new WorkPhoto();
                $file = json_decode($file, true);
                $photo->fill([
                    'work_id' => $work_id,
                    'url'     => $file['url'],
                    'title'   => $file['file_name'],
                ])->save();
            }
        }
    }

    public function createWorkSkill($work_id, $data)
    {
        WorkSkill::where('work_id', $work_id)->delete();
        if ($data) {
            foreach ($data as $value) {
                $skill = new WorkSkill();
                $skill->fill([
                    'work_id'  => $work_id,
                    'skill_id' => $value,
                ])->save();
            }
        }
    }

    public function getGroupRecordByWorkId($work_id, $group)
    {
        return WorkRecord::where('work_id', $work_id)
                        ->get()
                        ->groupBy($group);
    }

    public function totalAmountPaidByWork($work_id)
    {
        $work_records = $this->getGroupRecordByWorkId($work_id, 'worker_id');

        $totals = 0;
        foreach($work_records as $woker_id => $workers){
            $total = 0;
            foreach($workers as $worker){
                $total = $total + $worker->base_wage + $worker->transportation_fee + $worker->nighttime_wage + $worker->ovetime_wage;
            }
            $totals += $total;
        }

        return $totals;
    }


    public function totalWorkTime($work_id)
    {
        $work_records = $this->getGroupRecordByWorkId($work_id, 'worker_id');
        $total_time = 0;
        foreach($work_records as $woker_id => $workers){
            foreach($workers as $worker){
                $worktime_start = Carbon::create($worker->worktime_start_at);
                $worktime_end = Carbon::create($worker->worktime_end_at);
                $time = $worktime_end->diffInMinutes($worktime_start) - $worker->resttime_minutes;
                $total_time += $time;
            }
        }
        return $total_time;
    }

    public function totalCommissoinFee($work_id)
    {
        $work_records = $this->getGroupRecordByWorkId($work_id, 'worker_id');
        $total_fee = 0;
        foreach($work_records as $woker_id => $workers){
            foreach($workers as $worker){
                $total_fee += $worker->commission_fee;
            }
        }
        return $total_fee;
    }

    public function addRecruitmentEndTime($start_time, int $deadline)
    {
        $start_time = Carbon::parse($start_time);

        switch ($deadline) {
            case 1:
                $recruitment_end = $start_time->addHours(-5);
                break;
            case 2:
                $recruitment_end = $start_time->addHours(-8);
                break;
            case 3:
                $recruitment_end = $start_time->addHours(-12);
                break;
            case 4:
                $recruitment_end = $start_time->addHours(-24);
                break;
            case 5:
                $recruitment_end = $start_time->addHours(-48);
                break;
            case 6:
                $recruitment_end = $start_time->addHours(-72);
                break;
            default:
                break;
        }

        return $recruitment_end;
    }

    public function getWorkerUid($work_id)
    {
        $worker_ids = WorkApplication::where('work_id', $work_id)
            ->where('status', WorkApplicationStatus::ASSIGNED)
            ->where('confirm_yn', config('const.WorkApplications.CONFIRM_STATUS.YES'))
            ->pluck('worker_id')
            ->toArray();

        $uids = Worker::find($worker_ids)->pluck('id')->toArray();
        return $uids;
    }

    public function formatDateTime($work)
    {
        // $worktime_start = Carbon::parse($work->worktime_start_at)->isoFormat('LL(dddd) HH:mm');
        // $worktime_end = Carbon::parse($work->worktime_end_at)->format('H:i');

        // $recruitment_end = Carbon::parse($work->deadline_at)->isoFormat('LL(dddd) HH:mm');

        // $work->work_time_format = $worktime_start . '~' . $worktime_end;
        // $work->recruitment_end_format = $recruitment_end;

        $work->input_worktime_start    = Carbon::create($work->worktime_start_at)->format('Y-m-d\TH:i');
        $work->input_worktime_end      = Carbon::create($work->worktime_end_at)->format('Y-m-d\TH:i');
        $work->input_resttime_start    = $work->resttime_start_at ? Carbon::create($work->resttime_start_at)->format('Y-m-d\TH:i') : '';
        $work->input_resttime_end      = $work->resttime_end_at ? Carbon::create($work->resttime_end_at)->format('Y-m-d\TH:i') : '';
        $work->input_recruitment_start = Carbon::create($work->recruitment_start_at)->format('Y-m-d\TH:i');
    }

    public function countWorkApplication($work)
    {
        $applying = $work->work_applications()->where('status', WorkApplicationStatus::APPLYING)->count();
        $confirmed = $work->work_applications()->where('status', WorkApplicationStatus::ASSIGNED)->count();

        $work->count_applying = number_format($applying) . ' ' . WorkConfig::WORKER;
        $work->count_confirmed = number_format($confirmed) . ' ' .  WorkConfig::WORKER;
    }

    public function configBaseWage($data)
    {
        $time_start = Carbon::parse($data['time_start']);
        $base_wage_time = $time_start->floatDiffInHours(Carbon::parse($data['time_end']));

        $resttime_minutes = $data['resttime_minutes'] / 60;
        $base_wage = ($base_wage_time - $resttime_minutes) * $data['hourly_wage'];

        return $base_wage;
    }

    /**
     * Check if work is allowed to edit
     */
    public function checkEdit($work)
    {
        $count_application = $work->work_applications()
                                ->where('confirm_yn', 'y')
                                ->whereNotNull('confirmed_at')
                                ->count();

        if($count_application > 0){
            $work->is_edit = false;
        }
        else{
            $work->is_edit = true;
        }
    }


    // custom
    public function getDataWorkFormat($data)
    {
        $this->countWorkApplication($data);
        $data->recruitment_person_count = number_format($data->recruitment_person_count) . ' ' . WorkConfig::WORKER;
        $data->hourly_wage = number_format($data->hourly_wage) . ' VNĐ';
        $data->transportation_fee = number_format($data->transportation_fee) . ' VNĐ';
        $data->total_paid = $this->totalAmountPaidByWork($data->id);
        $data->total_work_time = $this->totalWorkTime($data->id);
        $data->total_fee = $this->totalCommissoinFee($data->id);

        return $data;
    }
}

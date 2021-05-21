<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;
use App\Models\UserContact;
use App\Models\UserWorkHistory;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\UserLanguage;
use App\Models\UserCertification;
use App\Http\Requests\UpdateCvRequest;
use Illuminate\Support\Facades\DB;

class MyProfileController extends Controller
{
    public function myProfile(Request $request)
    {
        $user = Auth::user();
        $city = City::pluck('name', 'id');

        return view('homes.users.index', compact([
            'user',
            'city'
        ]));
    }

    public function saveProfile(UpdateCvRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            // Save user contact
            if(!$contact = $user->contact){
                $contact = new UserContact;
            }
            $contact->fill([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'birthday' => $request->birthday,
                'job_title' => $request->job_title,
                'sex' => $request->sex ?? 0,
                'city' => $request->city,
                'district' => $request->district,
                'address' => $request->address,
                'summary' => $request->summary, 
            ])->save();

            // save workHistories
            if($request->workHistories){
                UserWorkHistory::where('user_id', $user->id)->delete();
                foreach ($request->workHistories as $value) {
                    $workHistory = new UserWorkHistory;
                    $workHistory->fill([
                        'user_id' => $user->id,
                        'position' => $value['position'],
                        'company' => $value['company'],
                        'from_date' => $value['from_date'],
                        'to_date' => $value['to_date'],
                        'current_job' => $value['current_job'] ?? 0,
                        'description' => $value['description'],
                    ])->save();
                }
            }

            // save Educations
            if($request->educations){
                UserEducation::where('user_id', $user->id)->delete();
                foreach ($request->educations as $value) {
                    $education = new UserEducation;
                    $education->fill([
                        'user_id' => $user->id,
                        'subject' => $value['subject'],
                        'school' => $value['school'],
                        'qualification' => $value['qualification'],
                        'from_date' => $value['from_date'],
                        'to_date' => $value['to_date'],
                        'description' => $value['description'],
                    ])->save();
                }
            }

            // save Skills
            if($request->skills){
                UserSkill::where('user_id', $user->id)->delete();
                foreach ($request->skills as $value) {
                    $skill = new UserSkill;
                    $skill->fill([
                        'user_id' => $user->id,
                        'name' => $value['name'],
                        'description' => $value['description'],
                    ])->save();
                }
            }

            // save languages
            if ($request->languages) {
                UserLanguage::where('user_id', $user->id)->delete();
                foreach ($request->languages as $value) {
                    $language = new UserLanguage;
                    $language->fill([
                        'user_id' => $user->id,
                        'language_id' => $value['language_id'],
                        'proficiency' => $value['proficiency'],
                    ])->save();
                }
            }

            // save Certifications
            if ($request->certifications) {
                UserCertification::where('user_id', $user->id)->delete();
                foreach ($request->certifications as $value) {
                    $certification = new UserCertification;
                    $certification->fill([
                        'user_id' => $user->id,
                        'name' => $value['name'],
                        'institution' => $value['institution'],
                        'date' => $value['date'],
                        'link' => $value['link'],
                        'file' => $value['certification_file'] ?? null,
                        'description' => $value['description'],
                    ])->save();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json($th, 400);
        }
    }
}
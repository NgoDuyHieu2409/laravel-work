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

    public function saveProfile(Request $request)
    {
        dd($request->all());

        // Save user contact
        $contact = new UserContact;
        $contact->fill([
            'user_id' => Auth::id(),
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'sex' => $request->sex ?? 0,
            'city' => $request->city,
            'district' => $request->district,
            'address' => $request->address,
            'summary' => $request->summary, 
        ])->save();

        // save workHistories
        foreach ($request->workHistories as $value) {
            $workHistory = new UserWorkHistory;
            $workHistory->fill([
                'user_id' => Auth::id(),
                'position' => $value->position,
                'company' => $value->company,
                'from_date' => $value->from_date,
                'to_date' => $value->to_date,
                'current_job' => $value->current_job ?? 0,
                'description' => $value->description,
            ])->save();
        }

        // save Educations
        foreach ($request->educations as $value) {
            $education = new UserEducation;
            $education->fill([
                'user_id' => Auth::id(),
                'subject' => $value->subject,
                'school' => $value->school,
                'qualification' => $value->qualification,
                'from_date' => $value->from_date,
                'to_date' => $value->to_date,
                'description' => $value->description,
            ])->save();
        }

        // save Skills
        foreach ($request->skills as $value) {
            $skill = new UserSkill;
            $skill->fill([
                'user_id' => Auth::id(),
                'name' => $value->name,
                'description' => $value->description,
            ])->save();
        }

        // save languages
        foreach ($request->languages as $value) {
            $language = new UserLanguage;
            $language->fill([
                'user_id' => Auth::id(),
                'language_id' => $value->language_id,
                'proficiency' => $value->proficiency,
            ])->save();
        }

        // save Certifications
        foreach ($request->languages as $value) {
            $certification = new UserCertification;
            $certification->fill([
                'user_id' => Auth::id(),
                'name' => $value->name,
                'institution' => $value->institution,
                'date' => $value->date,
                'link' => $value->link,
                'file' => $value->certification_file,
                'descriptions' => $value->descriptions,
            ])->save();
        }
    }
}

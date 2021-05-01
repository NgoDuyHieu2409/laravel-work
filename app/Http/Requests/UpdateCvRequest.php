<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|numberic',
            'birthday' => 'required|date',
            'job_ttle' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            
            'workHistories' => 'array',
            'workHistories.*' => 'required_if:isWorkHistory,1',
            'workHistories.*.position' => 'required_if:isWorkHistory,1',
            'workHistories.*.company' => 'required_if:isWorkHistory,1',

            'educations' => 'array',
            'educations.*' => 'required_if:isEducation,1',
            'educations.*.subject' => 'required_if:isEducation,1',
            'educations.*.school' => 'required_if:isEducation,1',

            'skills' => 'array',
            'skills.*' => 'required_if:isSkill,1',
            'skills.*.name' => 'required_if:isSkill,1',

            'languages' => 'array',
            'languages.*' => 'required_if:isLanguage,1',
            'languages.*.language_id' => 'required_if:isLanguage,1',
            'languages.*.proficiency' => 'required_if:isLanguage,1',

            'certifications' => 'array',
            'certifications.*' => 'required_if:isCertification,1',
            'certifications.*.name' => 'required_if:isCertification,1',
            'certifications.*institution' => 'required_if:isCertification,1',
        ];
    }
}

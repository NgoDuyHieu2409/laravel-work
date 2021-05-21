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
            'phone' => 'required',
            'birthday' => 'required|date',
            'job_title' => 'required',
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
            'certifications.*.institution' => 'required_if:isCertification,1',
        ];
    }

    public function attributes()
    {
        return [
            'workHistories.*.position' => 'position',
            'workHistories.*.company' => 'company',
            'educations.*.subject' => 'subject',
            'educations.*.school' => 'school',
            'skills.*.name' => 'name',
            'languages.*.language_id' => 'language',
            'languages.*.proficiency' => 'proficiency',
            'certifications.*.name' => 'name',
            'certifications.*.institution' => 'institution',
        ];
    }

    public function messages()
    {
        return [
            'workHistories.*.position.required_if' => 'The :attributes field is required.',
            'workHistories.*.company.required_if' => 'The :attributes field is required.',
            'educations.*.subject.required_if' => 'The :attributes field is required.',
            'educations.*.school.required_if' => 'The :attributes field is required.',
            'skills.*.name.required_if' => 'The :attributes field is required.',
            'languages.*.language_id.required_if' => 'The :attributes field is required.',
            'languages.*.proficiency.required_if' => 'The :attributes field is required.',
            'certifications.*.name.required_if' => 'The :attributes field is required.',
            'certifications.*.institution.required_if' => 'The :attributes field is required.',
        ];
    }
}

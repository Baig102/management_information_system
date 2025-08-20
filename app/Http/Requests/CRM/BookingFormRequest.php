<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class BookingFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [

            'company_id' => [
                'required'
            ],
            /* 'first_name' => [
                'required'
            ], */
            'total_sales_cost' => [
                'required'
            ],
            'total_net_cost' => [
                'required'
            ],
            'currency' => [
                'required'
            ],
            /* 'date_of_birth' => [
                'required',
                'date'
            ], */
            /* 'picture' => [
                'nullable',
                'mimes:jpg,jpeg,png'
            ],
            'date_of_birth' => [
                'required'
            ],
            'cnic' => [
                'required'
            ],
            'religion' => [
                'required'
            ],
            'blood_group' => [
                'required'
            ], */

        ];

        return $rules;
    }
}

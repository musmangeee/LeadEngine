<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class EditLeadRequest extends FormRequest
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
            'email' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'ssn' => 'required',
            'address_months' => 'required',
            'address_years' => 'required',
            'dl_state' => 'required',
            'dl_number' => 'required',
            'rent_or_own' => 'required',
            'is_military' => 'required',
            
            'job_title' => 'required',
            'emp_name' => 'required',
            'emp_phone' => 'required',
            'pay_frequency' => 'required',
            'income_type' => 'required',
            'net_monthly_income' => 'required',
            'next_pay_date' => 'required',
            'emp_years' => 'required',
            'emp_months' => 'required',

            'bank_name' => 'required',
            'pay_type' => 'required',
            'account_number' => 'required',
            'routing_number' => 'required'
        ];
    }
}

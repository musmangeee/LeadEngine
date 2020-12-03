<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLeadRequest extends FormRequest
{
    private $validate_income_type;
    private $validate_pay_frequency;

    public function __construct()
    {
        $this->validate_income_type = ['Job Income', 'Self Employed', 'Benefits', 'Pension', 'Social Security', 'Disability', 'Income', 'Military', 'Other'];

        $this->validate_pay_frequency = ['Weekly', 'Every 2 Weeks', 'Twice A Month', 'Monthly'];
    }
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
    * Get data to be validated from the request.
    *
    * @return array
    */
    public function validationData()
    {
        $xml = simplexml_load_string(parent::getContent());
        $json = json_encode($xml);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'REFERRAL.AFFID' => 'numeric',
            'CUSTOMER.PERSONAL.REQUESTEDAMOUNT' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'CUSTOMER.PERSONAL.EMAIL' => 'required|email',
            'CUSTOMER.PERSONAL.FIRSTNAME' => 'required',
            'CUSTOMER.PERSONAL.LASTNAME' => 'required',
            'CUSTOMER.PERSONAL.PHONE' => 'required',
            'CUSTOMER.PERSONAL.ADDRESS' => 'required',
            'CUSTOMER.PERSONAL.CITY' => 'required',
            'CUSTOMER.PERSONAL.STATE' => 'required',
            'CUSTOMER.PERSONAL.ZIP' => 'required',
            'CUSTOMER.PERSONAL.RENTOROWN' => 'required|in:Own,Rent',
            'CUSTOMER.PERSONAL.ADDRESSMONTHS' => 'required|integer',
            'CUSTOMER.PERSONAL.ADDRESSYEARS' => 'required|integer',
            'CUSTOMER.PERSONAL.DLSTATE' => 'required|string|min:2|max:2',
            'CUSTOMER.PERSONAL.DLNUMBER' => 'required|alpha_num',
            'CUSTOMER.PERSONAL.SSN' => 'required|digits:9',
            'CUSTOMER.PERSONAL.ISMILITARY' => 'required|in:Yes,No',
            'CUSTOMER.PERSONAL.TCPAOPTIN' => 'in:Yes,No',
            'CUSTOMER.EMPLOYMENT.INCOMETYPE' => ['required', Rule::in($this->validate_income_type)],
            'CUSTOMER.EMPLOYMENT.PAYFREQUENCY' => ['required', Rule::in($this->validate_pay_frequency)],
            'CUSTOMER.EMPLOYMENT.NEXTPAYDATE' => 'required|date_format:m-d-Y',
            'CUSTOMER.EMPLOYMENT.EMPNAME' => 'required',
            'CUSTOMER.EMPLOYMENT.EMPPHONE' => 'required',
            'CUSTOMER.EMPLOYMENT.JOBTITLE' => 'required',
            'CUSTOMER.EMPLOYMENT.EMPMONTHS' => 'required',
            'CUSTOMER.EMPLOYMENT.EMPYEARS' => 'required',
            'CUSTOMER.EMPLOYMENT.PAYTYPE' => 'required',
            'CUSTOMER.BANK.NETMONTHLYINCOME' => 'required|numeric',
            'CUSTOMER.BANK.BANKNAME' => 'required',
            'CUSTOMER.BANK.ACCOUNTNUMBER' => 'required|numeric',
            'CUSTOMER.BANK.ROUTINGNUMBER' => 'required|digits:9',
        ];
    }

    public function messages()
    {
        return [
            'REFERRAL.AFFID.numeric' => 'REFERRAL.AFFID must be a numeric',
            'CUSTOMER.PERSONAL.REQUESTEDAMOUNT.required' => 'CUSTOMER.PERSONAL.REQUESTEDAMOUNT is required',
            'CUSTOMER.PERSONAL.REQUESTEDAMOUNT.regex' => 'CUSTOMER.PERSONAL.REQUESTEDAMOUNT must be float',
            'CUSTOMER.PERSONAL.EMAIL.required' => 'CUSTOMER.PERSONAL.EMAIL is required',
            'CUSTOMER.PERSONAL.EMAIL.email' => 'CUSTOMER.PERSONAL.EMAIL is not a valid email',
            'CUSTOMER.PERSONAL.FIRSTNAME.required' => 'CUSTOMER.PERSONAL.FIRSTNAME is required',
            'CUSTOMER.PERSONAL.LASTNAME.required' => 'CUSTOMER.PERSONAL.LASTNAME is required',
            'CUSTOMER.PERSONAL.PHONE.required' => 'CUSTOMER.PERSONAL.PHONE is required',
            'CUSTOMER.PERSONAL.ADDRESS.required' => 'CUSTOMER.PERSONAL.ADDRESS is required',
            'CUSTOMER.PERSONAL.CITY.required' => 'CUSTOMER.PERSONAL.CITY is required',
            'CUSTOMER.PERSONAL.STATE.required' => 'CUSTOMER.PERSONAL.STATE is required',
            'CUSTOMER.PERSONAL.ZIP.required' => 'CUSTOMER.PERSONAL.ZIP is required',
            'CUSTOMER.PERSONAL.RENTOROWN.required' => 'CUSTOMER.PERSONAL.RENTOROWN is required',
            'CUSTOMER.PERSONAL.RENTOROWN.in' => 'CUSTOMER.PERSONAL.RENTOROWN must be one of the following type: Rent, Own',
            'CUSTOMER.PERSONAL.ADDRESSMONTHS.required' => 'CUSTOMER.PERSONAL.ADDRESSMONTHS is required',
            'CUSTOMER.PERSONAL.ADDRESSMONTHS.integer' => 'CUSTOMER.PERSONAL.ADDRESSMONTHS must be integer',
            'CUSTOMER.PERSONAL.ADDRESSYEARS.required' => 'CUSTOMER.PERSONAL.ADDRESSYEARS is required',
            'CUSTOMER.PERSONAL.ADDRESSYEARS.integer' => 'CUSTOMER.PERSONAL.ADDRESSYEARS must be integer',
            'CUSTOMER.PERSONAL.DLSTATE.required' => 'CUSTOMER.PERSONAL.DLSTATE is required',
            'CUSTOMER.PERSONAL.DLSTATE.min' => 'CUSTOMER.PERSONAL.DLSTATE must be exactly 2 character',
            'CUSTOMER.PERSONAL.DLSTATE.max' => 'CUSTOMER.PERSONAL.DLSTATE must be exactly 2 character',
            'CUSTOMER.PERSONAL.DLNUMBER.required' => 'CUSTOMER.PERSONAL.DLNUMBER is required',
            'CUSTOMER.PERSONAL.DLNUMBER.alpha_num' => 'CUSTOMER.PERSONAL.DLNUMBER must be alphe numeric',
            'CUSTOMER.PERSONAL.SSN.required' => 'CUSTOMER.PERSONAL.SSN is required',
            'CUSTOMER.PERSONAL.SSN.digits' => 'CUSTOMER.PERSONAL.SSN must be exactly 9 digits',
            'CUSTOMER.PERSONAL.ISMILITARY.required' => 'CUSTOMER.PERSONAL.ISMILITARY is required',
            'CUSTOMER.PERSONAL.ISMILITARY.in' => 'CUSTOMER.PERSONAL.ISMILITARY must be one of the following type: Yes, No',
            'CUSTOMER.PERSONAL.TCPAOPTIN.in' => 'CUSTOMER.PERSONAL.TCPAOPTIN must be one of the following type: Yes, No',
            'CUSTOMER.EMPLOYMENT.INCOMETYPE.required' => 'CUSTOMER.EMPLOYMENT.INCOMETYPE is required',
            'CUSTOMER.EMPLOYMENT.INCOMETYPE.in' => 'CUSTOMER.EMPLOYMENT.INCOMETYPE must be one of the following type: ' . implode(', ', $this->validate_income_type),
            'CUSTOMER.EMPLOYMENT.PAYFREQUENCY.required' => 'CUSTOMER.EMPLOYMENT.PAYFREQUENCY is required',
            'CUSTOMER.EMPLOYMENT.PAYFREQUENCY.in' => 'CUSTOMER.EMPLOYMENT.PAYFREQUENCY must be one of the following type: ' . implode(', ', $this->validate_pay_frequency),
            'CUSTOMER.EMPLOYMENT.NEXTPAYDATE.required' => 'CUSTOMER.EMPLOYMENT.NEXTPAYDATE is required',
            'CUSTOMER.EMPLOYMENT.NEXTPAYDATE.date_format' => 'CUSTOMER.EMPLOYMENT.NEXTPAYDATE must be in the format: MM-DD-YYYY',
            'CUSTOMER.EMPLOYMENT.EMPNAME.required' => 'CUSTOMER.EMPLOYMENT.EMPNAME is required',
            'CUSTOMER.EMPLOYMENT.EMPPHONE.required' => 'CUSTOMER.EMPLOYMENT.EMPPHONE is required',
            'CUSTOMER.EMPLOYMENT.JOBTITLE.required' => 'CUSTOMER.EMPLOYMENT.JOBTITLE is required',
            'CUSTOMER.EMPLOYMENT.EMPMONTHS.required' => 'CUSTOMER.EMPLOYMENT.EMPMONTHS is required',
            'CUSTOMER.EMPLOYMENT.EMPYEARS.required' => 'CUSTOMER.EMPLOYMENT.EMPYEARS is required',
            'CUSTOMER.EMPLOYMENT.PAYTYPE.required' => 'CUSTOMER.EMPLOYMENT.PAYTYPE is required',
            'CUSTOMER.BANK.NETMONTHLYINCOME.required' => 'CUSTOMER.BANK.NETMONTHLYINCOME is required',
            'CUSTOMER.BANK.NETMONTHLYINCOME.numeric' => 'CUSTOMER.BANK.NETMONTHLYINCOME must be numeric',
            'CUSTOMER.BANK.BANKNAME.required' => 'CUSTOMER.BANK.BANKNAME is required',
            'CUSTOMER.BANK.ACCOUNTNUMBER.required' => 'CUSTOMER.BANK.ACCOUNTNUMBER is required',
            'CUSTOMER.BANK.ACCOUNTNUMBER.numeric' => 'CUSTOMER.BANK.ACCOUNTNUMBER must be numeric',
            'CUSTOMER.BANK.ROUTINGNUMBER.required' => 'CUSTOMER.BANK.ROUTINGNUMBER is required',
            'CUSTOMER.BANK.ROUTINGNUMBER.digits' => 'CUSTOMER.BANK.ROUTINGNUMBER must be exactly 9 digits',
        ];
    }
}

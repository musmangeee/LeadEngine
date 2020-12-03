<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePandaChannelRequest extends FormRequest
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
            'provider_id' => 'required',
            'channels' => 'required',
            'tier' => 'required',
            'portfolio' => 'required',
            'api_key' => 'required',
            'price' => 'required',
            'deliverySchedules' => 'nullable',
            'deliverySchedules.*.day_of_week' => 'required',
            'deliverySchedules.*.start_at' => 'required',
            'deliverySchedules.*.end_at' => 'required',
            'deliverySchedules.*.daily_cap' => 'required',
            'deliverySchedules.*.hourly_cap' => 'required'
        ];

    }
}

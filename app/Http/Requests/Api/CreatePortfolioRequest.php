<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreatePortfolioRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'status' => 'required',
            'route_type' => 'required',
            'routings' => 'nullable|array',
            'routings.*.traffic_percentage' => 'required',
            'routings.*.routeable_type' => 'required',
            'routings.*.routeable_id' => 'required',
        ];
    }
}

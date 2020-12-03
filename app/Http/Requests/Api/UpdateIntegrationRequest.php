<?php

namespace App\Http\Requests\Api;

use App\Models\Integration;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIntegrationRequest extends FormRequest
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
            'is_enable' => 'required|boolean',
            'channel' => 'required|in:vertical,panda,plat|unique:integrations,channel,' . $this->id,
            'name' => 'required|unique:integrations,name,' . $this->id,
            'post_url' => 'required|url',
            'post_method' => 'required|in:post,get',
            'post_content_type' => 'required|in:json,xml,form-data',
            'post_body' => 'required_if:post_content_type,xml,json',
            'post_form_datas' => 'required_if:post_content_type,==,form-data',
            'wait_timeout' => 'required|numeric',
            'response_type' => 'required|in:json,xml',
            'status_response' => '',
            'message_response' => '',
            'price_response' => '',
            'redirect_response' => ''
        ];
    }

    public function messages()
    {
        return [
            'channel.unique' => 'Channel already have integration created.',
        ];
    }
}

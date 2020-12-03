<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateIntegrationRequest extends FormRequest
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
            'is_enable' => 'required',
            'channel' => 'required|in:vertical,panda,plat|unique:integrations',
            'name' => 'required|unique:integrations',
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

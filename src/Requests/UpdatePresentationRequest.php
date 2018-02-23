<?php

namespace Webcore\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Webcore\Presentation\Models\Presentation;

class UpdatePresentationRequest extends FormRequest
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
        return Presentation::$rules;
    }
}

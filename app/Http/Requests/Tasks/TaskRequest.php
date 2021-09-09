<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:40',
            ],
            'bucket' => [
                'required',
                'string',
                'max:20',
            ],
            'area' => [
                'required',
                'string',
                'max:20',
            ],
            'description' => [
                'string',
                'nullable'

            ],
            'scheduled_for' => ['required', 'date'],
            'completed' => ['boolean', 'nullable'],
        ];
    }
}

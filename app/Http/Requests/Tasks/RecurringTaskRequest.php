<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class RecurringTaskRequest extends FormRequest
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
            'frequency' => 'required|string|max:5',
            'interval' => 'integer|required',
            'occurences' => 'integer|nullable',
            'end_date' => 'date|nullable'
        ];
    }
}

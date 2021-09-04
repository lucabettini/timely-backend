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
            'frequency' => [
                'required',
                'string',
                'max:5',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['day', 'week', 'month', 'year'])) {
                        $fail("The '$attribute' is invalid.");
                    }
                }
            ],
            'interval' => 'integer|nullable',
            'occurrences_left' => 'integer|nullable',
            'end_date' => 'date|nullable'
        ];
    }
}

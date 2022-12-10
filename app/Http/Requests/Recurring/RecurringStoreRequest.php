<?php

namespace App\Http\Requests\Recurring;

use Illuminate\Foundation\Http\FormRequest;

class RecurringStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => ['required', 'string'],
            'amount' => ['required', 'not_in:0', 'integer'],
            'type' => ['required', 'string', 'in:spending,earning'],
            'interval' => ['required', 'string', 'in:daily,weekly,biweekly,monthly,yearly'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'space_id' => ['required', 'exists:spaces,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['required_with:tags', 'exists:tags,id'],
        ];
    }
}

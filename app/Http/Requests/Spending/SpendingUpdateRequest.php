<?php

namespace App\Http\Requests\Spending;

use Illuminate\Foundation\Http\FormRequest;

class SpendingUpdateRequest extends FormRequest
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
            'description' => ['sometimes', 'string'],
            'amount' => ['sometimes', 'not_in:0', 'integer'],
            'when' => ['sometimes', 'date'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'space_id' => ['sometimes', 'exists:spaces,id'],
        ];
    }
}

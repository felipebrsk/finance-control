<?php

namespace App\Http\Requests\Spending;

use Illuminate\Foundation\Http\FormRequest;

class DetachSpendingTagsRequest extends FormRequest
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
            'tags' => ['required', 'array'],
            'tags.*' => ['required_with:tags', 'exists:tags,id'],
        ];
    }
}

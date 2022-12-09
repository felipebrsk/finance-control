<?php

namespace App\Http\Requests\Space;

use Illuminate\Foundation\Http\FormRequest;

class SpaceUpdateRequest extends FormRequest
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
            'name' => ['sometimes', 'string'],
            'currency_id' => ['sometimes', 'exists:currencies,id'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['required_with:tags', 'exists:tags,id'],
        ];
    }
}

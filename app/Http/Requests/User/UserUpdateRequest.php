<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'username' => ['sometimes', 'string', 'unique:users,username'],
            'avatar' => ['nullable', 'mimes:jpeg,jpg,png,gif'],
            'weekly_report' => ['nullable', 'boolean'],
            'first_day_of_week' => ['nullable', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'weekly_report' => filter_var($this->weekly_report, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
        ]);
    }
}

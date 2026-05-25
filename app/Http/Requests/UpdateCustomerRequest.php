<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 💡 false から true に変更
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'kana'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'gender' => 'nullable|string|max:10',
        ];
    }
}

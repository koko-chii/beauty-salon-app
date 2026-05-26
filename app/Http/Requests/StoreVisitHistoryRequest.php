<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVisitHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id', // 存在する顧客IDかチェック
            'visited_at'  => 'required|date',               // 来店日は必須、日付形式
            'menu'        => 'required|string|max:255',      // メニューは必須
            'memo'        => 'nullable|string',              // メモは空っぽOK
            'image_1'     => 'nullable|image|max:2048',
            'image_2'     => 'nullable|image|max:2048',
            'image_3'     => 'nullable|image|max:2048',
        ];
    }
}

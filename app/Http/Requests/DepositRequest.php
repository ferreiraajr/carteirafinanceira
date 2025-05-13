<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
        ];
    }
    public function messages(): array
    {
        return [
            'amount.required' => 'O valor do depósito é obrigatório.',
            'amount.numeric' => 'O valor do depósito deve ser um número.',
            'amount.min' => 'O valor do depósito deve ser maior que zero.',
        ];
    }
}

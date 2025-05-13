<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
            'recipient' => 'required|exists:users,id|different:' . Auth::id(),
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'O valor da transferência é obrigatório.',
            'amount.numeric' => 'O valor da transferência deve ser um número.',
            'amount.min' => 'O valor da transferência deve ser maior que zero.',
            'recipient.required' => 'O destinatário é obrigatório.',
            'recipient.exists' => 'O destinatário selecionado não existe.',
            'recipient.different' => 'Você não pode transferir para si mesmo.',
        ];
    }
}

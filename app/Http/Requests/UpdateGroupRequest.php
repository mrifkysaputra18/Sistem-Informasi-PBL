<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', 'unique:groups,kode,' . $this->route('group')->id],
            'nama' => ['required', 'string', 'max:100'],
            'academic_term_id' => ['required', 'exists:academic_terms,id'],
            'judul_proyek' => ['nullable', 'string', 'max:200'],
        ];
    }
}

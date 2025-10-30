<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Update with proper authorization later
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $projectId = $this->route('project') ? $this->route('project')->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'dosen_id' => ['nullable', 'exists:pengguna,id'],
            'program_studi' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'max_members' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul mata kuliah',
            'description' => 'deskripsi',
            'dosen_id' => 'dosen pengampu',
            'program_studi' => 'program studi',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
            'max_members' => 'maksimal anggota',
        ];
    }
}



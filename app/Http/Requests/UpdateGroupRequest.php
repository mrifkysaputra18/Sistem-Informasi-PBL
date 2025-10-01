<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'class_room_id' => ['required', 'exists:class_rooms,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'max_members' => ['integer', 'min:1', 'max:10'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelompok wajib diisi',
            'class_room_id.required' => 'Kelas wajib dipilih',
            'class_room_id.exists' => 'Kelas tidak valid',
        ];
    }
}
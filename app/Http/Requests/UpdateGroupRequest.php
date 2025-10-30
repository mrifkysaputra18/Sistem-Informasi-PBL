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
            'class_room_id' => ['required', 'exists:ruang_kelas,id'],
            'project_id' => ['nullable', 'exists:proyek,id'],
            'max_members' => ['integer', 'min:1', 'max:10'],
            'members' => ['array'],
            'members.*' => ['exists:pengguna,id'],
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate members count doesn't exceed max_members
            if ($this->has('members') && $this->has('max_members')) {
                $membersCount = count($this->members);
                $maxMembers = (int) $this->max_members;
                
                if ($membersCount > $maxMembers) {
                    $validator->errors()->add(
                        'members',
                        "Jumlah anggota ($membersCount) melebihi batas maksimal ($maxMembers). Silakan kurangi anggota atau tingkatkan batas maksimal."
                    );
                }
            }
            
            // Validate leader is part of members
            if ($this->has('leader_id') && $this->has('members')) {
                if (!in_array($this->leader_id, $this->members)) {
                    $validator->errors()->add('leader_id', 'Ketua harus menjadi salah satu anggota kelompok.');
                }
            }
        });
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


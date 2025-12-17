<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeeklyTargetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $target = $this->route('target');
        
        // Admin selalu bisa
        if ($user && $user->isAdmin()) {
            return true;
        }
        
        // Creator atau dosen pengampu bisa update
        if ($user && $target) {
            if ($target->created_by === $user->id) {
                return true;
            }
            
            if ($user->isDosen() && $target->group->classRoom->dosen_id === $user->id) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'week_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'deadline' => ['required', 'date'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'week_number' => 'minggu ke',
            'title' => 'judul target',
            'description' => 'deskripsi',
            'deadline' => 'deadline',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'week_number.required' => 'Minggu harus dipilih.',
            'week_number.integer' => 'Minggu harus berupa angka.',
            'week_number.min' => 'Minggu minimal 1.',
            'title.required' => 'Judul target harus diisi.',
            'description.required' => 'Deskripsi target harus diisi.',
            'deadline.required' => 'Deadline harus diisi.',
            'deadline.date' => 'Deadline harus berupa tanggal valid.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Kelompok;
use App\Models\RuangKelas;

class StoreWeeklyTargetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        
        // Hanya admin dan dosen yang bisa membuat target
        return $user && ($user->isAdmin() || $user->isDosen());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'target_type' => ['required', 'in:single,multiple,all_class'],
            'class_room_id' => ['required_if:target_type,all_class', 'nullable', 'exists:ruang_kelas,id'],
            'group_id' => ['required_if:target_type,single', 'nullable', 'exists:kelompok,id'],
            'group_ids' => ['required_if:target_type,multiple', 'nullable', 'array'],
            'group_ids.*' => ['exists:kelompok,id'],
            'week_number' => ['required', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'deadline' => ['required', 'date', 'after:today'],
            'todo_items' => ['required', 'array', 'min:1'],
            'todo_items.*.title' => ['required', 'string', 'max:255'],
            'todo_items.*.description' => ['nullable', 'string'],
            'todo_items.*.order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'target_type' => 'tipe target',
            'class_room_id' => 'kelas',
            'group_id' => 'kelompok',
            'group_ids' => 'kelompok',
            'week_number' => 'minggu ke',
            'title' => 'judul target',
            'description' => 'deskripsi',
            'deadline' => 'deadline',
            'todo_items' => 'todo items',
            'todo_items.*.title' => 'judul todo item',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'target_type.required' => 'Tipe target harus dipilih.',
            'class_room_id.required_if' => 'Kelas harus dipilih untuk target semua kelas.',
            'class_room_id.exists' => 'Kelas yang dipilih tidak valid.',
            'group_id.required_if' => 'Kelompok harus dipilih untuk target single.',
            'group_id.exists' => 'Kelompok yang dipilih tidak valid.',
            'group_ids.required_if' => 'Minimal satu kelompok harus dipilih.',
            'week_number.required' => 'Minggu harus dipilih.',
            'title.required' => 'Judul target harus diisi.',
            'description.required' => 'Deskripsi target harus diisi.',
            'deadline.required' => 'Deadline harus diisi.',
            'deadline.after' => 'Deadline harus setelah hari ini.',
            'todo_items.required' => 'Minimal 1 todo item harus diisi.',
            'todo_items.min' => 'Minimal 1 todo item harus diisi.',
            'todo_items.*.title.required' => 'Judul todo item harus diisi.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Filter empty todo items
        if ($this->has('todo_items')) {
            $todoItems = array_filter($this->todo_items ?? [], function ($item) {
                return !empty($item['title']);
            });
            $this->merge(['todo_items' => array_values($todoItems)]);
        }
    }

    /**
     * Configure the validator instance.
     * Tidak ada pembatasan akses kelas untuk dosen
     */
    public function withValidator($validator): void
    {
        // Tidak ada validasi akses kelas khusus - dosen bisa akses semua kelas
    }
}

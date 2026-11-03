<?php

namespace App\Features\Tasks\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, list<string>> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
            'longDescription' => ['nullable', 'string'],
            'dueDate' => ['nullable', 'date_format:Y-m-d'],
            'assignee_id' => ['nullable', 'uuid', 'exists:users,id'],
        ];
    }
}

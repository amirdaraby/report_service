<?php

namespace App\Http\Requests\Api\V1\Report;

use App\Enums\Frequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'frequency' => ['required', Rule::in(Frequency::values())],
            'keywords' => ['required', 'array', 'min:1'],
            'keywords.*' => ['required', 'string'],
        ];
    }
}

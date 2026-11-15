<?php

namespace App\Http\Requests\Admin\Reports;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ReportFilterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from'     => ['nullable', 'date'],
            'to'       => ['nullable', 'date', 'after_or_equal:from'],
            'group_by' => ['nullable', 'in:day,week,month'],
            'limit'    => ['nullable', 'integer', 'between:1,50'],
        ];
    }
    public function from(): Carbon
    {
        return $this->filled('from')
            ? Carbon::parse($this->input('from'))->startOfDay()
            : now()->subDays(29)->startOfDay();
    }

    public function to(): Carbon
    {
        return $this->filled('to')
            ? Carbon::parse($this->input('to'))->endOfDay()
            : now()->endOfDay();
    }
    public function groupBy(): string
    {
        return $this->input('group_by', 'day');
    }

    public function limit(): int
    {
        return (int) $this->input('limit', 10);
    }
}

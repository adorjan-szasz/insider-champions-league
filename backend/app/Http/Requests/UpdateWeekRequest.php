<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWeekRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $weekId = $this->route('week')?->id;

        return [
            'week_number' => [
                'sometimes',
                'integer',
                'min:1',
                'max:4',
                Rule::unique('weeks')->ignore($weekId)->where(function ($query) {
                    return $query->where('league_id', $this->input('league_id'));
                }),
            ],
            'league_id' => 'sometimes|exists:leagues,id',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeekRequest extends FormRequest
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
        return [
            'week_number' => [
                'required',
                'integer',
                'min:1',
                'max:4',
                Rule::unique('weeks')->where(function ($query) {
                    return $query->where('league_id', $this->input('league_id'));
                }),
            ],
            'league_id' => 'required|exists:leagues,id',
        ];
    }
}

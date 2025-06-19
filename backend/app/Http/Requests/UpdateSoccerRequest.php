<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSoccerRequest extends FormRequest
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
            'home_team_id' => 'sometimes|exists:teams,id|different:away_team_id',
            'away_team_id' => 'sometimes|exists:teams,id',
            'week_id' => 'sometimes|exists:weeks,id',
            'home_goals' => 'nullable|integer|min:0',
            'away_goals' => 'nullable|integer|min:0',
        ];
    }
}

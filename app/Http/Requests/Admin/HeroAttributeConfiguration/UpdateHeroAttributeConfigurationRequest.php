<?php

namespace App\Http\Requests\Admin\HeroAttributeConfiguration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHeroAttributeConfigurationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          'base_agility' => 'required|integer|min:0',
          'base_mental' => 'required|integer|min:0',
          'base_will' => 'required|integer|min:0',
          'base_strength' => 'required|integer|min:0',
          'base_armor' => 'required|integer|min:0',
          'total_points' => 'required|integer|min:1'
        ];
    }
}
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroRaceRequest extends FormRequest
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
    $rules = [
      'name' => [
        'required',
        'string',
        'max:255',
      ],
    ];

    // Add unique rule for creation or update differently
    if ($this->isMethod('post')) {
      $rules['name'][] = 'unique:hero_races';
    } else {
      $rules['name'][] = Rule::unique('hero_races')->ignore($this->hero_race);
    }

    return $rules;
  }
  
  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la raza es obligatorio.',
      'name.unique' => 'Ya existe una raza con este nombre.'
    ];
  }
}
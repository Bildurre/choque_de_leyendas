<?php

namespace App\Http\Requests\Admin\HeroRace;

use Illuminate\Foundation\Http\FormRequest;

class StoreHeroRaceRequest extends FormRequest
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
      'name' => 'required|string|max:255|unique:hero_races'
    ];
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
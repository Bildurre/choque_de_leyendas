<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroRaceRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $heroRaceId = $this->route('hero_race');
      $rules['name'][] = Rule::unique('hero_races')->ignore($heroRaceId);
    } else {
      $rules['name'][] = 'unique:hero_races,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la raza es obligatorio.',
      'name.unique' => 'Ya existe una raza con este nombre.',
    ];
  }
}
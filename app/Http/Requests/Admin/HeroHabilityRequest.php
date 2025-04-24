<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroAbilityRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'attack_range_id' => ['required', 'exists:attack_ranges,id'],
      'attack_subtype_id' => ['required', 'exists:attack_subtypes,id'],
      'blast' => ['boolean'],
      'cost' => ['required', 'string', 'max:5', 'regex:/^[RGBrgb]*$/'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $heroAbilityId = $this->route('hero_ability');
      $rules['name'][] = Rule::unique('hero_abilities')->ignore($heroAbilityId);
    } else {
      $rules['name'][] = 'unique:hero_abilities,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la habilidad es obligatorio.',
      'name.unique' => 'Ya existe una habilidad con este nombre.',
      'attack_range_id.required' => 'El rango de ataque es obligatorio.',
      'attack_range_id.exists' => 'El rango de ataque seleccionado no existe.',
      'attack_subtype_id.required' => 'El subtipo de ataque es obligatorio.',
      'attack_subtype_id.exists' => 'El subtipo de ataque seleccionado no existe.',
      'cost.required' => 'El coste de la habilidad es obligatorio.',
      'cost.regex' => 'El coste solo puede contener los caracteres R, G, B.',
      'cost.max' => 'El coste no puede tener más de 5 caracteres.',
    ];
  }
}
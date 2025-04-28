<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class HeroRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'lore_text' => ['nullable', 'string'],
      'passive_name' => ['nullable', 'string', 'max:255'],
      'passive_description' => ['nullable', 'string'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'faction_id' => ['nullable', 'exists:factions,id'],
      'hero_race_id' => ['required', 'exists:hero_races,id'],
      'hero_class_id' => ['required', 'exists:hero_classes,id'],
      'gender' => ['required', 'in:male,female'],
      'agility' => ['required', 'integer', 'min:1'],
      'mental' => ['required', 'integer', 'min:1'],
      'will' => ['required', 'integer', 'min:1'],
      'strength' => ['required', 'integer', 'min:1'],
      'armor' => ['required', 'integer', 'min:1'],
      'abilities' => ['nullable', 'array'],
      'abilities.*' => ['exists:hero_abilities,id'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $heroId = $this->route('hero');
      $rules['name'][] = Rule::unique('heroes')->ignore($heroId);
    } else {
      $rules['name'][] = 'unique:heroes,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del héroe es obligatorio.',
      'name.unique' => 'Ya existe un héroe con este nombre.',
      'hero_race_id.required' => 'La raza del héroe es obligatoria.',
      'hero_race_id.exists' => 'La raza seleccionada no existe.',
      'hero_class_id.required' => 'La clase del héroe es obligatoria.',
      'hero_class_id.exists' => 'La clase seleccionada no existe.',
      'gender.required' => 'El género del héroe es obligatorio.',
      'gender.in' => 'El género debe ser masculino o femenino.',
      'agility.required' => 'La agilidad es obligatoria.',
      'agility.integer' => 'La agilidad debe ser un número entero.',
      'agility.min' => 'La agilidad debe ser al menos 1.',
      'mental.required' => 'El mental es obligatorio.',
      'mental.integer' => 'El mental debe ser un número entero.',
      'mental.min' => 'El mental debe ser al menos 1.',
      'will.required' => 'La voluntad es obligatoria.',
      'will.integer' => 'La voluntad debe ser un número entero.',
      'will.min' => 'La voluntad debe ser al menos 1.',
      'strength.required' => 'La fuerza es obligatoria.',
      'strength.integer' => 'La fuerza debe ser un número entero.',
      'strength.min' => 'La fuerza debe ser al menos 1.',
      'armor.required' => 'La armadura es obligatoria.',
      'armor.integer' => 'La armadura debe ser un número entero.',
      'armor.min' => 'La armadura debe ser al menos 1.',
      'abilities.*.exists' => 'Una de las habilidades seleccionadas no existe.',
    ];
  }
}
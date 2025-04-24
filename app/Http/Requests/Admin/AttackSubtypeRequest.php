<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttackSubtypeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'type' => ['required', 'in:physical,magical'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $attackSubtypeId = $this->route('attack_subtype');
      $rules['name'][] = Rule::unique('attack_subtypes')->ignore($attackSubtypeId);
    } else {
      $rules['name'][] = 'unique:attack_subtypes,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del subtipo de ataque es obligatorio.',
      'name.unique' => 'Ya existe un subtipo de ataque con este nombre.',
      'type.required' => 'El tipo de ataque es obligatorio.',
      'type.in' => 'El tipo debe ser físico o mágico.',
    ];
  }
}
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttackRangeRequest extends FormRequest
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
      $attackRangeId = $this->route('attack_range');
      $rules['name'][] = Rule::unique('attack_ranges')->ignore($attackRangeId);
    } else {
      $rules['name'][] = 'unique:attack_ranges,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del rango de ataque es obligatorio.',
      'name.unique' => 'Ya existe un rango de ataque con este nombre.',
    ];
  }
}
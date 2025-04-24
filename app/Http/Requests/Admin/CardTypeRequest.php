<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CardTypeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'hero_superclass_id' => ['nullable', 'exists:hero_superclasses,id'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $cardTypeId = $this->route('card_type');
      $rules['name'][] = Rule::unique('card_types')->ignore($cardTypeId);
    } else {
      $rules['name'][] = 'unique:card_types,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del tipo de carta es obligatorio.',
      'name.unique' => 'Ya existe un tipo de carta con este nombre.',
      'hero_superclass_id.exists' => 'La superclase seleccionada no existe.',
    ];
  }
}
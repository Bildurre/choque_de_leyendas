<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttackSubtypeRequest extends FormRequest
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
      'attack_type_id' => 'required|exists:attack_types,id',
    ];

    // Add unique rule for creation or update differently
    if ($this->isMethod('post')) {
      $rules['name'][] = 'unique:attack_subtypes';
    } else {
      $rules['name'][] = Rule::unique('attack_subtypes')->ignore($this->attack_subtype);
    }

    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del subtipo es obligatorio.',
      'name.unique' => 'Ya existe un subtipo con este nombre.',
      'attack_type_id.required' => 'El tipo de ataque es obligatorio.',
      'attack_type_id.exists' => 'El tipo de ataque seleccionado no es válido.'
    ];
  }
}
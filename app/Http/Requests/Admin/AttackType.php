<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttackTypeRequest extends FormRequest
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
      ]
    ];

    // Add unique rule for creation or update differently
    if ($this->isMethod('post')) {
      $rules['name'][] = 'unique:attack_types';
    } else {
      $rules['name'][] = Rule::unique('attack_types')->ignore($this->attack_type);
    }

    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del tipo es obligatorio.',
      'name.unique' => 'Ya existe un tipo con este nombre.'
    ];
  }
}
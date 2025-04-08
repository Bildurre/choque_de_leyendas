<?php

namespace App\Http\Requests\Admin\AttackType;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttackTypeRequest extends FormRequest
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
      'name' => [
        'required',
        'string',
        'max:255',
        Rule::unique('attack_types')->ignore($this->attack_type->id)
      ],
      'description' => 'nullable|string',
      'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del tipo es obligatorio.',
      'name.unique' => 'Ya existe un tipo con este nombre.',
      'color.required' => 'El color del tipo es obligatorio.',
      'color.regex' => 'El color debe estar en formato hexadecimal (#RRGGBB).',
    ];
  }
}
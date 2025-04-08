<?php

namespace App\Http\Requests\Admin\AttackSubtype;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttackSubtypeRequest extends FormRequest
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
      'name' => 'required|string|max:255|unique:attack_subtypes',
      'description' => 'nullable|string',
      'attack_type_id' => 'required|exists:attack_types,id',
      'color' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del subtipo es obligatorio.',
      'name.unique' => 'Ya existe un subtipo con este nombre.',
      'attack_type_id.required' => 'El tipo de habilidad es obligatorio.',
      'attack_type_id.exists' => 'El tipo de habilidad seleccionado no es válido.',
      'color.regex' => 'El color debe estar en formato hexadecimal (#RRGGBB).',
    ];
  }
}
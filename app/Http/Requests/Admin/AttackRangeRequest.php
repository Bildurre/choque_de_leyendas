<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttackRangeRequest extends FormRequest
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
      'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    // Add unique rule for creation or update differently
    if ($this->isMethod('post')) {
      $rules['name'][] = 'unique:attack_ranges';
    } else {
      $rules['name'][] = Rule::unique('attack_ranges')->ignore($this->attack_range);
      $rules['remove_icon'] = 'nullable|boolean';
    }

    return $rules;
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del rango es obligatorio.',
      'name.unique' => 'Ya existe un rango con este nombre.',
      'icon.image' => 'El archivo debe ser una imagen.',
      'icon.max' => 'La imagen no debe superar los 2MB.',
    ];
  }
}
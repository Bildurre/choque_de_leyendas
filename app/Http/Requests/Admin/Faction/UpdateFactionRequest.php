<?php

namespace App\Http\Requests\Admin\Faction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFactionRequest extends FormRequest
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
        Rule::unique('factions')->ignore($this->faction->id),
      ],
      'lore_text' => 'nullable|string',
      'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
      'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'remove_icon' => 'nullable|boolean',
    ];
  }
  
  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la facción es obligatorio.',
      'name.unique' => 'Ya existe una facción con este nombre.',
      'color.required' => 'El color de la facción es obligatorio.',
      'color.regex' => 'El color debe estar en formato hexadecimal (#RRGGBB).',
      'icon.image' => 'El archivo debe ser una imagen.',
      'icon.max' => 'La imagen no debe superar los 2MB.',
    ];
  }
}
<?php

namespace App\Http\Requests\Admin\Faction;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactionRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    // Como tienes un middleware de admin, podemos retornar true 
    // ya que la autorización se maneja allí
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
      'name' => 'required|string|max:255|unique:factions',
      'lore_text' => 'nullable|string',
      'color' => 'required|string|max:7|regex:/^#[0-9A-F]{6}$/i',
      'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
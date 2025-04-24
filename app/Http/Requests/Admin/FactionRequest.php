<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactionRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'lore_text' => ['nullable', 'string'],
      'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6})$/'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $factionId = $this->route('faction');
      $rules['name'][] = Rule::unique('factions')->ignore($factionId);
    } else {
      $rules['name'][] = 'unique:factions,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la facción es obligatorio.',
      'name.unique' => 'Ya existe una facción con este nombre.',
      'color.required' => 'El color de la facción es obligatorio.',
      'color.regex' => 'El color debe estar en formato hexadecimal (ej. #FFFFFF).',
      'icon.image' => 'El archivo debe ser una imagen.',
      'icon.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe pesar más de 2MB.',
    ];
  }
}
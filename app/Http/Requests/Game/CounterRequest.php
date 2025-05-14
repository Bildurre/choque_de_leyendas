<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class CounterRequest extends FormRequest
{
  use ValidatesTranslatableUniqueness;

  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    $counterId = $this->route('counter');
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'effect' => ['nullable', 'array'],
      'type' => ['required', 'string', 'in:boon,bane'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'remove_icon' => ['nullable', 'boolean'],
    ];

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre del contador es obligatorio.',
      'name.array' => 'El nombre debe ser un array con traducciones.',
      'name.es.required' => 'El nombre en español es obligatorio.',
      'type.required' => 'El tipo es obligatorio.',
      'type.string' => 'El tipo debe ser una cadena de texto.',
      'type.in' => 'El tipo seleccionado no es válido.',
      'icon.image' => 'El archivo debe ser una imagen válida.',
      'icon.mimes' => 'El archivo debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe ser mayor de 2MB.',
    ];
  }
}
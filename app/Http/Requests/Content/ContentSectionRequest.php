<?php

namespace App\Http\Requests\Content;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class ContentSectionRequest extends FormRequest
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
    $pageId = $this->route('page')->id;
    $sectionId = $this->route('section')?->id;
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'title' => 'required|array',
      'title.es' => 'required|string|max:255',
      'anchor_id' => 'nullable|string|max:255',
      'include_in_index' => 'boolean',
      'background_color' => 'nullable|string|max:7|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
      'order' => 'integer',
    ];

    return $rules;
  }

  /**
   * Get the error messages for the defined validation rules.
   */
  public function messages(): array
  {
    return [
      'title.required' => 'El título de la sección es obligatorio.',
      'title.array' => 'El título debe ser un array con traducciones.',
      'title.es.required' => 'El título en español es obligatorio.',
      'anchor_id.max' => 'El ID de anclaje no puede tener más de 255 caracteres.',
      'background_color.regex' => 'El color de fondo debe ser un valor hexadecimal válido (ej. #FFFFFF).',
    ];
  }
}
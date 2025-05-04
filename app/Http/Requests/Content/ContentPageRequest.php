<?php

namespace App\Http\Requests\Content;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class ContentPageRequest extends FormRequest
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
    $pageId = $this->route('page')?->id;
    $locales = config('app.available_locales', ['es']);
    
    $rules = [
      'title' => 'required|array',
      'title.es' => 'required|string|max:255',
      'slug' => 'nullable|string|max:255|unique:content_pages,slug' . ($pageId ? ',' . $pageId : ''),
      'meta_description' => 'nullable|array',
      'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'remove_background_image' => 'nullable|in:0,1',
      'is_published' => 'boolean',
      'show_in_menu' => 'boolean',
      'order' => 'integer',
      'parent_slug' => 'nullable|string|exists:content_pages,slug',
    ];

    // Add translatable uniqueness rules
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('content_pages', 'title', $pageId, $locales)
    );

    return $rules;
  }

  /**
   * Get the error messages for the defined validation rules.
   */
  public function messages(): array
  {
    $messages = [
      'title.required' => 'El título es obligatorio.',
      'title.array' => 'El título debe ser un array con traducciones.',
      'title.es.required' => 'El título en español es obligatorio.',
      'slug.unique' => 'El slug ya está en uso.',
      'parent_slug.exists' => 'La página padre seleccionada no existe.',
      'background_image.image' => 'El archivo debe ser una imagen.',
      'background_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
      'background_image.max' => 'La imagen no debe pesar más de 2MB.',
    ];
    
    // Add translatable uniqueness messages
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["title.{$locale}.unique"] = "Ya existe una página con este título en {$localeName}.";
    }
    
    return $messages;
  }
}
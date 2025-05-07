<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
    $locales = config('app.available_locales', ['es']);
    $pageId = $this->route('page') ? $this->route('page')->id : null;
    
    $rules = [
      'title' => 'required|array',
      'title.es' => 'required|string|max:255',
      'description' => 'nullable|array',
      'meta_title' => 'nullable|array',
      'meta_description' => 'nullable|array',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'background_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'is_published' => 'nullable|boolean',
      'parent_id' => 'nullable|exists:pages,id',
      'template' => 'nullable|string',
      'order' => 'nullable|integer|min:0',
      'remove_image' => 'nullable|boolean',
      'remove_background_image' => 'nullable|boolean',
    ];

    foreach ($locales as $locale) {
      if ($locale !== 'es') {
        $rules["title.{$locale}"] = 'nullable|string|max:255';
      }
    }
    
    return $rules;
  }

  /**
   * Get the error messages for the defined validation rules.
   */
  public function messages(): array
  {
    return [
      'title.required' => 'The page title is required.',
      'title.es.required' => 'The page title in Spanish is required.',
      'parent_id.exists' => 'The selected parent page does not exist.',
    ];
  }
}
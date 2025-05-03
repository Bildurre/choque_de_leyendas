<?php
namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class ContentBlockRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->user()->isAdmin();
  }

  public function rules(): array
  {
    $availableTypes = array_keys(config('content-blocks.types', []));
    
    return [
      'content_section_id' => 'required|exists:content_sections,id',
      'type' => 'required|string|in:' . implode(',', $availableTypes),
      'content' => 'nullable|array',
      'content.*' => 'nullable|string',
      'image' => 'nullable|image|max:2048',
      'metadata' => 'nullable|array',
      'model_type' => 'nullable|string',
      'filters' => 'nullable|array',
      'settings' => 'nullable|array',
      'order' => 'nullable|integer|min:0',
      'include_in_index' => 'nullable|boolean'
    ];
  }
}
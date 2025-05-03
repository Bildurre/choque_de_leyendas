<?php
namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentPageRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->user()->isAdmin();
  }

  public function rules(): array
  {
    $pageId = $this->route('content_page')?->id;
    
    return [
      'title' => 'required|array',
      'title.*' => 'required|string|max:255',
      'slug' => [
        'required',
        'string',
        'max:255',
        Rule::unique('content_pages')->ignore($pageId)
      ],
      'type' => 'required|string|in:standard,rules,components,annexes,home',
      'meta_description' => 'nullable|array',
      'meta_description.*' => 'nullable|string|max:500',
      'background_image' => 'nullable|image|max:2048',
      'header_config' => 'nullable|array',
      'show_index' => 'nullable|boolean',
      'is_published' => 'nullable|boolean'
    ];
  }
}
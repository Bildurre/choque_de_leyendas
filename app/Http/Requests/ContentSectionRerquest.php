<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentSectionRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->user()->isAdmin();
  }

  public function rules(): array
  {
    return [
      'content_page_id' => 'required|exists:content_pages,id',
      'title' => 'required|array',
      'title.*' => 'required|string|max:255',
      'anchor_id' => 'nullable|string|max:255',
      'order' => 'nullable|integer|min:0',
      'include_in_index' => 'nullable|boolean',
      'settings' => 'nullable|array'
    ];
  }
}
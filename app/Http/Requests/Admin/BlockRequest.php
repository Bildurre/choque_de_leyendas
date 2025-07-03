<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Admin\BlockRules\HeaderRules;
use App\Http\Requests\Admin\BlockRules\TextRules;
use App\Http\Requests\Admin\BlockRules\CtaRules;
use App\Http\Requests\Admin\BlockRules\RelatedsRules;

class BlockRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation()
  {
    $data = $this->all();
    
    // Ensure is_printable is always present
    if (!isset($data['is_printable'])) {
      $data['is_printable'] = 0;
    }
    
    $this->merge($data);
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    // Get the block type
    $type = $this->input('type');
    
    // If we're in edit mode, type might not be in the request
    if (!$type && $this->route('block')) {
      $type = $this->route('block')->type;
    }
    
    // Base rules common to all block types
    $rules = [
      'is_printable' => ['nullable', 'boolean'],
      'background_color' => ['nullable', 'string'],
      'image' => ['nullable', 'image', 'max:2048'],
      'remove_image' => ['nullable', 'boolean'],
      'settings' => ['nullable', 'array'],
    ];
    
    // Only require type on creation
    if ($this->isMethod('POST')) {
      $rules['type'] = ['required', 'string'];
    }
    
    // Get type-specific rules
    $typeRules = $this->getTypeSpecificRules($type);
    
    // Merge base rules with type-specific rules
    return array_merge($rules, $typeRules);
  }
  
  /**
   * Get type-specific validation rules
   */
  protected function getTypeSpecificRules(?string $type): array
  {
    if (!$type) {
      return [];
    }
    
    return match($type) {
      'header' => HeaderRules::getRules(),
      'text' => TextRules::getRules(),
      'cta' => CtaRules::getRules(),
      'relateds' => RelatedsRules::getRules(),
      default => [],
    };
  }
  
  /**
   * Get custom error messages.
   */
  public function messages(): array
  {
    $type = $this->input('type') ?? $this->route('block')?->type;
    
    $baseMessages = [
      'type.required' => 'El tipo de bloque es obligatorio.',
      'image.image' => 'El archivo debe ser una imagen válida.',
      'image.max' => 'La imagen no debe ser mayor de 2MB.',
    ];
    
    if (!$type) {
      return $baseMessages;
    }
    
    $typeMessages = match($type) {
      'header' => HeaderRules::getMessages(),
      'text' => TextRules::getMessages(),
      'cta' => CtaRules::getMessages(),
      'relateds' => RelatedsRules::getMessages(),
      default => [],
    };
    
    return array_merge($baseMessages, $typeMessages);
  }
}
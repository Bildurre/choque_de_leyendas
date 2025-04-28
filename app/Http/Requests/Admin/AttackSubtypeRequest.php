<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;

class AttackSubtypeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'array'],
      'name.*' => ['required', 'string', 'max:255'],
      'type' => ['required', 'in:physical,magical'],
    ];

    // Para cada idioma, añadimos regla de unicidad
    foreach (config('app.available_locales', ['es']) as $locale) {
      $nameField = "name.$locale";
      
      if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
        $attackSubtypeId = $this->route('attack_subtype');
        // Verificamos que el nombre sea único para este idioma
        $rules[$nameField][] = Rule::unique('attack_subtypes', 'name->$locale')->ignore($attackSubtypeId);
      } else {
        $rules[$nameField][] = Rule::unique('attack_subtypes', 'name->$locale');
      }
    }

    return $rules;
  }

  public function messages(): array
  {
    $messages = [];
    
    foreach (config('app.available_locales', ['es']) as $locale) {
      $messages["name.$locale.required"] = __('validation.required', [
        'attribute' => __('attack_subtypes.name') . ' (' . locale_name($locale) . ')'
      ]);
      
      $messages["name.$locale.unique"] = __('validation.unique', [
        'attribute' => __('attack_subtypes.name') . ' (' . locale_name($locale) . ')'
      ]);
    }
    
    return array_merge([
      'name.required' => 'El nombre del subtipo de ataque es obligatorio.',
      'type.required' => 'El tipo de ataque es obligatorio.',
      'type.in' => 'El tipo debe ser físico o mágico.',
    ], $messages);
  }
}
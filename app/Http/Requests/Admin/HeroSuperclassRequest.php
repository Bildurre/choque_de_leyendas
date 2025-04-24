<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroSuperclassRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    ];

    if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
      $heroSuperclassId = $this->route('hero_superclass');
      $rules['name'][] = Rule::unique('hero_superclasses')->ignore($heroSuperclassId);
    } else {
      $rules['name'][] = 'unique:hero_superclasses,name';
    }

    return $rules;
  }

  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la superclase es obligatorio.',
      'name.unique' => 'Ya existe una superclase con este nombre.',
      'icon.image' => 'El archivo debe ser una imagen.',
      'icon.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'icon.max' => 'La imagen no debe pesar más de 2MB.',
    ];
  }
}
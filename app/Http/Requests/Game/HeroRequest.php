<?php

namespace App\Http\Requests\Game;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class HeroRequest extends FormRequest
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
    $heroId = $this->route('hero');
    $locales = config('app.available_locales', ['es']);
    $configService = app(\App\Services\Game\HeroAttributesConfigurationService::class);
    $config = $configService->getConfiguration();
    
    $rules = [
      'name' => ['required', 'array'],
      'name.es' => ['required', 'string', 'max:255'],
      'lore_text' => ['nullable', 'array'],
      'passive_name' => ['nullable', 'array'],
      'passive_description' => ['nullable', 'array'],
      'faction_id' => ['nullable', 'exists:factions,id'],
      'hero_race_id' => ['required', 'exists:hero_races,id'],
      'hero_class_id' => ['required', 'exists:hero_classes,id'],
      'gender' => ['required', 'in:male,female'],
      'agility' => ['required', 'integer', 'min:' . $config->min_attribute_value, 'max:' . $config->max_attribute_value],
      'mental' => ['required', 'integer', 'min:' . $config->min_attribute_value, 'max:' . $config->max_attribute_value],
      'will' => ['required', 'integer', 'min:' . $config->min_attribute_value, 'max:' . $config->max_attribute_value],
      'strength' => ['required', 'integer', 'min:' . $config->min_attribute_value, 'max:' . $config->max_attribute_value],
      'armor' => ['required', 'integer', 'min:' . $config->min_attribute_value, 'max:' . $config->max_attribute_value],
      'hero_abilities' => ['nullable', 'array'],
      'hero_abilities.*' => ['exists:hero_abilities,id'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'remove_image' => ['nullable', 'boolean'],
      'is_published' => ['nullable', 'boolean'],
    ];

    // Add total attributes validation
    $rules['agility'][] = function ($attribute, $value, $fail) use ($config) {
      $total = (int)$value + 
        (int)$this->input('mental') + 
        (int)$this->input('will') + 
        (int)$this->input('strength') + 
        (int)$this->input('armor');
      
      if ($total < $config->min_total_attributes) {
        $fail(__('heroes.validation.min_total_attributes', [
          'min' => $config->min_total_attributes
        ]));
      }
      
      if ($total > $config->max_total_attributes) {
        $fail(__('heroes.validation.max_total_attributes', [
          'max' => $config->max_total_attributes
        ]));
      }
    };

    // Add uniqueness rules for each locale
    $rules = array_merge(
      $rules, 
      $this->uniqueTranslatableRules('heroes', 'name', $heroId, $locales)
    );

    return $rules;
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    $messages = [
      'name.required' => 'El nombre del héroe es obligatorio.',
      'name.array' => __('validation.array', ['attribute' => __('common.name')]),
      'name.es.required' => __('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')]),
      'hero_race_id.required' => 'La raza es obligatoria.',
      'hero_race_id.exists' => 'La raza seleccionada no existe.',
      'hero_class_id.required' => 'La clase es obligatoria.',
      'hero_class_id.exists' => 'La clase seleccionada no existe.',
      'faction_id.exists' => 'La facción seleccionada no existe.',
      'gender.required' => 'El género es obligatorio.',
      'gender.in' => 'El género debe ser masculino o femenino.',
      'agility.required' => 'El atributo de agilidad es obligatorio.',
      'agility.integer' => 'El atributo de agilidad debe ser un número entero.',
      'agility.min' => 'El atributo de agilidad debe ser al menos :min.',
      'agility.max' => 'El atributo de agilidad no puede ser mayor que :max.',
      'mental.required' => 'El atributo de mente es obligatorio.',
      'mental.integer' => 'El atributo de mente debe ser un número entero.',
      'mental.min' => 'El atributo de mente debe ser al menos :min.',
      'mental.max' => 'El atributo de mente no puede ser mayor que :max.',
      'will.required' => 'El atributo de voluntad es obligatorio.',
      'will.integer' => 'El atributo de voluntad debe ser un número entero.',
      'will.min' => 'El atributo de voluntad debe ser al menos :min.',
      'will.max' => 'El atributo de voluntad no puede ser mayor que :max.',
      'strength.required' => 'El atributo de fuerza es obligatorio.',
      'strength.integer' => 'El atributo de fuerza debe ser un número entero.',
      'strength.min' => 'El atributo de fuerza debe ser al menos :min.',
      'strength.max' => 'El atributo de fuerza no puede ser mayor que :max.',
      'armor.required' => 'El atributo de armadura es obligatorio.',
      'armor.integer' => 'El atributo de armadura debe ser un número entero.',
      'armor.min' => 'El atributo de armadura debe ser al menos :min.',
      'armor.max' => 'El atributo de armadura no puede ser mayor que :max.',
      'hero_abilities.array' => 'Las habilidades deben ser un array.',
      'hero_abilities.*.exists' => 'La habilidad seleccionada no existe.',
      'image.image' => 'El archivo debe ser una imagen válida.',
      'image.mimes' => 'El archivo debe ser de tipo: jpeg, png, jpg, gif, svg.',
      'image.max' => 'La imagen no debe ser mayor de 2MB.',
    ];

    // Messages for uniqueness in each language
    foreach (config('app.available_locales', ['es']) as $locale) {
      $localeName = locale_name($locale);
      $messages["name.{$locale}.unique"] = "Ya existe un héroe con este nombre en {$localeName}.";
    }

    return $messages;
  }
}
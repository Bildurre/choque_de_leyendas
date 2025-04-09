<?php

namespace App\Http\Requests\Admin\HeroAbility;

use App\Services\CostTranslatorService;
use Illuminate\Foundation\Http\FormRequest;

class StoreHeroAbilityRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'description' => 'required|string',
      'attack_subtype_id' => 'nullable|exists:attack_subtypes,id',
      'attack_range_id' => 'nullable|exists:attack_ranges,id',
      'cost' => 'required|string|max:5'
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'El nombre de la habilidad es obligatorio.',
      'description.required' => 'La descripción de la habilidad es obligatoria.',
      'attack_subtype_id.exists' => 'El subtipo seleccionado no es válido.',
      'attack_range_id.exists' => 'El rango seleccionado no es válido.',
      'cost.required' => 'El coste de activación es obligatorio.'
    ];
  }

  /**
   * Configure the validator instance.
   *
   * @param \Illuminate\Validation\Validator $validator
   * @return void
   */
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $cost = $this->input('cost');
      
      // Skip validation if cost is empty
      if (empty($cost)) {
        return;
      }
      
      $costTranslator = app(CostTranslatorService::class);
      if (!$costTranslator->isValidCost($cost)) {
        $validator->errors()->add(
          'cost', 
          'El coste debe contener solo los caracteres R, G, B y tener un máximo de 5 dados.'
        );
      }
    });
  }
}
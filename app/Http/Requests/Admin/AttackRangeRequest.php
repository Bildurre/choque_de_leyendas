<?php

namespace App\Http\Requests\Admin;

use App\Models\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class AttackRangeRequest extends FormRequest
{
    use ValidatesTranslatableUniqueness;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $attackRangeId = $this->route('attack_range');
        $locales = config('app.available_locales', ['es']);
        
        $rules = [
            'name' => ['required', 'array'],
            'name.es' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
        
        // Agregar reglas de unicidad para cada idioma
        $rules = array_merge(
            $rules, 
            $this->uniqueTranslatableRules('attack_ranges', 'name', $attackRangeId, $locales)
        );
        
        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'name.required' => 'El nombre del rango de ataque es obligatorio.',
            'name.array' => 'El nombre debe ser un array con traducciones.',
            'name.es.required' => 'El nombre en español es obligatorio.',
            'icon.image' => 'El archivo debe ser una imagen.',
            'icon.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
            'icon.max' => 'La imagen no debe pesar más de 2MB.',
        ];
        
        // Mensajes para la unicidad en cada idioma
        foreach (config('app.available_locales', ['es']) as $locale) {
            $localeName = locale_name($locale);
            $messages["name.{$locale}.unique"] = "Ya existe un rango de ataque con este nombre en {$localeName}.";
        }
        
        return $messages;
    }
}
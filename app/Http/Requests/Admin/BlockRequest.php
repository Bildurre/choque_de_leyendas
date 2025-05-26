<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Asumimos que la autorización ya se maneja en el middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Obtener el tipo de bloque
        $type = $this->input('type');
        
        // Reglas base comunes a todos los tipos de bloques
        $rules = [
            'title' => ['nullable', 'array'],
            'subtitle' => ['nullable', 'array'],
            'background_color' => ['nullable', 'string'],
            'settings' => ['nullable', 'array'],
            'type' => ['required', 'string'], // El tipo es siempre requerido
            'image' => ['nullable', 'image', 'max:2048'], // Si se sube una imagen
            'remove_image' => ['nullable', 'boolean'], // Para eliminar la imagen existente
        ];
        
        // Si estamos en modo edición, el tipo podría no estar presente en el request
        if (!$type && $this->route('block')) {
            $type = $this->route('block')->type;
        }
        
        // Agregar reglas específicas según el tipo de bloque
        if ($type === 'text') {
            $rules['content'] = ['required', 'array'];
            $rules['content.es'] = ['required', 'string']; // El contenido en español es obligatorio
            
            // Contenido en otros idiomas es opcional
            foreach (array_keys(config('laravellocalization.supportedLocales', ['es' => []])) as $locale) {
                if ($locale !== 'es') {
                    $rules["content.{$locale}"] = ['nullable', 'string'];
                }
            }
        } elseif ($type === 'header') {
            // Para un bloque de tipo encabezado, título es obligatorio
            $rules['title'] = ['required', 'array'];
            $rules['title.es'] = ['required', 'string'];
        } elseif ($type === 'relateds') {
            // Para un bloque de tipo relacionados, título es obligatorio
            $rules['title'] = ['required', 'array'];
            $rules['title.es'] = ['required', 'string'];
        } elseif ($type === 'cta') {
            // Para un bloque de tipo CTA
            $rules['title'] = ['required', 'array'];
            $rules['title.es'] = ['required', 'string'];
            
            // El content en CTA contendrá: text, button_text, button_link
            $rules['content'] = ['required', 'array'];
            $rules['content.text'] = ['required', 'array'];
            $rules['content.text.es'] = ['required', 'string'];
            $rules['content.button_text'] = ['required', 'array'];
            $rules['content.button_text.es'] = ['required', 'string'];
            $rules['content.button_link'] = ['required', 'array'];
            $rules['content.button_link.es'] = ['required', 'string'];
            
            // Otros idiomas son opcionales
            foreach (array_keys(config('laravellocalization.supportedLocales', ['es' => []])) as $locale) {
                if ($locale !== 'es') {
                    $rules["content.text.{$locale}"] = ['nullable', 'string'];
                    $rules["content.button_text.{$locale}"] = ['nullable', 'string'];
                    $rules["content.button_link.{$locale}"] = ['nullable', 'string'];
                }
            }
        }
        
        // También podríamos tener reglas específicas para cada campo settings según el tipo
        if ($type && isset($rules['settings']) && $this->has('settings')) {
            $blockTypes = config('blocks.types', []);
            if (isset($blockTypes[$type]['settings'])) {
                foreach ($blockTypes[$type]['settings'] as $settingKey => $setting) {
                    if ($setting['type'] === 'boolean') {
                        $rules["settings.{$settingKey}"] = ['nullable', 'boolean'];
                    } elseif ($setting['type'] === 'select') {
                        $rules["settings.{$settingKey}"] = ['nullable', 'string', 'in:' . implode(',', array_keys($setting['options']))];
                    }
                }
            }
        }
        
        return $rules;
    }
    
    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'content.required' => 'El contenido del bloque es obligatorio.',
            'content.es.required' => 'El contenido en español es obligatorio.',
            'content.text.required' => 'El texto del CTA es obligatorio.',
            'content.text.es.required' => 'El texto del CTA en español es obligatorio.',
            'content.button_text.required' => 'El texto del botón es obligatorio.',
            'content.button_text.es.required' => 'El texto del botón en español es obligatorio.',
            'content.button_link.required' => 'El enlace del botón es obligatorio.',
            'content.button_link.es.required' => 'El enlace del botón en español es obligatorio.',
            'title.required' => 'El título del bloque es obligatorio.',
            'title.es.required' => 'El título en español es obligatorio.',
            'image.image' => 'El archivo debe ser una imagen válida.',
            'image.max' => 'La imagen no debe ser mayor de 2MB.',
        ];
    }
}
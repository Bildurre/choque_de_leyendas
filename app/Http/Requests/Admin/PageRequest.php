<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Traits\ValidatesTranslatableUniqueness;
use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        $pageId = $this->route('page') ? $this->route('page')->id : null;
        $locales = config('app.available_locales', ['es', 'en']);
        
        $rules = [
            'title' => ['required', 'array'],
            'title.es' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'meta_title' => ['nullable', 'array'],
            'meta_description' => ['nullable', 'array'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
            'parent_id' => ['nullable', 'exists:pages,id'],
            'template' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'remove_background_image' => ['nullable', 'boolean'],
        ];

        // Add rules for other locales
        foreach ($locales as $locale) {
            if ($locale !== 'es') {
                $rules["title.{$locale}"] = ['nullable', 'string', 'max:255'];
            }
            
            // Add validation rules for description, meta_title, etc. for each locale if needed
        }
        
        // Add unique translatable validation rules for title and slug
        $uniqueTitleRules = $this->uniqueTranslatableRules('pages', 'title', $pageId, $locales);
        $uniqueSlugRules = $this->uniqueTranslatableRules('pages', 'slug', $pageId, $locales);
        
        $rules = array_merge($rules, $uniqueTitleRules, $uniqueSlugRules);
        
        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        $locales = config('app.available_locales', ['es', 'en']);
        
        $messages = [
            'title.required' => 'The page title is required.',
            'title.es.required' => 'The page title in Spanish is required.',
            'parent_id.exists' => 'The selected parent page does not exist.',
        ];
        
        // Add unique translatable validation messages
        $titleMessages = $this->uniqueTranslatableMessages('title', $locales);
        $slugMessages = $this->uniqueTranslatableMessages('slug', $locales);
        
        return array_merge($messages, $titleMessages, $slugMessages);
    }
}
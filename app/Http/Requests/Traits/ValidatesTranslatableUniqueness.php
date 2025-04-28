<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait ValidatesTranslatableUniqueness
{
    /**
     * Generate unique validation rules for translatable fields
     *
     * @param string $table Table name
     * @param string $field Field name
     * @param mixed $ignoreId ID to ignore (for updates)
     * @param array $locales Locales to validate
     * @return array
     */
    protected function uniqueTranslatableRules(string $table, string $field, $ignoreId = null, array $locales = null): array
    {
        $locales = $locales ?? config('app.available_locales', ['es']);
        $rules = [];
        
        foreach ($locales as $locale) {
            $rule = Rule::unique($table, $field . '->' . $locale);
            
            if ($ignoreId) {
                $rule->ignore($ignoreId);
            }
            
            $rules[$field . '.' . $locale][] = $rule;
        }
        
        return $rules;
    }
}
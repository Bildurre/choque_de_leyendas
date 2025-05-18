<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Rule;

trait ValidatesTranslatableUniqueness
{
    /**
     * Generate unique validation rules for translatable fields.
     *
     * @param string $table The table name to check against
     * @param string $field The field name to validate
     * @param mixed $ignoreId The ID to ignore (for updates)
     * @param array $locales The supported locales
     * @return array The validation rules
     */
    protected function uniqueTranslatableRules(string $table, string $field, $ignoreId = null, array $locales = []): array
    {
        $rules = [];
        
        // Use provided locales or fall back to config
        $locales = !empty($locales) ? $locales : array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
        
        foreach ($locales as $locale) {
            // Using a JSON path expression for MySQL
            $jsonPath = "$.$locale";

            // Build a rule that uses DB::raw to check JSON content
            $rule = Rule::unique($table)->where(function ($query) use ($field, $jsonPath, $locale) {
                // This correctly extracts the JSON value and compares it
                // We're adding the whereJsonContains as a workaround for Laravel's JSON handling
                return $query->whereRaw("JSON_EXTRACT(`{$field}`, ?) IS NOT NULL", [$jsonPath]);
            });
            
            // Ignore the current model ID for updates
            if ($ignoreId) {
                $rule->ignore($ignoreId);
            }
            
            // Using a custom validation approach that will check the specific JSON value
            $rules["{$field}.{$locale}"] = [
                'sometimes',
                function ($attribute, $value, $fail) use ($table, $field, $locale, $ignoreId) {
                    // Skip empty values
                    if (empty($value)) {
                        return;
                    }
                    
                    // Build a query to check if this value exists in the JSON field for this locale
                    $query = \DB::table($table)
                        ->whereRaw("JSON_EXTRACT(`{$field}`, '$.{$locale}') = ?", [json_encode($value)]);
                    
                    // Exclude the current record if needed
                    if ($ignoreId) {
                        $query->where('id', '!=', $ignoreId);
                    }
                    
                    // Check if any record exists with this value
                    if ($query->exists()) {
                        $localeName = function_exists('locale_name') 
                            ? locale_name($locale) 
                            : strtoupper($locale);
                        $fail("The $field in $localeName has already been taken.");
                    }
                }
            ];
        }
        
        return $rules;
    }
    
    /**
     * Generate validation messages for translatable uniqueness rules.
     *
     * @param string $field The field being validated
     * @param array $locales The supported locales
     * @return array The error messages
     */
    protected function uniqueTranslatableMessages(string $field, array $locales = []): array
    {
        $messages = [];
        
        // Use provided locales or fall back to config
        $locales = !empty($locales) ? $locales : array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
        
        foreach ($locales as $locale) {
            $localeName = function_exists('locale_name') 
                ? locale_name($locale) 
                : strtoupper($locale);
                
            $messages["{$field}.{$locale}.unique"] = "The {$field} in {$localeName} has already been taken.";
        }
        
        return $messages;
    }
}
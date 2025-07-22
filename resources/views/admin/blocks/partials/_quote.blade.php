@php
  $contentData = isset($block) ? $block->getTranslations('content') : [];
  $textValues = [];
  $labelValues = [];
  
  // Extract values from content JSON
  foreach (config('laravellocalization.supportedLocales', ['es' => []]) as $locale => $localeData) {
    $textValues[$locale] = old("content.text.{$locale}", $contentData[$locale]['text'] ?? '');
    $labelValues[$locale] = old("content.label.{$locale}", $contentData[$locale]['label'] ?? '');
  }
@endphp

<x-form.multilingual-wysiwyg
  name="content"
  :label="__('pages.blocks.quote_text')"
  :values="$textValues"
  required
/>
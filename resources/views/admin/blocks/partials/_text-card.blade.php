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
  name="content[text]"
  :label="__('pages.blocks.text_card_text')"
  :values="$textValues"
  required
/>

<x-form.multilingual-input
  name="content[label]"
  :label="__('pages.blocks.text_card_label')"
  :values="$labelValues"
  required
/>
@php
  $contentData = isset($block) ? $block->getTranslations('content') : [];
  $textValues = [];
  $buttonTextValues = [];
  $buttonLinkValues = [];
  
  // Extract values from content JSON
  foreach (config('laravellocalization.supportedLocales', ['es' => []]) as $locale => $localeData) {
    $textValues[$locale] = old("content.text.{$locale}", $contentData[$locale]['text'] ?? '');
    $buttonTextValues[$locale] = old("content.button_text.{$locale}", $contentData[$locale]['button_text'] ?? '');
    $buttonLinkValues[$locale] = old("content.button_link.{$locale}", $contentData[$locale]['button_link'] ?? '');
  }
@endphp

<x-form.multilingual-wysiwyg
  name="content[text]"
  :label="__('pages.blocks.cta_text')"
  :values="$textValues"
  type="textarea"
/>

<x-form.multilingual-input
  name="content[button_text]"
  :label="__('pages.blocks.cta_button_text')"
  :values="$buttonTextValues"
  required
/>

<x-form.multilingual-input
  name="content[button_link]"
  :label="__('pages.blocks.cta_button_link')"
  :values="$buttonLinkValues"
  required
/>
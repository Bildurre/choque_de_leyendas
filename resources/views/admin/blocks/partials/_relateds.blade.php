@php
  $contentData = isset($block) ? $block->getTranslations('content') : [];
  $buttonTextValues = [];
  
  // Extract button text values from content
  foreach (config('laravellocalization.supportedLocales', ['es' => []]) as $locale => $localeData) {
    $buttonTextValues[$locale] = old("content.button_text.{$locale}", $contentData[$locale]['button_text'] ?? '');
  }
@endphp

<x-form.fieldset :legend="__('pages.blocks.fields.block_fields', ['type' => $blockName])">
  <x-form.multilingual-input
    name="content[button_text]"
    :label="__('pages.blocks.settings.button_text')"
    :values="$buttonTextValues"
    :help="__('pages.blocks.relateds_button_help')"
  />
</x-fomr.fieldset>
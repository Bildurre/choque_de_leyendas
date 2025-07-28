<x-form.fieldset :legend="__('pages.blocks.fields.block_fields', ['type' => $blockName])"> 
  <x-form.multilingual-wysiwyg
    name="content"
    :label="__('pages.blocks.content')"
    :values="old('content', isset($block) ? $block->getTranslations('content') : [])"
    required
  />
</x-form.fieldset>
<x-form.multilingual-wysiwyg
  name="content"
  :label="__('pages.blocks.content')"
  :values="old('content', isset($block) ? $block->getTranslations('content') : [])"
  required
/>
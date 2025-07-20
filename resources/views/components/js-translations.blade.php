{{-- resources/views/components/js-translations.blade.php --}}
<script>
  window.translations = window.translations || {};
  
  // Forms translations
  window.translations.forms = {
    choices: {
      noResults: @json(__('forms.choices.no_results')),
      noChoices: @json(__('forms.choices.no_choices')),
      itemSelect: @json(__('forms.choices.item_select')),
      placeholder: @json(__('forms.choices.placeholder')),
      searchPlaceholder: @json(__('forms.choices.search_placeholder')),
      removeItem: @json(__('forms.choices.remove_item'))
    }
  };
  
  // PDF translations
  window.translations.pdf = {
    download: {
      button_title: @json(__('pdf.download.button_title')),
      not_available: @json(__('pdf.download.not_available')),
      success: @json(__('pdf.download.success')),
      error: @json(__('pdf.download.error'))
    },
    collection: {
      add_button_title: @json(__('pdf.collection.add_button_title')),
      added_successfully: @json(__('pdf.collection.added_successfully')),
      removed_successfully: @json(__('pdf.collection.removed_successfully')),
      updated_successfully: @json(__('pdf.collection.updated_successfully')),
      cleared_successfully: @json(__('pdf.collection.cleared_successfully')),
      empty_collection: @json(__('pdf.collection.empty_collection')),
      add_hint: @json(__('pdf.collection.add_hint')),
      copies: @json(__('pdf.collection.copies')),
      remove_from_collection: @json(__('pdf.collection.remove_from_collection')),
      your_pdfs: @json(__('pdf.collection.your_pdfs')),
      temporary_description: @json(__('pdf.collection.temporary_description')),
      generating: @json(__('pdf.collection.generating')),
      generation_complete: @json(__('pdf.collection.generation_complete')),
      generation_failed: @json(__('pdf.collection.generation_failed'))
    },
    confirm_delete: @json(__('pdf.confirm_delete'))
  };
  
  // Entity translations
  window.translations.entities = {
    faction: @json(__('entities.faction')),
    deck: @json(__('entities.deck')),
    card: @json(__('entities.card')),
    hero: @json(__('entities.hero'))
  };
  
  // Notification translations
  window.translations.notification = {
    close: @json(__('common.close'))
  };
</script>
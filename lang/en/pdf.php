<?php

return [
  // Meta
  'downloads_page_title' => 'Downloads - Alanda: Clash of Legends',
  'downloads_page_description' => 'Download official Alanda PDFs. Access game rules, reference sheets, preset decks and all the documentation needed to play.',

  // Titles and names
  'plural' => 'PDFs',
  'singular' => 'PDF',
  'export_management' => 'PDF Export Management',
  'other' => 'Other',
  'others' => 'Others',
  
  // States
  'generated' => 'PDF generated',
  'not_generated' => 'PDF not generated',
  'processing' => 'Generating PDF...',
  'no_pdfs_found' => 'No PDFs found',
  
  // Actions
  'generate' => 'Generate',
  'regenerate' => 'Regenerate',
  'view' => 'View',
  'delete' => 'Delete',
  'download' => 'Download',
  
  // Confirmations
  'confirm_regenerate' => 'Are you sure you want to regenerate this PDF? The current file will be replaced.',
  'confirm_delete' => 'Are you sure you want to delete this PDF?',
  'confirm_cleanup' => 'Are you sure you want to delete all temporary PDFs?',
  
  // Success/error messages
  'generation_started' => 'PDF generation for :name has started. One PDF will be created for each language.',
  'generation_failed' => 'Failed to start PDF generation.',
  'deleted_successfully' => 'PDF deleted successfully.',
  'deletion_failed' => 'Failed to delete PDF.',
  'cleanup_completed' => ':count temporary PDF files deleted successfully.',
  'cleanup_failed' => 'Failed to delete temporary PDF files.',
  
  // PDF types
  'types' => [
    'rules' => 'Game Rules',
    'rules_description' => 'PDF with all game rules',
    'counters' => 'Game Counters',
    'counters_description' => 'PDF with all printable game counters',
    'tokens' => 'Counters',
  ],
  
  // Specific counters
  'counters' => 'Counters',
  'counters_list' => 'Counters List',
  'counters_pdf_description' => 'Generate counter list PDFs for all languages',
  'cut_out_counters' => 'Cut-out Counters',
  'cut_out_counters_description' => 'Generate counter PDFs for printing and cutting',
  
  // Visualization
  'view_counters_list' => 'View counters list',
  'view_counters_printable' => 'View printable counters',
  
  // List messages
  'no_boon_counters' => 'No published boon counters',
  'no_bane_counters' => 'No published bane counters',
  
  // Generated files
  'generated_files' => 'Generated files',
  
  // Cleanup
  'cleanup_temporary' => 'Delete temporary PDFs',
  
  // Temporary collection
  'collection' => [
    'add_button_title' => 'Add to collection',
    'title' => 'PDF Library',
    'description' => 'Download official PDFs or create your own custom collection',
    'temporary_collection' => 'Your Collection',
    'empty_collection' => 'Your collection is empty',
    'add_hint' => 'Add heroes and cards from faction pages',
    'clear' => 'Clear collection',
    'generate_pdf' => 'Generate PDF',
    'custom_collection' => 'Custom Collection',
    'your_pdfs' => 'Your Generated PDFs',
    'temporary_description' => 'These PDFs are temporary and will be deleted after 24 hours',
    'other_documents' => 'Other Documents',
    'no_available' => 'No PDFs available at this time',
    
    // Messages
    'added_successfully' => 'Added to collection',
    'removed_successfully' => 'Removed from collection',
    'updated_successfully' => 'Copies updated',
    'cleared_successfully' => 'Collection cleared',
    'generated_successfully' => 'PDF generated successfully',
    'no_items' => 'No items in collection',
    'max_items_exceeded' => 'You have reached the maximum limit of :max cards in the collection',
    
    // Progress messages
    'generating' => 'Generating...',
    'generating_pdf' => 'Generating PDF',
    'please_wait' => 'Please wait while we generate your PDF...',
    'generation_warning' => 'Please do not reload the page while the PDF is being generated',
    'generation_complete' => 'PDF generated successfully!',
    'pdf_added_to_list' => 'The PDF has been added to the list above',
    'generation_failed' => 'Failed to generate PDF',
    'close' => 'Close',
    
    // Form elements
    'copies' => 'Copies',
    'remove_from_collection' => 'Remove from collection',
    
    // Errors
    'add_failed' => 'Failed to add to collection',
    'remove_failed' => 'Failed to remove from collection',
    'update_failed' => 'Failed to update copies',
    
    // Confirmations
    'confirm_clear' => 'Are you sure you want to clear the entire collection?',
  ],
  
  // View state
  'wrong_locale_view' => 'This PDF was generated in another language',

  'download' => [
    'button_title' => 'Download PDF',
    'not_available' => 'PDF not available',
    'success' => ':type :name downloaded successfully',
    'error' => 'Error downloading PDF',
  ],
];
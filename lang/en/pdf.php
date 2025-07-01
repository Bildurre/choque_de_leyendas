<?php

return [
    // Titles and names
    'plural' => 'PDFs',
    'singular' => 'PDF',
    'export_management' => 'PDF Export Management',
    'other' => 'Others',
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
        'tokens' => 'Game Tokens',
        'tokens_description' => 'PDF with all printable tokens',
    ],
    
    // Cleanup
    'cleanup_temporary' => 'Delete temporary PDFs',
    
    // Public downloads page
    'collection' => [
        'title' => 'Download Center',
        'description' => 'Download all available game PDFs',
        'other_documents' => 'Other documents',
        'your_pdfs' => 'Your temporary PDFs',
        'temporary_description' => 'These files are temporary and will be automatically deleted after 24 hours.',
        'no_available' => 'No PDFs available at this time.',
    ],
];
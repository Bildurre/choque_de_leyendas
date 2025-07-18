<?php

return [
  // Meta
  'downloads_page_title' => 'Descargas - Alanda: Choque de Leyendas',
  'downloads_page_description' => 'Descarga los PDFs oficiales de Alanda. Accede a las reglas del juego, hojas de referencia, mazos predeterminados y toda la documentación necesaria para jugar.',

  // Titles and names
  'plural' => 'PDFs',
  'singular' => 'PDF',
  'export_management' => 'Gestión de Exportación PDF',
  'other' => 'Otro',
  'others' => 'Otros',
  
  // States
  'generated' => 'PDF generado',
  'not_generated' => 'PDF no generado',
  'processing' => 'Generando PDF...',
  'no_pdfs_found' => 'No se encontraron PDFs',
  
  // Actions
  'generate' => 'Generar',
  'regenerate' => 'Regenerar',
  'view' => 'Ver',
  'delete' => 'Eliminar',
  'download' => 'Descargar',
  
  // Confirmations
  'confirm_regenerate' => '¿Estás seguro de que quieres regenerar este PDF? El archivo actual será reemplazado.',
  'confirm_delete' => '¿Estás seguro de que quieres eliminar este PDF?',
  'confirm_cleanup' => '¿Estás seguro de que quieres eliminar todos los PDFs temporales?',
  
  // Success/error messages
  'generation_started' => 'La generación de PDF para :name ha comenzado. Se creará un PDF para cada idioma.',
  'generation_failed' => 'Error al iniciar la generación del PDF.',
  'deleted_successfully' => 'PDF eliminado correctamente.',
  'deletion_failed' => 'Error al eliminar el PDF.',
  'cleanup_completed' => ':count archivos PDF temporales eliminados correctamente.',
  'cleanup_failed' => 'Error al eliminar los archivos PDF temporales.',
  
  // PDF types
  'types' => [
    'rules' => 'Reglas del Juego',
    'rules_description' => 'PDF con todas las reglas del juego',
    'counters' => 'Contadores del Juego',
    'counters_description' => 'PDF con todos los contadores imprimibles del juego',
    'tokens' => 'Contadores',
  ],
  
  // Specific counters
  'counters' => 'Contadores',
  'counters_list' => 'Lista de Contadores',
  'counters_pdf_description' => 'Generar PDFs de lista de contadores para todos los idiomas',
  'cut_out_counters' => 'Contadores Recortables',
  'cut_out_counters_description' => 'Generar PDFs de contadores para imprimir y recortar',
  
  // Visualization
  'view_counters_list' => 'Ver lista de contadores',
  'view_counters_printable' => 'Ver contadores imprimibles',
  
  // List messages
  'no_boon_counters' => 'No hay contadores de beneficio publicados',
  'no_bane_counters' => 'No hay contadores de perjuicio publicados',
  
  // Generated files
  'generated_files' => 'Archivos generados',
  
  // Cleanup
  'cleanup_temporary' => 'Eliminar PDFs temporales',
  
  // Temporary collection
  'collection' => [
    'add_button_title' => 'Añadir a la colección',
    'title' => 'Biblioteca de PDFs',
    'description' => 'Descarga PDFs oficiales o crea tu propia colección personalizada',
    'temporary_collection' => 'Tu Colección Temporal',
    'empty_collection' => 'Tu colección está vacía',
    'add_hint' => 'Añade héroes y cartas desde las páginas de facciones',
    'clear' => 'Limpiar colección',
    'generate_pdf' => 'Generar PDF',
    'custom_collection' => 'Colección Personalizada',
    'your_pdfs' => 'Tus PDFs Generados',
    'temporary_description' => 'Estos PDFs son temporales y se eliminarán después de 24 horas',
    'other_documents' => 'Otros Documentos',
    'no_available' => 'No hay PDFs disponibles en este momento',
    
    // Messages
    'added_successfully' => 'Añadido a la colección',
    'removed_successfully' => 'Eliminado de la colección',
    'updated_successfully' => 'Copias actualizadas',
    'cleared_successfully' => 'Colección limpiada',
    'generated_successfully' => 'PDF generado correctamente',
    'no_items' => 'No hay elementos en la colección',
    'max_items_exceeded' => 'Has alcanzado el límite máximo de :max cartas en la colección',
    
    // Progress messages
    'generating' => 'Generando...',
    'generating_pdf' => 'Generando PDF',
    'please_wait' => 'Por favor espera mientras generamos tu PDF...',
    'generation_warning' => 'Por favor no recargues la página mientras se genera el PDF',
    'generation_complete' => '¡PDF generado correctamente!',
    'pdf_added_to_list' => 'El PDF ha sido añadido a la lista superior',
    'generation_failed' => 'Error al generar el PDF',
    'close' => 'Cerrar',
    
    // Form elements
    'copies' => 'Copias',
    'remove_from_collection' => 'Quitar de la colección',
    
    // Errors
    'add_failed' => 'No se pudo añadir a la colección',
    'remove_failed' => 'No se pudo eliminar de la colección',
    'update_failed' => 'No se pudo actualizar las copias',
    
    // Confirmations
    'confirm_clear' => '¿Estás seguro de que quieres limpiar toda la colección?',
  ],
  
  // View state
  'wrong_locale_view' => 'Este PDF fue generado en otro idioma',
];
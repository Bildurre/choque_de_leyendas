<?php

return [
  // Meta
  'downloads_page_title' => 'Descargas - Alanda: Choque de Leyendas',
  'downloads_page_description' => 'Descarga PDFs oficiales de Alanda. Accede a las reglas del juego, hojas de referencia, mazos predeterminados y toda la documentación necesaria para jugar.',

  // Títulos y nombres
  'plural' => 'PDFs',
  'singular' => 'PDF',
  'export_management' => 'Gestión de exportación PDF',
  'other' => 'Otros',
  'others' => 'Otros',
  
  // Estados
  'generated' => 'PDF generado',
  'not_generated' => 'PDF no generado',
  'processing' => 'Generando PDF...',
  'no_pdfs_found' => 'No se encontraron PDFs',
  
  // Acciones
  'generate' => 'Generar',
  'regenerate' => 'Regenerar',
  'view' => 'Ver',
  'delete' => 'Eliminar',
  'download' => 'Descargar',
  
  // Confirmaciones
  'confirm_regenerate' => '¿Estás seguro de que quieres regenerar este PDF? El archivo actual será reemplazado.',
  'confirm_delete' => '¿Estás seguro de que quieres eliminar este PDF?',
  'confirm_cleanup' => '¿Estás seguro de que quieres eliminar todos los PDFs temporales?',
  
  // Mensajes de éxito/error
  'generation_started' => 'La generación del PDF de :name ha comenzado. Se creará un PDF por cada idioma.',
  'generation_failed' => 'Error al iniciar la generación del PDF.',
  'deleted_successfully' => 'PDF eliminado correctamente.',
  'deletion_failed' => 'Error al eliminar el PDF.',
  'cleanup_completed' => ':count archivos PDF temporales eliminados correctamente.',
  'cleanup_failed' => 'Error al eliminar archivos PDF temporales.',
  
  // Tipos de PDF
  'types' => [
    'rules' => 'Reglas del juego',
    'rules_description' => 'PDF con todas las reglas del juego',
    'counters' => 'Fichas del juego',
    'counters_description' => 'PDF con todas las fichas imprimibles del juego',
    'tokens' => 'Contadores',
  ],
  
  // Counters específicos
  'counters' => 'Contadores',
  'counters_list' => 'Lista de contadores',
  'counters_pdf_description' => 'Genera los PDFs de lista de contadores para todos los idiomas',
  'cut_out_counters' => 'Contadores recortables',
  'cut_out_counters_description' => 'Genera los PDFs de contadores para imprimir y recortar',
  
  // Visualización
  'view_counters_list' => 'Ver lista de contadores',
  'view_counters_printable' => 'Ver contadores imprimibles',
  
  // Mensajes para lista
  'no_boon_counters' => 'No hay contadores de tipo boon publicados',
  'no_bane_counters' => 'No hay contadores de tipo bane publicados',
  
  // Archivos generados
  'generated_files' => 'Archivos generados',
  
  // Limpieza
  'cleanup_temporary' => 'Eliminar PDFs temporales',
  
  // Colección temporal
  'collection' => [
    'add_button_title' => 'Añadir a la colección',
    'title' => 'Biblioteca de PDFs',
    'description' => 'Descarga los PDFs oficiales o crea tu propia colección personalizada',
    'temporary_collection' => 'Tu Colección Temporal',
    'empty_collection' => 'Tu colección está vacía',
    'add_hint' => 'Añade héroes y cartas desde las páginas de facciones',
    'clear' => 'Limpiar colección',
    'generate_pdf' => 'Generar PDF',
    'custom_collection' => 'Colección Personalizada',
    'your_pdfs' => 'Tus PDFs Generados',
    'temporary_description' => 'Estos PDFs son temporales y se eliminarán después de 24 horas',
    'other_documents' => 'Otros Documentos',
    'no_available' => 'No hay PDFs disponibles actualmente',
    
    // Mensajes
    'added_successfully' => 'Añadido a la colección',
    'removed_successfully' => 'Eliminado de la colección',
    'updated_successfully' => 'Copias actualizadas',
    'cleared_successfully' => 'Colección limpiada',
    'generation_started' => 'Generando PDF... Actualiza la página en unos segundos',
    'no_items' => 'No hay elementos en la colección',
    
    // Errores
    'add_failed' => 'Error al añadir a la colección',
    'remove_failed' => 'Error al eliminar de la colección',
    'update_failed' => 'Error al actualizar las copias',
    
    // Confirmaciones
    'confirm_clear' => '¿Estás seguro de que quieres limpiar toda la colección?',
  ],
  
  // Estado de visualización
  'wrong_locale_view' => 'Este PDF fue generado en otro idioma',
];
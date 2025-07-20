<?php

return [
  // Meta
  'downloads_page_title' => 'Descargas - Alanda: Choque de Leyendas',
  'downloads_page_description' => 'Descarga los PDFs oficiales de Alanda. Accede a las reglas del juego, hojas de referencia, mazos predeterminados y toda la documentación necesaria para jugar.',

  // Títulos y nombres
  'plural' => 'PDFs',
  'singular' => 'PDF',
  'export_management' => 'Gestión de Exportación de PDF',
  'other' => 'Otro',
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
  'generation_started' => 'La generación del PDF para :name ha comenzado. Se creará un PDF para cada idioma.',
  'generation_failed' => 'Error al iniciar la generación del PDF.',
  'deleted_successfully' => 'PDF eliminado correctamente.',
  'deletion_failed' => 'Error al eliminar el PDF.',
  'cleanup_completed' => ':count archivos PDF temporales eliminados correctamente.',
  'cleanup_failed' => 'Error al eliminar los archivos PDF temporales.',
  
  // Tipos de PDF
  'types' => [
    'rules' => 'Reglas del Juego',
    'rules_description' => 'PDF con todas las reglas del juego',
    'counters' => 'Contadores del Juego',
    'counters_description' => 'PDF con todos los contadores imprimibles del juego',
    'tokens' => 'Contadores',
  ],
  
  // Contadores específicos
  'counters' => 'Contadores',
  'counters_list' => 'Lista de Contadores',
  'counters_pdf_description' => 'Generar PDFs de lista de contadores para todos los idiomas',
  'cut_out_counters' => 'Contadores Recortables',
  'cut_out_counters_description' => 'Generar PDFs de contadores para imprimir y recortar',
  
  // Visualización
  'view_counters_list' => 'Ver lista de contadores',
  'view_counters_printable' => 'Ver contadores imprimibles',
  
  // Mensajes de lista
  'no_boon_counters' => 'No hay contadores de beneficio publicados',
  'no_bane_counters' => 'No hay contadores de perjuicio publicados',
  
  // Archivos generados
  'generated_files' => 'Archivos generados',
  
  // Limpieza
  'cleanup_temporary' => 'Eliminar PDFs temporales',
  
  // Colección temporal
  'collection' => [
    'add_button_title' => 'Añadir a la colección',
    'title' => 'Biblioteca de PDFs',
    'description' => 'Descarga PDFs oficiales o crea tu propia colección personalizada',
    'temporary_collection' => 'Tu Colección',
    'empty_collection' => 'Tu colección está vacía',
    'add_hint' => 'Añade héroes y cartas desde las páginas de facción',
    'clear' => 'Vaciar colección',
    'generate_pdf' => 'Generar PDF',
    'custom_collection' => 'Colección Personalizada',
    'your_pdfs' => 'Tus PDFs Generados',
    'temporary_description' => 'Estos PDFs son temporales y se eliminarán después de 24 horas',
    'other_documents' => 'Otros Documentos',
    'no_available' => 'No hay PDFs disponibles en este momento',
    
    // Mensajes
    'added_successfully' => 'Añadido a la colección',
    'removed_successfully' => 'Eliminado de la colección',
    'updated_successfully' => 'Copias actualizadas',
    'cleared_successfully' => 'Colección vaciada',
    'generated_successfully' => 'PDF generado correctamente',
    'no_items' => 'No hay elementos en la colección',
    'max_items_exceeded' => 'Has alcanzado el límite máximo de :max cartas en la colección',
    
    // Mensajes de progreso
    'generating' => 'Generando...',
    'generating_pdf' => 'Generando PDF',
    'please_wait' => 'Por favor espera mientras generamos tu PDF...',
    'generation_warning' => 'Por favor no recargues la página mientras se genera el PDF',
    'generation_complete' => '¡PDF generado correctamente!',
    'pdf_added_to_list' => 'El PDF ha sido añadido a la lista de arriba',
    'generation_failed' => 'Error al generar el PDF',
    'close' => 'Cerrar',
    
    // Elementos de formulario
    'copies' => 'Copias',
    'remove_from_collection' => 'Eliminar de la colección',
    
    // Errores
    'add_failed' => 'Error al añadir a la colección',
    'remove_failed' => 'Error al eliminar de la colección',
    'update_failed' => 'Error al actualizar copias',
    
    // Confirmaciones
    'confirm_clear' => '¿Estás seguro de que quieres vaciar toda la colección?',
  ],
  
  // Estado de vista
  'wrong_locale_view' => 'Este PDF fue generado en otro idioma',
  
  // Nuevas claves para descarga de PDFs
  'download' => [
    'button_title' => 'Descargar PDF',
    'not_available' => 'PDF no disponible',
    'success' => ':type :name descargado correctamente',
    'error' => 'Error al descargar el PDF',
  ],
];
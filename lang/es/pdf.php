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
    
    // Página pública de descargas
    'collection' => [
        'title' => 'Centro de descargas',
        'description' => 'Descarga todos los PDF disponibles del juego',
        'other_documents' => 'Otros documentos',
        'your_pdfs' => 'Tus PDF temporales',
        'temporary_description' => 'Estos archivos son temporales y se eliminarán automáticamente después de 24 horas.',
        'no_available' => 'No hay PDF disponibles en este momento.',
    ],
];
<?php

return [
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
        'tokens' => 'Fichas del juego',
        'tokens_description' => 'PDF con todas las fichas imprimibles',
    ],
    
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
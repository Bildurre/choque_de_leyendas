<?php

return [
  // Páginas
  'database_title' => 'Exportar - Base de Datos',
  'menu_database_title' => 'Base de Datos',
  'json_title' => 'Exportar - JSON',
  'menu_json_title' => 'JSON',
  
  // Database - Información
  'database_information' => 'Información de la Base de Datos',
  'current_database' => 'Base de Datos Actual',
  'database_name' => 'Nombre',
  'table_count' => 'Número de Tablas',
  'total_size' => 'Tamaño Total',
  
  // Database - Exportar
  'database' => 'Exportar Base de Datos',
  'new_export' => 'Nueva Exportación',
  'include_data' => 'Incluir datos',
  'include_data_help' => 'Si no está marcado, solo se exportará la estructura de la base de datos',
  'compress_file' => 'Comprimir archivo (ZIP)',
  'compress_file_help' => 'Comprime el archivo SQL para reducir el tamaño de descarga',
  'export_button' => 'Exportar Base de Datos',
  
  // Database - Importar
  'database_restore' => 'Importar Base de Datos',
  'upload_backup' => 'Subir Copia de Seguridad',
  'upload_backup_help' => 'Sube un archivo SQL o ZIP que contenga un archivo SQL para poder restaurarlo. Una vez subido, aparecerá en la lista de copias disponibles.',
  'select_file' => 'Seleccionar archivo',
  'upload_button' => 'Subir Archivo',
  
  // Database - Lista de exportaciones
  'other_exports' => 'Copias de Seguridad Disponibles',
  'uploaded' => 'Subido',
  
  // JSON - Sección
  'json_exports_section' => 'Exportaciones JSON',
  'recent_exports' => 'Exportaciones Recientes',
  
  // JSON - Cartas
  'export_cards' => 'Exportar Cartas',
  'export_cards_help' => 'Exporta todas las cartas en formato JSON con todos sus datos y traducciones.',
  'export_cards_button' => 'Exportar Cartas',
  
  // JSON - Héroes
  'export_heroes' => 'Exportar Héroes',
  'export_heroes_help' => 'Exporta todos los héroes en formato JSON con todos sus datos y traducciones.',
  'export_heroes_button' => 'Exportar Héroes',
  
  // JSON - Contadores
  'export_counters' => 'Exportar Contadores',
  'export_counters_help' => 'Exporta todos los contadores en formato JSON con todos sus datos y traducciones.',
  'export_counters_button' => 'Exportar Contadores',
  
  // JSON - Clases
  'export_classes' => 'Exportar Clases',
  'export_classes_help' => 'Exporta todas las clases de héroe en formato JSON con todos sus datos y traducciones.',
  'export_classes_button' => 'Exportar Clases',
  
  // JSON - Facción Individual
  'export_faction' => 'Exportar Facción',
  'export_faction_help' => 'Selecciona una facción para exportar todos sus héroes y cartas en formato JSON.',
  'select_faction' => 'Seleccionar facción',
  'select_faction_placeholder' => 'Selecciona una facción',
  'export_faction_button' => 'Exportar Facción',
  
  // JSON - Todas las Facciones
  'export_all_factions' => 'Exportar Todas las Facciones',
  'export_all_factions_help' => 'Exporta todas las facciones en archivos JSON separados comprimidos en un ZIP.',
  'export_all_factions_button' => 'Exportar Todas las Facciones',
  
  // Acciones
  'download' => 'Descargar',
  'download_all' => 'Descargar Todas',
  'delete_all' => 'Borrar Todas',
  'restore' => 'Restaurar',
  
  // Confirmaciones
  'confirm_delete_single' => '¿Estás seguro de que quieres eliminar esta exportación?',
  'confirm_delete_all' => '¿Estás seguro de que quieres eliminar todas las exportaciones?',
  'restore_confirm' => '¿Estás seguro de que quieres restaurar la base de datos? Esta acción sobrescribirá todos los datos actuales y no se puede deshacer.',
  
  // Mensajes de éxito
  'deleted_single_success' => 'Exportación eliminada correctamente.',
  'deleted_all_success' => ':count exportación(es) eliminada(s) correctamente.',
  'upload_success' => 'Archivo :filename subido correctamente.',
  'restore_success_logout' => 'Base de datos restaurada correctamente. Por favor, inicia sesión nuevamente.',
  
  // Mensajes de error
  'export_error' => 'Error al exportar',
  'delete_error' => 'Error al eliminar la exportación.',
  'upload_error' => 'Error al subir el archivo',
  'restore_error' => 'Error al restaurar la base de datos',
  'download_error' => 'Error al descargar las exportaciones',
  'no_exports_to_download' => 'No hay exportaciones disponibles para descargar.',
  'invalid_zip_file' => 'El archivo no es un ZIP válido.',
  'invalid_sql_file' => 'El archivo no es un SQL válido.',
  'invalid_file_type' => 'Tipo de archivo no válido. Solo se permiten archivos .sql y .zip.',
];
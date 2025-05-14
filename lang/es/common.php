<?php

return [
    // Common terms
    'name' => 'Nombre',
    'description' => 'Descripción',
    'type' => 'Tipo',
    'image' => 'Imagen',
    'icon' => 'Icono',
    'status' => 'Estado',
    'actions' => 'Acciones',
    'details' => 'Detalles',
    'category' => 'Categoría',
    'created_at' => 'Creado el',
    'updated_at' => 'Actualizado el',
    'color' => 'Color',
    
    // Common statuses
    'active' => 'Activo',
    'inactive' => 'Inactivo',
    'draft' => 'Borrador',
    'published' => 'Publicado',
    
    // Common actions
    'search' => 'Buscar',
    'filter' => 'Filtrar',
    'apply' => 'Aplicar',
    'clear' => 'Limpiar',
    'export' => 'Exportar',
    'import' => 'Importar',
    'print' => 'Imprimir',
    'download' => 'Descargar',
    
    // Common messages
    'confirm' => '¿Estás seguro?',
    'success' => 'Operación completada con éxito.',
    'error' => 'Ha ocurrido un error.',
    'warning' => 'Advertencia',
    'info' => 'Información',

    // Common errors
    'errors' => [
      'create' => 'Ha ocurrido un error al crear el/la :entity: ',
      'update' => 'Ha ocurrido un error al actualizar el/la :entity: ',
      'delete' => 'Ha ocurrido un error al eliminar el/la :entity: ',
      'restore' => 'Ha ocurrido un error al restaurar el/la :entity: ',
      'force_delete' => 'Ha ocurrido un error al eliminar permanentemente el/la :entity: ',
    ],
    // Common successes
    'success' => [
      'created' => ':entity creado/a correctamente.',
      'updated' => ':entity actualizado/a correctamente.',
      'deleted' => ':entity eliminado/a correctamente.',
      'restored' => ':entity restaurado/a correctamente.',
      'force_deleted' => ':entity eliminado/a permanentemente con éxito.',
    ],
];
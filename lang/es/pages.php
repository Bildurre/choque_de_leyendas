<?php

return [
  // General
  'singular' => 'Página',
  'plural' => 'Páginas',
  'create' => 'Crear página',
  'edit' => 'Editar página',
  'form_title' => 'Información de la página',
  'all_pages' => 'Todas las páginas',
  'read_more' => 'Leer más',
  'no_pages' => 'No hay páginas disponibles',
  
  // Campos
  'title' => 'Título',
  'slug' => 'URL amigable',
  'description' => 'Descripción',
  'content' => 'Contenido',
  'template' => 'Plantilla',
  'parent' => 'Página padre',
  'no_parent' => 'Sin página padre',
  'order' => 'Orden',
  'published' => 'Publicada',
  'draft' => 'Borrador',
  'meta_info' => 'Información META',
  'meta_title' => 'Título META',
  'meta_description' => 'Descripción META',
  'background_image' => 'Imagen de fondo',
  
  // Mensajes
  'children_count' => ':count páginas hijas',
  'back_to_list' => 'Volver al listado',
  'confirm_delete' => '¿Estás seguro de que deseas eliminar esta página?',
  'confirm_force_delete' => '¿Estás seguro de que deseas eliminar permanentemente esta página?',
  'no_blocks' => 'Esta página no tiene bloques de contenido',
  
  // Bloques
  'blocks' => [
    'singular' => 'Bloque',
    'plural' => 'Bloques',
    'page_blocks' => 'Bloques de la página',
    'add_block' => 'Añadir bloque',
    'create' => 'Crear bloque :type',
    'edit' => 'Editar bloque :type',
    'form_title' => 'Configuración del bloque :block_name de la página :page_title',
    'title' => 'Título',
    'subtitle' => 'Subtítulo',
    'content' => 'Contenido',
    'image' => 'Imagen',
    'background_color' => 'Color de fondo',
    'appearance' => 'Apariencia',
    'image_position' => 'Posición de la imagen',
    'image_position_options' => [
      'left' => 'Izquierda',
      'right' => 'Derecha',
    ],
    'types' => [
      'text' => 'Texto',
      'header' => 'Encabezado',
      'relateds' => 'Contenido relacionado',
      'cta' => 'Llamada a la Acción'
    ],
    'settings' => [
      'full_width' => 'Ancho completo',
      'text_alignment' => 'Alineación del texto',
      'text_alignment_options' => [
        'left' => 'Izquierda',
        'center' => 'Centro',
        'right' => 'Derecha',
      ],
      'model_type' => 'Tipo de contenido',
      'model_type_options' => [
        'hero' => 'Héroes',
        'card' => 'Cartas',
        'faction' => 'Facciones',
      ],
      'display_type' => 'Tipo de visualización',
      'display_type_options' => [
        'latest' => 'Más recientes',
        'random' => 'Aleatorio',
      ],
      'button_text' => 'Texto del botón',
    ],
    'relateds' => [
      'view_all' => 'Ver todos',
    ],
    'confirm_delete' => '¿Estás seguro de que deseas eliminar este bloque?',
  ],
];
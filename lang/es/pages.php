<?php

return [
  // General
  'singular' => 'Página',
  'plural' => 'Páginas',
  'create' => 'Crear página',
  'edit' => 'Editar página',
  'edit_details' => 'Editar Detalles de Página',
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
  'printable' => 'Imprimible',
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

  // Página Home
  'home_page_settings' => 'Configuración de página de inicio',
  'select_home_page' => 'Seleccionar página de inicio',
  'no_home_page_selected' => 'Ninguna página seleccionada',
  'current_home_page' => 'Página de inicio actual: :title',
  'set_as_home' => 'Establecer',
  'set_as_home_success' => 'La página ":title" se ha establecido como página de inicio',
  'set_as_home_error' => 'Error al establecer la página de inicio',
  
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
      'top' => 'Encima',
      'bottom' => 'Debajo',
    ],
    'image_scale_mode' => 'Modo de Escalado',
    'image_scale_mode_options' => [
      'contain' => 'Contain',
      'cover' => 'Cover',
      'fill' => 'Fill',
    ],
    'column_proportions' => 'Columnas',
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
        'justify' => 'Justificar',
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
      'button_variant' => 'Variantes de Botón',
      'button_variant_options' => [
        'primary' => 'Primario',
        'secondary' => 'Secundario',
        'accent' => 'Acento',
      ],
      'button_size' => 'Tamaño de Botón',
      'button_size_options' => [
        'lg' => 'Largo',
        'md' => 'Medio',
        'sm' => 'Pequeño',
      ],
    ],
    'relateds' => [
      'view_all' => 'Ver todos',
    ],
    'confirm_delete' => '¿Estás seguro de que deseas eliminar este bloque?',
    'created_successfully' => 'Bloque creado con exito',
    'create_error' => 'Error al crear el bloque',
    'back_to_page' => 'Volver a la Página',
    'printable' => 'Imprimible',
    'cta_text' => 'Texto',
    'cta_button_text' => 'Texto del Botón',
    'cta_button_link' => 'Enlace',
  ],
];
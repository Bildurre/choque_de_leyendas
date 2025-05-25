<?php

return [
    // Pages
    'plural' => 'Páginas',
    'singular' => 'Página',
    'create' => 'Crear Página',
    'edit' => 'Editar Página',
    'edit_details' => 'Editar Detalles de Página',
    'form_title' => 'Información de la Página',
    'title' => 'Título',
    'slug' => 'Slug',
    'description' => 'Descripción',
    'template' => 'Plantilla',
    'parent' => 'Página Padre',
    'no_parent' => 'Sin Padre',
    'order' => 'Orden',
    'is_published' => 'Está Publicada',
    'published' => 'Publicada',
    'draft' => 'Borrador',
    'no_pages' => 'No hay páginas disponibles',
    'children_count' => ':count páginas hijas',
    'all_pages' => 'Todas las Páginas',
    'read_more' => 'Leer Más',
    
    // Blocks
    'blocks' => [
        'page_blocks' => 'Bloques de Página',
        'add_block' => 'Añadir Bloque',
        'create' => 'Crear Bloque',
        'edit' => 'Editar Bloque',
        'form_title' => 'Información del Bloque',
        'title' => 'Título',
        'subtitle' => 'Subtítulo',
        'content' => 'Contenido',
        'appearance' => 'Apariencia',
        'background_color' => 'Color de Fondo',
        'image' => 'Imagen',
        'image_position' => 'Posición de la Imagen',
        'image_position_options' => [
            'left' => 'Izquierda',
            'right' => 'Derecha',
        ],
        'settings' => [
            'text_alignment' => 'Alineación de Texto',
            'text_alignment_options' => [
                'left' => 'Izquierda',
                'center' => 'Centro',
                'right' => 'Derecha',
            ],
            'full_width' => 'Ancho Completo',
            // Configuraciones del bloque relateds
            'model_type' => 'Tipo de elemento',
            'display_type' => 'Tipo de visualización',
            'button_text' => 'Texto del botón',
            'model_types' => [
                'faction' => 'Facciones',
                'hero' => 'Héroes',
                'card' => 'Cartas',
            ],
            'display_types' => [
                'latest' => 'Últimos añadidos',
                'random' => 'Selección aleatoria',
            ],
        ],
        'confirm_delete' => '¿Estás seguro de que quieres eliminar este bloque?',
        'types' => [
            'text' => 'Bloque de Texto',
            'header' => 'Bloque de Cabecera',
            'relateds' => 'Elementos Relacionados',
            'image' => 'Bloque de Imagen',
            'gallery' => 'Bloque de Galería',
            'cards' => 'Bloque de Cartas',
            'heroes' => 'Bloque de Héroes',
        ],
        // Específicas del bloque relateds
        'relateds' => [
            'view_all' => 'Ver todos',
            'title_placeholder' => 'Elementos destacados',
            'subtitle_placeholder' => 'Descubre los últimos elementos añadidos',
        ],
    ],
    
    // Meta information
    'meta_info' => 'Información Meta',
    'meta_title' => 'Título Meta',
    'meta_description' => 'Descripción Meta',
    'background_image' => 'Imagen de Fondo',
    'no_blocks' => 'Esta página aún no tiene bloques de contenido.',
];
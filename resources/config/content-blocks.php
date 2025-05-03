<?php
return [
  'types' => [
    'text' => [
      'label' => 'Texto',
      'description' => 'Bloque de texto plano o con formato',
      'supports' => ['columns', 'alignment', 'styling', 'anchor'],
      'settings' => [
        'columns' => [
          'options' => ['1', '2'],
          'default' => '1',
          'label' => 'Número de columnas'
        ],
        'alignment' => [
          'options' => ['left', 'center', 'right', 'justify'],
          'default' => 'left',
          'label' => 'Alineación del texto'
        ],
        'text_size' => [
          'options' => ['sm', 'md', 'lg', 'xl'],
          'default' => 'md',
          'label' => 'Tamaño del texto'
        ],
        'styling' => [
          'options' => ['standard', 'quote', 'highlight', 'note'],
          'default' => 'standard',
          'label' => 'Estilo del texto'
        ]
      ]
    ],
    
    'image' => [
      'label' => 'Imagen',
      'description' => 'Imagen individual con opciones de estilo',
      'supports' => ['image_size', 'alignment', 'caption', 'anchor'],
      'settings' => [
        'size' => [
          'options' => ['sm', 'md', 'lg', 'xl', 'full'],
          'default' => 'lg',
          'label' => 'Tamaño de la imagen'
        ],
        'alignment' => [
          'options' => ['left', 'center', 'right'],
          'default' => 'center',
          'label' => 'Alineación'
        ],
        'border_radius' => [
          'options' => ['none', 'sm', 'md', 'lg', 'full'],
          'default' => 'none',
          'label' => 'Borde redondeado'
        ],
        'shadow' => [
          'options' => ['none', 'sm', 'md', 'lg'],
          'default' => 'none',
          'label' => 'Sombra'
        ]
      ]
    ],
    
    'text_image' => [
      'label' => 'Texto e Imagen',
      'description' => 'Texto combinado con imagen en diferentes disposiciones',
      'supports' => ['image_position', 'image_size', 'text_alignment', 'anchor'],
      'settings' => [
        'image_position' => [
          'options' => ['left', 'right', 'top', 'bottom'],
          'default' => 'left',
          'label' => 'Posición de la imagen'
        ],
        'distribution' => [
          'options' => ['30-70', '40-60', '50-50', '60-40', '70-30'],
          'default' => '50-50',
          'label' => 'Distribución de espacio'
        ],
        'image_fit' => [
          'options' => ['cover', 'contain', 'scale-down'],
          'default' => 'cover',
          'label' => 'Ajuste de imagen'
        ],
        'spacing' => [
          'options' => ['tight', 'normal', 'loose'],
          'default' => 'normal',
          'label' => 'Espaciado entre elementos'
        ]
      ]
    ],
    
    'list' => [
      'label' => 'Lista',
      'description' => 'Lista con diferentes estilos e iconos',
      'supports' => ['list_type', 'layout', 'styling', 'anchor'],
      'settings' => [
        'list_type' => [
          'options' => ['bullets', 'numbers', 'checks', 'icons', 'custom'],
          'default' => 'bullets',
          'label' => 'Tipo de lista'
        ],
        'layout' => [
          'options' => ['1_column', '2_columns', '3_columns'],
          'default' => '1_column',
          'label' => 'Disposición'
        ],
        'icon' => [
          'options' => ['arrow', 'check', 'star', 'dice', 'hero', 'card', 'faction'],
          'default' => 'arrow',
          'label' => 'Icono personalizado'
        ],
        'alignment' => [
          'options' => ['left', 'center'],
          'default' => 'left',
          'label' => 'Alineación'
        ]
      ]
    ],
    
    'list_image' => [
      'label' => 'Lista con Imagen',
      'description' => 'Lista combinada con imagen lateral',
      'supports' => ['image_position', 'list_type', 'layout', 'anchor'],
      'settings' => [
        'image_position' => [
          'options' => ['left', 'right'],
          'default' => 'left',
          'label' => 'Posición de la imagen'
        ],
        'list_type' => [
          'options' => ['bullets', 'numbers', 'checks', 'icons'],
          'default' => 'bullets',
          'label' => 'Tipo de lista'
        ],
        'image_size' => [
          'options' => ['sm', 'md', 'lg'],
          'default' => 'md',
          'label' => 'Tamaño de imagen'
        ]
      ]
    ],
    
    'table' => [
      'label' => 'Tabla',
      'description' => 'Tabla con encabezados y celdas',
      'supports' => ['headers', 'striped', 'borders', 'anchor'],
      'settings' => [
        'style' => [
          'options' => ['default', 'striped', 'bordered', 'borderless', 'dark'],
          'default' => 'default',
          'label' => 'Estilo de tabla'
        ],
        'size' => [
          'options' => ['sm', 'md', 'lg'],
          'default' => 'md',
          'label' => 'Tamaño'
        ],
        'responsive' => [
          'options' => ['true', 'false'],
          'default' => 'true',
          'label' => 'Responsiva'
        ]
      ]
    ],
    
    'model_list' => [
      'label' => 'Lista de Modelos',
      'description' => 'Lista dinámica de héroes, cartas, contadores, etc.',
      'supports' => ['model_type', 'filters', 'display_options', 'anchor'],
      'settings' => [
        'display_type' => [
          'options' => ['grid', 'list', 'cards'],
          'default' => 'grid',
          'label' => 'Tipo de visualización'
        ],
        'columns' => [
          'options' => ['2', '3', '4', '6'],
          'default' => '3',
          'label' => 'Columnas (para grid/cards)'
        ],
        'show_image' => [
          'options' => ['true', 'false'],
          'default' => 'true',
          'label' => 'Mostrar imagen'
        ],
        'show_description' => [
          'options' => ['true', 'false'],
          'default' => 'false',
          'label' => 'Mostrar descripción'
        ],
        'limit' => [
          'default' => '10',
          'label' => 'Límite de elementos'
        ]
      ],
      'model_types' => [
        'heroes' => 'Héroes',
        'cards' => 'Cartas',
        'factions' => 'Facciones',
        'hero_abilities' => 'Habilidades de Héroe',
        'attack_ranges' => 'Rangos de Ataque',
        'attack_subtypes' => 'Subtipos de Ataque',
        'equipment_types' => 'Tipos de Equipo',
        'card_types' => 'Tipos de Carta',
        'hero_races' => 'Razas',
        'hero_classes' => 'Clases',
        'hero_superclasses' => 'Superclases'
      ]
    ],
    
    'call_to_action' => [
      'label' => 'Call to Action',
      'description' => 'Sección destacada con llamada a la acción',
      'supports' => ['button', 'image', 'background', 'anchor'],
      'settings' => [
        'style' => [
          'options' => ['minimal', 'card', 'banner', 'fullwidth'],
          'default' => 'card',
          'label' => 'Estilo del CTA'
        ],
        'background_type' => [
          'options' => ['color', 'image', 'gradient'],
          'default' => 'color',
          'label' => 'Tipo de fondo'
        ],
        'image_position' => [
          'options' => ['left', 'right', 'background'],
          'default' => 'right',
          'label' => 'Posición de imagen'
        ],
        'button_style' => [
          'options' => ['primary', 'secondary', 'outline'],
          'default' => 'primary',
          'label' => 'Estilo del botón'
        ]
      ]
    ],
    
    'index' => [
      'label' => 'Índice',
      'description' => 'Índice automático de la página',
      'supports' => ['depth', 'styling', 'anchor'],
      'settings' => [
        'depth' => [
          'options' => ['sections', 'sections_and_blocks'],
          'default' => 'sections',
          'label' => 'Profundidad del índice'
        ],
        'style' => [
          'options' => ['list', 'grid', 'tree'],
          'default' => 'list',
          'label' => 'Estilo de visualización'
        ],
        'numbering' => [
          'options' => ['none', 'numeric', 'alpha'],
          'default' => 'numeric',
          'label' => 'Sistema de numeración'
        ]
      ]
    ],
    
    'divider' => [
      'label' => 'Divisor',
      'description' => 'Línea o espacio de separación',
      'supports' => ['style', 'spacing'],
      'settings' => [
        'type' => [
          'options' => ['line', 'space', 'decorative'],
          'default' => 'line',
          'label' => 'Tipo de divisor'
        ],
        'style' => [
          'options' => ['solid', 'dashed', 'dotted', 'double'],
          'default' => 'solid',
          'label' => 'Estilo de línea'
        ],
        'thickness' => [
          'options' => ['thin', 'medium', 'thick'],
          'default' => 'medium',
          'label' => 'Grosor'
        ],
        'spacing' => [
          'options' => ['sm', 'md', 'lg', 'xl'],
          'default' => 'md',
          'label' => 'Espaciado vertical'
        ]
      ]
    ],
    
    'quote' => [
      'label' => 'Cita',
      'description' => 'Bloque de cita destacada',
      'supports' => ['author', 'styling', 'anchor'],
      'settings' => [
        'style' => [
          'options' => ['standard', 'boxed', 'minimal', 'decorative'],
          'default' => 'standard',
          'label' => 'Estilo de cita'
        ],
        'alignment' => [
          'options' => ['left', 'center', 'right'],
          'default' => 'left',
          'label' => 'Alineación'
        ],
        'size' => [
          'options' => ['sm', 'md', 'lg', 'xl'],
          'default' => 'md',
          'label' => 'Tamaño de fuente'
        ]
      ]
    ]
  ]
];
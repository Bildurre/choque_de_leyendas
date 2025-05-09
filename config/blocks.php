<?php

return [
  'types' => [
    'text' => [
      'name' => 'Text Block',
      'view' => 'content.blocks.text',
      'icon' => 'text',
      'allows_image' => true,
      'settings' => [
        'full_width' => [
          'type' => 'boolean',
          'default' => false,
        ],
        'text_alignment' => [
          'type' => 'select',
          'default' => 'left',
          'options' => [
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
          ],
        ],
      ],
    ],
    'header' => [
      'name' => 'Header Block',
      'view' => 'content.blocks.header',
      'icon' => 'heading',
      'allows_image' => false,
      'settings' => [
        'text_alignment' => [
          'type' => 'select',
          'default' => 'center',
          'options' => [
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right',
          ],
        ],
      ],
    ],
      // Podemos añadir más tipos después
  ],

  'background_colors' => [
    'none' => 'Sin color de fondo',
    'red' => 'Rojo',
    'orange' => 'Naranja',
    'lime' => 'Lima',
    'green' => 'Verde',
    'teal' => 'Turquesa',
    'cyan' => 'Cian',
    'blue' => 'Azul',
    'purple' => 'Morado',
    'magenta' => 'Magenta'
  ]
];
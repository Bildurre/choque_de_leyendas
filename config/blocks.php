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
        'image_position' => [
          'type' => 'select',
          'options' => ['top', 'left', 'right'],
          'default' => 'top'
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
    'primary' => 'Color primario',
    'secondary' => 'Color secundario',
    'tertiary' => 'Color terciario',
    'light' => 'Claro',
    'dark' => 'Oscuro',
    'accent' => 'Acento'
  ]
];
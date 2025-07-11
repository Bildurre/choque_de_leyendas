<?php

return [
  'types' => [
    'text' => [
      'name' => 'Text Block',
      'view' => 'content.blocks.text',
      'icon' => 'text',
      'allows_image' => true,
      'allows_clearfix_image' => true,
      'settings' => [
      ],
    ],
    'header' => [
      'name' => 'Header Block',
      'view' => 'content.blocks.header',
      'icon' => 'heading',
      'allows_image' => false,
      'settings' => [
      ],
    ],
    'relateds' => [
      'name' => 'Related Items Block',
      'view' => 'content.blocks.relateds',
      'icon' => 'dashboard',
      'allows_image' => false,
      'settings' => [
        'model_type' => [
          'type' => 'select',
          'default' => 'hero',
          'options' => [
            'faction' => 'Factions',
            'hero' => 'Heroes',
            'card' => 'Cards',
          ],
        ],
        'display_type' => [
          'type' => 'select',
          'default' => 'latest',
          'options' => [
            'latest' => 'Latest Added',
            'random' => 'Random Selection',
          ],
        ],
        'button_variant' => [
          'type' => 'select',
          'default' => 'primary',
          'options' => [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
          ],
        ],
        'button_size' => [
          'type' => 'select',
          'default' => 'lg',
          'options' => [
            'sm' => 'Small',
            'md' => 'Medium',
            'lg' => 'Large',
          ],
        ],
      ],
    ],
    'cta' => [
      'name' => 'CTA Block',
      'view' => 'content.blocks.cta',
      'icon' => 'link',
      'allows_image' => true,
      'settings' => [
        'button_variant' => [
          'type' => 'select',
          'default' => 'primary',
          'options' => [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
          ],
        ],
        'button_size' => [
          'type' => 'select',
          'default' => 'lg',
          'options' => [
            'sm' => 'Small',
            'md' => 'Medium',
            'lg' => 'Large',
          ],
        ],
        'width' => [
          'type' => 'select',
          'default' => 'lg',
          'options' => [
            'sm' => 'Small',
            'md' => 'Medium',
            'lg' => 'Large',
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
    'magenta' => 'Magenta',
    'accent-primary' => 'Color de acento primario',
    'accent-secondary' => 'Color de acento secundario',
    'accent-tertiary' => 'Color de acento terciario',
    'theme-card' => 'Fondo de tarjeta del tema',
    'theme-border' => 'Borde del tema',
    'theme-header' => 'Fondo de cabecera del tema'
  ]
];
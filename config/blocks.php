<?php

return [
    'types' => [
        'text' => [
            'name' => 'Text Block',
            'view' => 'content.blocks.text',
            'icon' => 'text',
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
];
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
                'overlay_opacity' => [
                    'type' => 'select',
                    'default' => '0.5',
                    'options' => [
                        '0' => 'None',
                        '0.3' => 'Light',
                        '0.5' => 'Medium',
                        '0.7' => 'Strong',
                        '0.9' => 'Very Strong',
                    ],
                ],
            ],
        ],
        // Podemos añadir más tipos después
    ],
];
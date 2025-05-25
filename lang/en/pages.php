<?php

return [
    // Pages
    'plural' => 'Pages',
    'singular' => 'Page',
    'create' => 'Create Page',
    'edit' => 'Edit Page',
    'edit_details' => 'Edit Page Details',
    'form_title' => 'Page Information',
    'title' => 'Title',
    'slug' => 'Slug',
    'description' => 'Description',
    'template' => 'Template',
    'parent' => 'Parent Page',
    'no_parent' => 'No Parent',
    'order' => 'Order',
    'is_published' => 'Is Published',
    'published' => 'Published',
    'draft' => 'Draft',
    'no_pages' => 'No pages available',
    'children_count' => ':count child pages',
    'all_pages' => 'All Pages',
    'read_more' => 'Read More',
    
    // Blocks
    'blocks' => [
        'page_blocks' => 'Page Blocks',
        'add_block' => 'Add Block',
        'create' => 'Create Block',
        'edit' => 'Edit Block',
        'form_title' => 'Block Information',
        'title' => 'Title',
        'subtitle' => 'Subtitle',
        'content' => 'Content',
        'appearance' => 'Appearance',
        'background_color' => 'Background Color',
        'image' => 'Image',
        'image_position' => 'Image Position',
        'image_position_options' => [
            'left' => 'Left',
            'right' => 'Right',
        ],
        'settings' => [
            'text_alignment' => 'Text Alignment',
            'text_alignment_options' => [
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ],
            'full_width' => 'Full Width',
            // Relateds block settings
            'model_type' => 'Item Type',
            'display_type' => 'Display Type',
            'button_text' => 'Button Text',
            'model_types' => [
                'faction' => 'Factions',
                'hero' => 'Heroes',
                'card' => 'Cards',
            ],
            'display_types' => [
                'latest' => 'Latest Added',
                'random' => 'Random Selection',
            ],
        ],
        'confirm_delete' => 'Are you sure you want to delete this block?',
        'types' => [
            'text' => 'Text Block',
            'header' => 'Header Block',
            'relateds' => 'Related Items',
            'image' => 'Image Block',
            'gallery' => 'Gallery Block',
            'cards' => 'Cards Block',
            'heroes' => 'Heroes Block',
        ],
        // Relateds block specific
        'relateds' => [
            'view_all' => 'View All',
            'title_placeholder' => 'Featured Items',
            'subtitle_placeholder' => 'Discover the latest added items',
        ],
    ],
    
    // Meta information
    'meta_info' => 'Meta Information',
    'meta_title' => 'Meta Title',
    'meta_description' => 'Meta Description',
    'background_image' => 'Background Image',
    'no_blocks' => 'This page doesn\'t have content blocks yet.',
];
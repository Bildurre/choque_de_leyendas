<?php

return [
    // Factions
    'factions' => [
        'singular' => 'Faction',
        'plural' => 'Factions',
        'create' => 'Create Faction',
        'edit' => 'Edit Faction',
        'name' => 'Faction Name',
        'color' => 'Faction Color',
        'lore_text' => 'Lore Text',
        'icon' => 'Faction Icon',
        'heroes_count' => ':count heroes',
        'cards_count' => ':count cards',
        'text_is_dark' => 'Text Color',
        'text_dark' => 'Dark',
        'text_light' => 'Light',
        'no_icon' => 'No icon available',
        'no_heroes' => 'No heroes in this faction',
        'no_cards' => 'No cards in this faction',
        'no_decks' => 'No decks for this faction',
        'tabs' => [
            'details' => 'Details',
            'heroes' => 'Heroes',
            'cards' => 'Cards',
            'decks' => 'Decks',
        ],
        'errors' => [
            'has_heroes' => 'Cannot delete the faction because it has associated heroes.',
            'has_cards' => 'Cannot delete the faction because it has associated cards.',
            'force_delete_has_heroes' => 'Cannot permanently delete the faction because it has associated heroes.',
            'force_delete_has_cards' => 'Cannot permanently delete the faction because it has associated cards.',
        ],
    ],
    
    // Heroes
    'heroes' => [
        'singular' => 'Hero',
        'plural' => 'Heroes',
        'create' => 'Create Hero',
        'edit' => 'Edit Hero',
        'name' => 'Hero Name',
        'lore_text' => 'Lore Text',
        'image' => 'Hero Image',
        'no_image' => 'No image available',
        'no_faction' => 'No Faction',
        'count' => ':count heroes',
        'total_attributes' => 'Total Attributes',
        'gender' => 'Gender',
        'genders' => [
            'male' => 'Male',
            'female' => 'Female',
        ],
        'passive' => 'Passive',
        'passive_ability' => 'Passive Ability',
        'passive_name' => 'Passive Name',
        'passive_description' => 'Passive Description',
        'system' => 'Hero System',
        'attributes' => [
          'title' => 'Attributes',
            'agility' => 'Agility',
            'mental' => 'Mental',
            'will' => 'Will',
            'strength' => 'Strength',
            'armor' => 'Armor',
            'health' => 'Health',
        ],
        'select_abilities' => 'Select Abilities',
        'validation' => [
            'min_total_attributes' => 'The total attributes must be at least :min.',
            'max_total_attributes' => 'The total attributes cannot be more than :max.',
        ],
    ],
    
    // Cards
    'cards' => [
        'singular' => 'Card',
        'plural' => 'Cards',
        'create' => 'Create Card',
        'edit' => 'Edit Card',
        'name' => 'Card Name',
        'lore_text' => 'Lore Text',
        'effect' => 'Effect',
        'restriction' => 'Restriction',
        'image' => 'Card Image',
        'no_image' => 'No image available',
        'no_faction' => 'No Faction',
        'count' => ':count cards',
        'area' => 'Area',
        'is_area_attack' => 'Is area attack',
        'hand' => 'Hand',
        'hands' => 'Hands',
        'hands_count' => '{1} hand|[2,*] hands',
        'one_hand' => 'One Hand',
        'two_hands' => 'Two Hands',
        'select_hands' => 'Select Hands',
        'no_equipment_type' => 'No Equipment Type',
        'no_attack_range' => 'No Attack Range',
        'no_attack_subtype' => 'No Attack Subtype',
        'no_hero_ability' => 'No Hero Ability',
        'system' => 'Card System',
    ],
    
    // Hero Classes
    'hero_classes' => [
        'singular' => 'Hero Class',
        'plural' => 'Hero Classes',
        'create' => 'Create Hero Class',
        'edit' => 'Edit Hero Class',
        'name' => 'Class Name',
        'passive' => 'Class Passive',
        'heroes_count' => ':count heroes',
        'errors' => [
        'has_heroes' => 'Cannot delete the class because it has associated heroes.',
        'force_delete_has_heroes' => 'Cannot permanently delete the class because it has associated heroes.',
      ],
    ],
    
    // Hero Superclasses
    'hero_superclasses' => [
        'singular' => 'Hero Superclass',
        'plural' => 'Hero Superclasses',
        'create' => 'Create Hero Superclass',
        'edit' => 'Edit Hero Superclass',
        'name' => 'Superclass Name',
        'classes_count' => ':count classes',
        'errors' => [
            'has_classes' => 'Cannot delete the superclass because it has associated classes.',
            'has_card_type' => 'Cannot delete the superclass because it has an associated card type.',
            'force_delete_has_classes' => 'Cannot permanently delete the superclass because it has associated classes.',
            'force_delete_has_card_type' => 'Cannot permanently delete the superclass because it has an associated card type.',
        ],
    ],
    
    // Hero Races
    'hero_races' => [
        'singular' => 'Hero Race',
        'plural' => 'Hero Races',
        'create' => 'Create Hero Race',
        'edit' => 'Edit Hero Race',
        'name' => 'Race Name',
        'heroes_count' => ':count heroes',
        'errors' => [
            'has_heroes' => 'Cannot delete the race because it has associated heroes.',
            'force_delete_has_heroes' => 'Cannot permanently delete the race because it has associated heroes.',
        ],
    ],
    
    // Hero Abilities
    'hero_abilities' => [
        'singular' => 'Hero Ability',
        'plural' => 'Hero Abilities',
        'create' => 'Create Hero Ability',
        'edit' => 'Edit Hero Ability',
        'name' => 'Ability Name',
        'description' => 'Description',
        'area' => 'Area',
        'is_area_attack' => 'Is area attack',
        'type' => 'Type',
        'no_attack_range' => 'No Attack Range',
        'no_attack_subtype' => 'No Attack Subtype',
        'errors' => [
            'has_heroes' => 'Cannot delete the ability because it is assigned to heroes.',
            'has_cards' => 'Cannot delete the ability because there are cards based on it.',
            'force_delete_has_heroes' => 'Cannot permanently delete the ability because it is assigned to heroes.',
            'force_delete_has_cards' => 'Cannot permanently delete the ability because there are cards based on it.',
        ],
    ],
    
    // Card Types
    'card_types' => [
        'singular' => 'Card Type',
        'plural' => 'Card Types',
        'create' => 'Create Card Type',
        'edit' => 'Edit Card Type',
        'name' => 'Type Name',
        'cards_count' => ':count cards',
        'hero_superclass' => 'Hero Superclass',
        'select_superclass' => 'Select a Superclass',
        'no_superclass' => 'No Superclass',
        'errors' => [
            'has_cards' => 'Cannot delete the card type because it has associated cards.',
            'force_delete_has_cards' => 'Cannot permanently delete the card type because it has associated cards.',
        ],
    ],
    
    // Equipment Types
    'equipment_types' => [
        'singular' => 'Equipment Type',
        'plural' => 'Equipment Types',
        'create' => 'Create Equipment Type',
        'edit' => 'Edit Equipment Type',
        'name' => 'Type Name',
        'category' => 'Category',
        'cards_count' => ':count cards',
        'errors' => [
            'has_cards' => 'Cannot delete the equipment type because it has associated cards.',
            'force_delete_has_cards' => 'Cannot permanently delete the equipment type because it has associated cards.',
        ],
        'categories' => [
          'weapon' => 'Weapon',
          'armor' => 'Armor'
        ] 
    ],
    
    // Attack Subtypes
    'attack_subtypes' => [
        'singular' => 'Attack Subtype',
        'plural' => 'Attack Subtypes',
        'create' => 'Create Attack Subtype',
        'edit' => 'Edit Attack Subtype',
        'name' => 'Subtype Name',
        'type' => 'Type',
        'types' => [
          'magical' => 'Magical',
          'physical' => 'Physical'
        ],
        'hero_abilities_count' => ':count hero abilities',
        'cards_count' => ':count cards',
        'errors' => [
            'has_cards' => 'Cannot delete the attack subtype because it has associated cards.',
            'has_abilities' => 'Cannot delete the attack subtype because it has associated hero abilities.',
            'force_delete_has_cards' => 'Cannot permanently delete the attack subtype because it has associated cards.',
            'force_delete_has_abilities' => 'Cannot permanently delete the attack subtype because it has associated hero abilities.',
        ],
    ],
    
    // Attack Ranges
    'attack_ranges' => [
        'singular' => 'Attack Range',
        'plural' => 'Attack Ranges',
        'create' => 'Create Attack Range',
        'edit' => 'Edit Attack Range',
        'name' => 'Range Name',
        'hero_abilities_count' => ':count hero abilities',
        'cards_count' => ':count cards',
        'errors' => [
            'has_abilities' => 'Cannot delete the attack range because it has associated hero abilities.',
            'has_cards' => 'Cannot delete the attack range because it has associated cards.',
            'force_delete_has_abilities' => 'Cannot permanently delete the attack range because it has associated hero abilities.',
            'force_delete_has_cards' => 'Cannot permanently delete the attack range because it has associated cards.',
        ],
    ],
    
    // Faction Decks
    'faction_decks' => [
        'singular' => 'Faction Deck',
        'plural' => 'Faction Decks',
        'create' => 'Create Deck',
        'edit' => 'Edit Deck',
        'name' => 'Deck Name',
        'icon' => 'Deck Icon',
        'cards_count' => ':count cards',
        'heroes_count' => ':count heroes',
        'total_cards' => 'Total Cards',
        'unique_cards' => 'Unique Cards',
        'total_heroes' => 'Total Heroes',
        'unique_heroes' => 'Unique Heroes',
        'card_type_distribution' => 'Card Type Distribution',
        'selected_game_mode' => 'Selected Game Mode',
        'deck_config_info' => 'Deck Configuration',
        'deck_config_details' => 'Deck Configuration Details',
        'min_cards' => 'Minimum Cards',
        'max_cards' => 'Maximum Cards',
        'max_copies_per_card' => 'Maximum Copies per Card',
        'max_copies_per_hero' => 'Maximum Copies per Hero',
        'required_heroes' => 'Required Heroes',
        'basic_info' => 'Basic Information',
        'deck_stats' => 'Deck Statistics',
        'no_deck_config' => 'No deck configuration available for this game mode',
        'cards' => 'Cards',
        'heroes' => 'Heroes',
        'select_cards' => 'Select Cards',
        'select_heroes' => 'Select Heroes',
        'no_cards' => 'No cards in this deck',
        'no_heroes' => 'No heroes in this deck',
        'search_cards' => 'Search cards',
        'search_heroes' => 'Search heroes',
        'no_cards_selected' => 'No cards selected',
        'no_heroes_selected' => 'No heroes selected',
        'no_cards_available' => 'No cards available',
        'no_heroes_available' => 'No heroes available',
        'copies' => 'Copies',
        'validation' => [
            'min_cards' => 'The deck must have at least :min cards.',
            'max_cards' => 'The deck cannot have more than :max cards.',
            'max_copies_per_card' => 'The deck cannot have more than :max copies of the same card.',
            'max_copies_per_hero' => 'The deck cannot have more than :max copies of the same hero.',
            'required_heroes' => 'The deck must have exactly :number heroes.',
        ],
    ],
    
    // Game Modes
    'game_modes' => [
        'singular' => 'Game Mode',
        'plural' => 'Game Modes',
        'create' => 'Create Game Mode',
        'edit' => 'Edit Game Mode',
        'name' => 'Mode Name',
        'description' => 'Description',
        'faction_decks_count' => ':count faction decks',
        'errors' => [
            'has_faction_decks' => 'Cannot delete the game mode because it has associated faction decks.',
            'force_delete_has_faction_decks' => 'Cannot permanently delete the game mode because it has associated faction decks.',
        ],
    ],
    
    // Counters
    'counters' => [
        'singular' => 'Counter',
        'plural' => 'Counters',
        'create' => 'Create Counter',
        'create_with_type' => 'Create :type Counter',
        'edit' => 'Edit Counter',
        'name' => 'Counter Name',
        'icon' => 'Counter Icon',
        'effect' => 'Effect',
        'type' => 'Type',
        'types' => [
            'boon' => 'Boon',
            'bane' => 'Bane',
        ],
    ],
    
    // Hero Attributes Configuration
    'hero_attributes' => [
        'config' => 'Hero Attributes Configuration',
        'min_attribute_value' => 'Minimum Attribute Value',
        'max_attribute_value' => 'Maximum Attribute Value',
        'min_total_attributes' => 'Minimum Total Attributes',
        'max_total_attributes' => 'Maximum Total Attributes',
        'total_health_base' => 'Base Health Value',
        'agility_multiplier' => 'Agility Multiplier',
        'mental_multiplier' => 'Mental Multiplier',
        'will_multiplier' => 'Will Multiplier',
        'strength_multiplier' => 'Strength Multiplier',
        'armor_multiplier' => 'Armor Multiplier',
    ],
    
    // Deck Attributes Configuration
    'deck_attributes' => [
        'singular' => 'Deck Configuration',
        'plural' => 'Deck Configurations',
        'configurations' => 'Deck Configurations',
        'create' => 'Create Deck Configuration',
        'edit' => 'Edit Deck Configuration',
        'min_cards' => 'Minimum Cards',
        'max_cards' => 'Maximum Cards',
        'max_copies_per_card' => 'Maximum Copies per Card',
        'max_copies_per_hero' => 'Maximum Copies per Hero',
        'required_heroes' => 'Required Heroes',
        'select_game_mode' => 'Select Game Mode',
        'no_configurations' => 'No deck configurations available',
        'errors' => [
            'create' => 'Error creating deck configuration: ',
            'update' => 'Error updating deck configuration: ',
            'delete' => 'Error deleting deck configuration: ',
        ],
    ],
];
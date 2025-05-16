<?php

return [
    // Factions
    'factions' => [
        'singular' => 'Facción',
        'plural' => 'Facciones',
        'create' => 'Crear Facción',
        'edit' => 'Editar Facción',
        'name' => 'Nombre de Facción',
        'color' => 'Color de Facción',
        'lore_text' => 'Texto de Trasfondo',
        'icon' => 'Icono de Facción',
        'heroes_count' => ':count héroes',
        'cards_count' => ':count cartas',
        'text_is_dark' => 'Color del Texto',
        'text_dark' => 'Oscuro',
        'text_light' => 'Claro',
        'no_icon' => 'No hay icono disponible',
        'no_heroes' => 'No hay héroes en esta facción',
        'no_cards' => 'No hay cartas en esta facción',
        'no_decks' => 'No hay mazos para esta facción',
        'tabs' => [
            'details' => 'Detalles',
            'heroes' => 'Héroes',
            'cards' => 'Cartas',
            'decks' => 'Mazos',
        ],
        'errors' => [
            'has_heroes' => 'No se puede eliminar la facción porque tiene héroes asociados.',
            'has_cards' => 'No se puede eliminar la facción porque tiene cartas asociadas.',
            'force_delete_has_heroes' => 'No se puede eliminar permanentemente la facción porque tiene héroes asociados.',
            'force_delete_has_cards' => 'No se puede eliminar permanentemente la facción porque tiene cartas asociadas.',
        ],
    ],
    
    // Heroes
    'heroes' => [
        'singular' => 'Héroe',
        'plural' => 'Héroes',
        'create' => 'Crear Héroe',
        'edit' => 'Editar Héroe',
        'name' => 'Nombre del Héroe',
        'lore_text' => 'Texto de Trasfondo',
        'image' => 'Imagen del Héroe',
        'no_image' => 'No hay imagen disponible',
        'no_faction' => 'Sin Facción',
        'count' => ':count héroes',
        'total_attributes' => 'Total de Atributos',
        'gender' => 'Género',
        'genders' => [
            'male' => 'Masculino',
            'female' => 'Femenino',
        ],
        'passive' => 'Pasiva',
        'passive_ability' => 'Habilidad Pasiva',
        'passive_name' => 'Nombre de la Pasiva',
        'passive_description' => 'Descripción de la Pasiva',
        'system' => 'Sistema de Héroes',
        'attributes' => [
            'title' => 'Atributos',
            'agility' => 'Agilidad',
            'mental' => 'Mental',
            'will' => 'Voluntad',
            'strength' => 'Fuerza',
            'armor' => 'Armadura',
            'health' => 'Salud',
        ],
        'select_abilities' => 'Seleccionar Habilidades',
        'validation' => [
            'min_total_attributes' => 'El total de atributos debe ser al menos :min.',
            'max_total_attributes' => 'El total de atributos no puede ser mayor que :max.',
        ],
    ],
    
    // Cards
    'cards' => [
        'singular' => 'Carta',
        'plural' => 'Cartas',
        'create' => 'Crear Carta',
        'edit' => 'Editar Carta',
        'name' => 'Nombre de la Carta',
        'lore_text' => 'Texto de Trasfondo',
        'effect' => 'Efecto',
        'restriction' => 'Restricción',
        'image' => 'Imagen de la Carta',
        'no_image' => 'No hay imagen disponible',
        'no_faction' => 'Sin Facción',
        'count' => ':count cartas',
        'area' => 'Área',
        'is_area_attack' => 'Es ataque de área',
        'hands' => 'Manos',
        'hands_count' => '{1} mano|[2,*] manos',
        'one_hand' => 'Una Mano',
        'two_hands' => 'Dos Manos',
        'select_hands' => 'Seleccionar Manos',
        'no_equipment_type' => 'Sin Tipo de Equipamiento',
        'no_attack_range' => 'Sin Alcance de Ataque',
        'no_attack_subtype' => 'Sin Subtipo de Ataque',
        'no_hero_ability' => 'Sin Habilidad de Héroe',
        'system' => 'Sistema de Cartas',
    ],
    
    // Hero Classes
    'hero_classes' => [
        'singular' => 'Clase de Héroe',
        'plural' => 'Clases de Héroe',
        'create' => 'Crear Clase de Héroe',
        'edit' => 'Editar Clase de Héroe',
        'name' => 'Nombre de Clase',
        'passive' => 'Pasiva de Clase',
        'heroes_count' => ':count héroes',
        'errors' => [
            'has_heroes' => 'No se puede eliminar la clase porque tiene héroes asociados.',
            'force_delete_has_heroes' => 'No se puede eliminar permanentemente la clase porque tiene héroes asociados.',
        ],
    ],
    
    // Hero Superclasses
    'hero_superclasses' => [
        'singular' => 'Superclase de Héroe',
        'plural' => 'Superclases de Héroe',
        'create' => 'Crear Superclase de Héroe',
        'edit' => 'Editar Superclase de Héroe',
        'name' => 'Nombre de Superclase',
        'classes_count' => ':count clases',
        'errors' => [
            'has_classes' => 'No se puede eliminar la superclase porque tiene clases asociadas.',
            'has_card_type' => 'No se puede eliminar la superclase porque tiene un tipo de carta asociado.',
            'force_delete_has_classes' => 'No se puede eliminar permanentemente la superclase porque tiene clases asociadas.',
            'force_delete_has_card_type' => 'No se puede eliminar permanentemente la superclase porque tiene un tipo de carta asociado.',
        ],
    ],
    
    // Hero Races
    'hero_races' => [
        'singular' => 'Raza de Héroe',
        'plural' => 'Razas de Héroe',
        'create' => 'Crear Raza de Héroe',
        'edit' => 'Editar Raza de Héroe',
        'name' => 'Nombre de Raza',
        'heroes_count' => ':count héroes',
        'errors' => [
            'has_heroes' => 'No se puede eliminar la raza porque tiene héroes asociados.',
            'force_delete_has_heroes' => 'No se puede eliminar permanentemente la raza porque tiene héroes asociados.',
        ],
    ],
    
    // Hero Abilities
    'hero_abilities' => [
        'singular' => 'Habilidad de Héroe',
        'plural' => 'Habilidades de Héroe',
        'create' => 'Crear Habilidad de Héroe',
        'edit' => 'Editar Habilidad de Héroe',
        'name' => 'Nombre de Habilidad',
        'description' => 'Descripción',
        'area' => 'Área',
        'is_area_attack' => 'Es ataque de área',
        'type' => 'Tipo',
        'no_attack_range' => 'Sin Alcance de Ataque',
        'no_attack_subtype' => 'Sin Subtipo de Ataque',
        'errors' => [
            'has_heroes' => 'No se puede eliminar la habilidad porque está asignada a héroes.',
            'has_cards' => 'No se puede eliminar la habilidad porque hay cartas basadas en ella.',
            'force_delete_has_heroes' => 'No se puede eliminar permanentemente la habilidad porque está asignada a héroes.',
            'force_delete_has_cards' => 'No se puede eliminar permanentemente la habilidad porque hay cartas basadas en ella.',
        ],
    ],
    
    // Card Types
    'card_types' => [
        'singular' => 'Tipo de Carta',
        'plural' => 'Tipos de Carta',
        'create' => 'Crear Tipo de Carta',
        'edit' => 'Editar Tipo de Carta',
        'name' => 'Nombre del Tipo',
        'cards_count' => ':count cartas',
        'hero_superclass' => 'Superclase de Héroe',
        'select_superclass' => 'Seleccionar una Superclase',
        'no_superclass' => 'Sin Superclase',
        'errors' => [
            'has_cards' => 'No se puede eliminar el tipo de carta porque tiene cartas asociadas.',
            'force_delete_has_cards' => 'No se puede eliminar permanentemente el tipo de carta porque tiene cartas asociadas.',
        ],
    ],
    
    // Equipment Types
    'equipment_types' => [
        'singular' => 'Tipo de Equipamiento',
        'plural' => 'Tipos de Equipamiento',
        'create' => 'Crear Tipo de Equipamiento',
        'edit' => 'Editar Tipo de Equipamiento',
        'name' => 'Nombre del Tipo',
        'category' => 'Categoría',
        'cards_count' => ':count cartas',
        'errors' => [
            'has_cards' => 'No se puede eliminar el tipo de equipamiento porque tiene cartas asociadas.',
            'force_delete_has_cards' => 'No se puede eliminar permanentemente el tipo de equipamiento porque tiene cartas asociadas.',
        ],
        'categories' => [
          'weapon' => 'Arma',
          'armor' => 'Armadura'
        ] 
    ],
    
    // Attack Subtypes
    'attack_subtypes' => [
        'singular' => 'Subtipo de Ataque',
        'plural' => 'Subtipos de Ataque',
        'create' => 'Crear Subtipo de Ataque',
        'edit' => 'Editar Subtipo de Ataque',
        'name' => 'Nombre del Subtipo',
        'type' => 'Tipo',
        'types' => [
          'magical' => 'Mágico',
          'physical' => 'Físico'
        ],
        'hero_abilities_count' => ':count habilidades de héroe',
        'cards_count' => ':count cartas',
        'errors' => [
            'has_cards' => 'No se puede eliminar el subtipo de ataque porque tiene cartas asociadas.',
            'has_abilities' => 'No se puede eliminar el subtipo de ataque porque tiene habilidades de héroe asociadas.',
            'force_delete_has_cards' => 'No se puede eliminar permanentemente el subtipo de ataque porque tiene cartas asociadas.',
            'force_delete_has_abilities' => 'No se puede eliminar permanentemente el subtipo de ataque porque tiene habilidades de héroe asociadas.',
        ],
    ],
    
    // Attack Ranges
    'attack_ranges' => [
        'singular' => 'Alcance de Ataque',
        'plural' => 'Alcances de Ataque',
        'create' => 'Crear Alcance de Ataque',
        'edit' => 'Editar Alcance de Ataque',
        'name' => 'Nombre del Alcance',
        'hero_abilities_count' => ':count habilidades de héroe',
        'cards_count' => ':count cartas',
        'errors' => [
            'has_abilities' => 'No se puede eliminar el alcance de ataque porque tiene habilidades de héroe asociadas.',
            'has_cards' => 'No se puede eliminar el alcance de ataque porque tiene cartas asociadas.',
            'force_delete_has_abilities' => 'No se puede eliminar permanentemente el alcance de ataque porque tiene habilidades de héroe asociadas.',
            'force_delete_has_cards' => 'No se puede eliminar permanentemente el alcance de ataque porque tiene cartas asociadas.',
        ],
    ],
    
    // Faction Decks
    'faction_decks' => [
        'singular' => 'Mazo de Facción',
        'plural' => 'Mazos de Facción',
        'create' => 'Crear Mazo',
        'edit' => 'Editar Mazo',
        'name' => 'Nombre del Mazo',
        'icon' => 'Icono del Mazo',
        'cards_count' => ':count cartas',
        'heroes_count' => ':count héroes',
        'total_cards' => 'Total de Cartas',
        'unique_cards' => 'Cartas Únicas',
        'total_heroes' => 'Total de Héroes',
        'unique_heroes' => 'Héroes Únicos',
        'card_type_distribution' => 'Distribución por Tipo de Carta',
        'selected_game_mode' => 'Modo de Juego Seleccionado',
        'deck_config_info' => 'Configuración del Mazo',
        'deck_config_details' => 'Detalles de Configuración del Mazo',
        'min_cards' => 'Mínimo de Cartas',
        'max_cards' => 'Máximo de Cartas',
        'max_copies_per_card' => 'Máximo de Copias por Carta',
        'max_copies_per_hero' => 'Máximo de Copias por Héroe',
        'required_heroes' => 'Héroes Requeridos',
        'basic_info' => 'Información Básica',
        'deck_stats' => 'Estadísticas del Mazo',
        'no_deck_config' => 'No hay configuración de mazo disponible para este modo de juego',
        'cards' => 'Cartas',
        'heroes' => 'Héroes',
        'select_cards' => 'Seleccionar Cartas',
        'select_heroes' => 'Seleccionar Héroes',
        'no_cards' => 'No hay cartas en este mazo',
        'no_heroes' => 'No hay héroes en este mazo',
        'search_cards' => 'Buscar cartas',
        'search_heroes' => 'Buscar héroes',
        'no_cards_selected' => 'No hay cartas seleccionadas',
        'no_heroes_selected' => 'No hay héroes seleccionados',
        'no_cards_available' => 'No hay cartas disponibles',
        'no_heroes_available' => 'No hay héroes disponibles',
        'copies' => 'Copias',
        'validation' => [
            'min_cards' => 'El mazo debe tener al menos :min cartas.',
            'max_cards' => 'El mazo no puede tener más de :max cartas.',
            'max_copies_per_card' => 'El mazo no puede tener más de :max copias de la misma carta.',
            'max_copies_per_hero' => 'El mazo no puede tener más de :max copias del mismo héroe.',
            'required_heroes' => 'El mazo debe tener exactamente :number héroes.',
        ],
    ],
    
    // Game Modes
    'game_modes' => [
        'singular' => 'Modo de Juego',
        'plural' => 'Modos de Juego',
        'create' => 'Crear Modo de Juego',
        'edit' => 'Editar Modo de Juego',
        'name' => 'Nombre del Modo',
        'description' => 'Descripción',
        'faction_decks_count' => ':count mazos de facción',
        'errors' => [
            'has_faction_decks' => 'No se puede eliminar el modo de juego porque tiene mazos de facción asociados.',
            'force_delete_has_faction_decks' => 'No se puede eliminar permanentemente el modo de juego porque tiene mazos de facción asociados.',
        ],
    ],
    
    // Counters
    'counters' => [
        'singular' => 'Contador',
        'plural' => 'Contadores',
        'create' => 'Crear Contador',
        'create_with_type' => 'Crear Contador de :type',
        'edit' => 'Editar Contador',
        'name' => 'Nombre del Contador',
        'icon' => 'Icono del Contador',
        'effect' => 'Efecto',
        'type' => 'Tipo',
        'types' => [
            'boon' => 'Beneficio',
            'bane' => 'Perjuicio',
        ],
    ],
    
    // Hero Attributes Configuration
    'hero_attributes' => [
        'config' => 'Configuración de Atributos de Héroe',
        'min_attribute_value' => 'Valor Mínimo de Atributo',
        'max_attribute_value' => 'Valor Máximo de Atributo',
        'min_total_attributes' => 'Total Mínimo de Atributos',
        'max_total_attributes' => 'Total Máximo de Atributos',
        'total_health_base' => 'Valor Base de Salud',
        'agility_multiplier' => 'Multiplicador de Agilidad',
        'mental_multiplier' => 'Multiplicador Mental',
        'will_multiplier' => 'Multiplicador de Voluntad',
        'strength_multiplier' => 'Multiplicador de Fuerza',
        'armor_multiplier' => 'Multiplicador de Armadura',
    ],
    
    // Deck Attributes Configuration
    'deck_attributes' => [
        'singular' => 'Configuración de Mazo',
        'plural' => 'Configuraciones de Mazo',
        'configurations' => 'Configuraciones de Mazo',
        'create' => 'Crear Configuración de Mazo',
        'edit' => 'Editar Configuración de Mazo',
        'min_cards' => 'Mínimo de Cartas',
        'max_cards' => 'Máximo de Cartas',
        'max_copies_per_card' => 'Máximo de Copias por Carta',
        'max_copies_per_hero' => 'Máximo de Copias por Héroe',
        'required_heroes' => 'Héroes Requeridos',
        'select_game_mode' => 'Seleccionar Modo de Juego',
        'no_configurations' => 'No hay configuraciones de mazo disponibles',
        'errors' => [
            'create' => 'Error al crear la configuración del mazo: ',
            'update' => 'Error al actualizar la configuración del mazo: ',
            'delete' => 'Error al eliminar la configuración del mazo: ',
        ],
    ],
];
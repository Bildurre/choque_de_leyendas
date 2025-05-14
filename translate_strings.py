#!/usr/bin/env python3
import os
import re
import sys

# Diccionario de sustituciones organizadas por servicio/controlador
translations = {
    # HeroClassService
    "No se puede eliminar la clase porque tiene héroes asociados.": "__('entities.hero_classes.errors.has_heroes')",
    "No se puede eliminar permanentemente la clase porque tiene héroes asociados.": "__('entities.hero_classes.errors.force_delete_has_heroes')",
    
    # HeroRaceService
    "No se puede eliminar la raza porque tiene héroes asociados.": "__('entities.hero_races.errors.has_heroes')",
    "No se puede eliminar permanentemente la raza porque tiene héroes asociados.": "__('entities.hero_races.errors.force_delete_has_heroes')",
    
    # HeroSuperclassService
    "No se puede eliminar la superclase porque tiene clases asociadas.": "__('entities.hero_superclasses.errors.has_classes')",
    "No se puede eliminar la superclase porque tiene un tipo de carta asociado.": "__('entities.hero_superclasses.errors.has_card_type')",
    "No se puede eliminar permanentemente la superclase porque tiene clases asociadas.": "__('entities.hero_superclasses.errors.force_delete_has_classes')",
    "No se puede eliminar permanentemente la superclase porque tiene un tipo de carta asociado.": "__('entities.hero_superclasses.errors.force_delete_has_card_type')",
    
    # CardTypeService
    "No se puede eliminar el tipo de carta porque tiene cartas asociadas.": "__('entities.card_types.errors.has_cards')",
    "No se puede eliminar permanentemente el tipo de carta porque tiene cartas asociadas.": "__('entities.card_types.errors.force_delete_has_cards')",
    
    # EquipmentTypeService
    "No se puede eliminar el tipo de equipo porque tiene cartas asociadas.": "__('entities.equipment_types.errors.has_cards')",
    "No se puede eliminar permanentemente el tipo de equipo porque tiene cartas asociadas.": "__('entities.equipment_types.errors.force_delete_has_cards')",
    
    # AttackRangeService
    "No se puede eliminar el rango de ataque porque tiene habilidades de héroe asociadas.": "__('entities.attack_ranges.errors.has_abilities')",
    "No se puede eliminar el rango de ataque porque tiene cartas asociadas.": "__('entities.attack_ranges.errors.has_cards')",
    "No se puede eliminar permanentemente el rango de ataque porque tiene habilidades de héroe asociadas.": "__('entities.attack_ranges.errors.force_delete_has_abilities')",
    "No se puede eliminar permanentemente el rango de ataque porque tiene cartas asociadas.": "__('entities.attack_ranges.errors.force_delete_has_cards')",
    
    # AttackSubtypeService
    "No se puede eliminar el subtipo de ataque porque tiene cartas asociadas.": "__('entities.attack_subtypes.errors.has_cards')",
    "No se puede eliminar el subtipo de ataque porque tiene habilidades de héroe asociadas.": "__('entities.attack_subtypes.errors.has_abilities')",
    "No se puede eliminar permanentemente el subtipo de ataque porque tiene cartas asociadas.": "__('entities.attack_subtypes.errors.force_delete_has_cards')",
    "No se puede eliminar permanentemente el subtipo de ataque porque tiene habilidades de héroe asociadas.": "__('entities.attack_subtypes.errors.force_delete_has_abilities')",
    
    # FactionService
    "No se puede eliminar la facción porque tiene héroes asociados.": "__('entities.factions.errors.has_heroes')",
    "No se puede eliminar la facción porque tiene cartas asociadas.": "__('entities.factions.errors.has_cards')",
    "No se puede eliminar permanentemente la facción porque tiene héroes asociados.": "__('entities.factions.errors.force_delete_has_heroes')",
    "No se puede eliminar permanentemente la facción porque tiene cartas asociadas.": "__('entities.factions.errors.force_delete_has_cards')",
    
    # HeroAbilityService
    "No se puede eliminar la habilidad porque está asignada a héroes.": "__('entities.hero_abilities.errors.has_heroes')",
    "No se puede eliminar la habilidad porque hay cartas basadas en ella.": "__('entities.hero_abilities.errors.has_cards')",
    "No se puede eliminar permanentemente la habilidad porque está asignada a héroes.": "__('entities.hero_abilities.errors.force_delete_has_heroes')",
    "No se puede eliminar permanentemente la habilidad porque hay cartas basadas en ella.": "__('entities.hero_abilities.errors.force_delete_has_cards')",
    
    # GameModeService
    "No se puede eliminar el modo de juego porque tiene mazos de facción asociados.": "__('entities.game_modes.errors.has_faction_decks')",
    "No se puede eliminar permanentemente el modo de juego porque tiene mazos de facción asociados.": "__('entities.game_modes.errors.force_delete_has_faction_decks')",
    
    # DeckAttributesConfigurationService
    "Error al crear la configuración de mazos: ": "__('entities.deck_attributes.errors.create') + ' '",
    "Error al actualizar la configuración de mazos: ": "__('entities.deck_attributes.errors.update') + ' '",
    "Error al eliminar la configuración de mazos: ": "__('entities.deck_attributes.errors.delete') + ' '",
    
    # Mensajes en DeckAttributesConfiguration.php
    "El mazo debe tener al menos {$this->min_cards} cartas.": "__('entities.faction_decks.validation.min_cards', ['min' => $this->min_cards])",
    "El mazo no puede tener más de {$this->max_cards} cartas.": "__('entities.faction_decks.validation.max_cards', ['max' => $this->max_cards])",
    "El mazo no puede tener más de {$this->max_copies_per_card} copias de una misma carta.": "__('entities.faction_decks.validation.max_copies_per_card', ['max' => $this->max_copies_per_card])",
    "El mazo no puede tener más de {$this->max_copies_per_hero} copias de un mismo héroe.": "__('entities.faction_decks.validation.max_copies_per_hero', ['max' => $this->max_copies_per_hero])",
    "El mazo debe tener exactamente {$this->required_heroes} héroes.": "__('entities.faction_decks.validation.required_heroes', ['number' => $this->required_heroes])",
    
    # Mensajes de error en controladores (patrones comunes)
    "Ha ocurrido un error al crear la Clase: ": "__('common.errors.create', ['entity' => __('entities.hero_classes.singular')]) + ' '",
    "Ha ocurrido un error al actualizar la Clase: ": "__('common.errors.update', ['entity' => __('entities.hero_classes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar la Clase: ": "__('common.errors.delete', ['entity' => __('entities.hero_classes.singular')]) + ' '",
    "Ha ocurrido un error al restaurar la Clase: ": "__('common.errors.restore', ['entity' => __('entities.hero_classes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente la Clase: ": "__('common.errors.force_delete', ['entity' => __('entities.hero_classes.singular')]) + ' '",
    
    "Ha ocurrido un error al crear la Raza: ": "__('common.errors.create', ['entity' => __('entities.hero_races.singular')]) + ' '",
    "Ha ocurrido un error al actualizar la Raza: ": "__('common.errors.update', ['entity' => __('entities.hero_races.singular')]) + ' '",
    "Ha ocurrido un error al eliminar la Raza: ": "__('common.errors.delete', ['entity' => __('entities.hero_races.singular')]) + ' '",
    "Ha ocurrido un error al restaurar la Raza: ": "__('common.errors.restore', ['entity' => __('entities.hero_races.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente la Raza: ": "__('common.errors.force_delete', ['entity' => __('entities.hero_races.singular')]) + ' '",
    
    "Ha ocurrido un error al crear la Superclase: ": "__('common.errors.create', ['entity' => __('entities.hero_superclasses.singular')]) + ' '",
    "Ha ocurrido un error al actualizar la Superclase: ": "__('common.errors.update', ['entity' => __('entities.hero_superclasses.singular')]) + ' '",
    "Ha ocurrido un error al eliminar la Superclase: ": "__('common.errors.delete', ['entity' => __('entities.hero_superclasses.singular')]) + ' '",
    "Ha ocurrido un error al restaurar la Superclase: ": "__('common.errors.restore', ['entity' => __('entities.hero_superclasses.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente la Superclase: ": "__('common.errors.force_delete', ['entity' => __('entities.hero_superclasses.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Tipo de Carta: ": "__('common.errors.create', ['entity' => __('entities.card_types.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Tipo de Carta: ": "__('common.errors.update', ['entity' => __('entities.card_types.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Tipo de Carta: ": "__('common.errors.delete', ['entity' => __('entities.card_types.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Tipo de Carta: ": "__('common.errors.restore', ['entity' => __('entities.card_types.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Tipo de Carta: ": "__('common.errors.force_delete', ['entity' => __('entities.card_types.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Tipo de Equipo: ": "__('common.errors.create', ['entity' => __('entities.equipment_types.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Tipo de Equipo: ": "__('common.errors.update', ['entity' => __('entities.equipment_types.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Tipo de Equipo: ": "__('common.errors.delete', ['entity' => __('entities.equipment_types.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Tipo de Equipo: ": "__('common.errors.restore', ['entity' => __('entities.equipment_types.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Tipo de Equipo: ": "__('common.errors.force_delete', ['entity' => __('entities.equipment_types.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Rango de Ataque: ": "__('common.errors.create', ['entity' => __('entities.attack_ranges.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Rango de Ataque: ": "__('common.errors.update', ['entity' => __('entities.attack_ranges.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Rango de Ataque: ": "__('common.errors.delete', ['entity' => __('entities.attack_ranges.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Rango de Ataque: ": "__('common.errors.restore', ['entity' => __('entities.attack_ranges.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Rango de Ataque: ": "__('common.errors.force_delete', ['entity' => __('entities.attack_ranges.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Subtipo de Ataque: ": "__('common.errors.create', ['entity' => __('entities.attack_subtypes.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Subtipo de Ataque: ": "__('common.errors.update', ['entity' => __('entities.attack_subtypes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Subtipo de Ataque: ": "__('common.errors.delete', ['entity' => __('entities.attack_subtypes.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Subtipo de Ataque: ": "__('common.errors.restore', ['entity' => __('entities.attack_subtypes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Subtipo de Ataque: ": "__('common.errors.force_delete', ['entity' => __('entities.attack_subtypes.singular')]) + ' '",
    
    "Ha ocurrido un error al crear la Facción: ": "__('common.errors.create', ['entity' => __('entities.factions.singular')]) + ' '",
    "Ha ocurrido un error al actualizar la Facción: ": "__('common.errors.update', ['entity' => __('entities.factions.singular')]) + ' '",
    "Ha ocurrido un error al eliminar la Facción: ": "__('common.errors.delete', ['entity' => __('entities.factions.singular')]) + ' '",
    "Ha ocurrido un error al restaurar la Facción: ": "__('common.errors.restore', ['entity' => __('entities.factions.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente la Facción: ": "__('common.errors.force_delete', ['entity' => __('entities.factions.singular')]) + ' '",
    
    "Ha ocurrido un error al crear la Habilidad: ": "__('common.errors.create', ['entity' => __('entities.hero_abilities.singular')]) + ' '",
    "Ha ocurrido un error al actualizar la Habilidad: ": "__('common.errors.update', ['entity' => __('entities.hero_abilities.singular')]) + ' '",
    "Ha ocurrido un error al eliminar la Habilidad: ": "__('common.errors.delete', ['entity' => __('entities.hero_abilities.singular')]) + ' '",
    "Ha ocurrido un error al restaurar la Habilidad: ": "__('common.errors.restore', ['entity' => __('entities.hero_abilities.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente la Habilidad: ": "__('common.errors.force_delete', ['entity' => __('entities.hero_abilities.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Modo de Juego: ": "__('common.errors.create', ['entity' => __('entities.game_modes.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Modo de Juego: ": "__('common.errors.update', ['entity' => __('entities.game_modes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Modo de Juego: ": "__('common.errors.delete', ['entity' => __('entities.game_modes.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Modo de Juego: ": "__('common.errors.restore', ['entity' => __('entities.game_modes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Modo de Juego: ": "__('common.errors.force_delete', ['entity' => __('entities.game_modes.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Héroe: ": "__('common.errors.create', ['entity' => __('entities.heroes.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Héroe: ": "__('common.errors.update', ['entity' => __('entities.heroes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Héroe: ": "__('common.errors.delete', ['entity' => __('entities.heroes.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Héroe: ": "__('common.errors.restore', ['entity' => __('entities.heroes.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Héroe: ": "__('common.errors.force_delete', ['entity' => __('entities.heroes.singular')]) + ' '",
    
    "Ha ocurrido un error al crear la Carta: ": "__('common.errors.create', ['entity' => __('entities.cards.singular')]) + ' '",
    "Ha ocurrido un error al actualizar la Carta: ": "__('common.errors.update', ['entity' => __('entities.cards.singular')]) + ' '",
    "Ha ocurrido un error al eliminar la Carta: ": "__('common.errors.delete', ['entity' => __('entities.cards.singular')]) + ' '",
    "Ha ocurrido un error al restaurar la Carta: ": "__('common.errors.restore', ['entity' => __('entities.cards.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente la Carta: ": "__('common.errors.force_delete', ['entity' => __('entities.cards.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Contador: ": "__('common.errors.create', ['entity' => __('entities.counters.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Contador: ": "__('common.errors.update', ['entity' => __('entities.counters.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Contador: ": "__('common.errors.delete', ['entity' => __('entities.counters.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Contador: ": "__('common.errors.restore', ['entity' => __('entities.counters.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Contador: ": "__('common.errors.force_delete', ['entity' => __('entities.counters.singular')]) + ' '",
    
    "Ha ocurrido un error al crear el Mazo de Facción: ": "__('common.errors.create', ['entity' => __('entities.faction_decks.singular')]) + ' '",
    "Ha ocurrido un error al actualizar el Mazo de Facción: ": "__('common.errors.update', ['entity' => __('entities.faction_decks.singular')]) + ' '",
    "Ha ocurrido un error al eliminar el Mazo de Facción: ": "__('common.errors.delete', ['entity' => __('entities.faction_decks.singular')]) + ' '",
    "Ha ocurrido un error al restaurar el Mazo de Facción: ": "__('common.errors.restore', ['entity' => __('entities.faction_decks.singular')]) + ' '",
    "Ha ocurrido un error al eliminar permanentemente el Mazo de Facción: ": "__('common.errors.force_delete', ['entity' => __('entities.faction_decks.singular')]) + ' '",
    
    # Mensajes de validación en Requests
    'El nombre de la clase es obligatorio.': "__('validation.required', ['attribute' => __('entities.hero_classes.name')])",
    'El nombre debe ser un array con traducciones.': "__('validation.array', ['attribute' => __('common.name')])",
    'El nombre en español es obligatorio.': "__('validation.required', ['attribute' => __('common.name'). ' ' . __('in_spanish')])",
    'La superclase es obligatoria.': "__('validation.required', ['attribute' => __('entities.hero_superclasses.singular')])",
    'La superclase seleccionada no existe.': "__('validation.exists', ['attribute' => __('entities.hero_superclasses.singular')])",
}

# Patrones para encontrar cadenas en excepciones (throw new Exception("..."))
exception_pattern = r'throw new \\Exception\("([^"]+)"\);'
exception_pattern_with_vars = r'throw new \\Exception\("([^"]+)" \. (.*?)\);'

# Patrones para encontrar mensajes de error en los controladores
controller_error_pattern = r'->with\(\'error\', \'([^\']+)\' \. (.*?)\)'

# Patrón para encontrar mensajes de validación en los Requests
validation_message_pattern = r'\'([^\']+)\' => \'([^\']+)\','

def process_file(filepath):
    """Procesa un archivo y reemplaza todas las cadenas según el diccionario."""
    with open(filepath, 'r', encoding='utf-8') as file:
        content = file.read()
    
    original_content = content
    
    # Primero reemplazamos las cadenas literales
    for old, new in translations.items():
        content = content.replace(old, new)
    
    # Procesamos patrones de excepción con variables
    content = re.sub(exception_pattern_with_vars, lambda m: f'throw new \\Exception({m.group(1).replace(m.group(1), translations.get(m.group(1), m.group(1)))} . {m.group(2)});', content)
    
    # Procesamos patrones de excepción simples
    matches = re.findall(exception_pattern, content)
    for match in matches:
        if match in translations:
            content = content.replace(f'throw new \\Exception("{match}");', f'throw new \\Exception({translations[match]});')
    
    # Procesamos patrones de error en controladores
    content = re.sub(controller_error_pattern, lambda m: f'->with(\'error\', {translations.get(m.group(1) + " ", m.group(1))} . {m.group(2)})', content)
    
    # Procesamos patrones de mensajes de validación
    # Esto es más complejo y podría requerir una implementación específica 
    # si los formatos de los mensajes varían significativamente
    
    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as file:
            file.write(content)
        return True
    return False

def main():
    """Función principal que recorre todos los archivos y aplica las sustituciones."""
    # Directorios a examinar
    directories = [
        'app/Http/Controllers',
        'app/Http/Controllers/Admin',
        'app/Http/Controllers/Auth',
        'app/Http/Controllers/Game',
        'app/Http/Controllers/Content',
        'app/Http/Requests',
        'app/Http/Requests/Admin',
        'app/Http/Requests/Auth',
        'app/Http/Requests/Game',
        'app/Http/Requests/Content',
        'app/Services',
        'app/Services/Game',
        'app/Services/Content',
    ]
    
    # Extensiones de archivo a procesar
    extensions = ['.php']
    
    total_files = 0
    modified_files = 0
    
    for directory in directories:
        if not os.path.exists(directory):
            print(f"El directorio {directory} no existe. Saltando...")
            continue
        
        for root, _, files in os.walk(directory):
            for file in files:
                if any(file.endswith(ext) for ext in extensions):
                    filepath = os.path.join(root, file)
                    total_files += 1
                    
                    try:
                        if process_file(filepath):
                            modified_files += 1
                            print(f"Procesado: {filepath}")
                    except Exception as e:
                        print(f"Error al procesar {filepath}: {e}")
    
    print(f"\nTotal de archivos examinados: {total_files}")
    print(f"Archivos modificados: {modified_files}")

if __name__ == "__main__":
    main()
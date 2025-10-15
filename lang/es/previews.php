<?php

return [
  'plural' => 'Previews',
  
  // Action titles
  'regenerate' => 'Regenerar preview',
  'regenerate_faction' => 'Regenerar previews de facción',
  'delete' => 'Eliminar preview',
  'delete_faction' => 'Eliminar previews de facción',
  
  // Confirmation messages
  'confirm_regenerate' => '¿Estás seguro de que quieres regenerar la preview de este elemento?',
  'confirm_regenerate_faction' => '¿Estás seguro de que quieres regenerar todas las previews de esta facción?',
  'confirm_delete' => '¿Estás seguro de que quieres eliminar la preview de este elemento?',
  'confirm_delete_faction' => '¿Estás seguro de que quieres eliminar todas las previews de esta facción?',
  'confirm_regenerate_all' => '¿Estás seguro de que quieres regenerar TODAS las previews? Esto puede tardar mucho tiempo.',
  'confirm_delete_all' => '¿Estás seguro de que quieres eliminar TODAS las previews? Esta acción no se puede deshacer.',
  'confirm_delete_all_heroes' => '¿Estás seguro de que quieres eliminar todas las previews de héroes?',
  'confirm_delete_all_cards' => '¿Estás seguro de que quieres eliminar todas las previews de cartas?',
  
  // Success messages
  'regenerate_queued' => 'La preview de :type ":name" se ha añadido a la cola de generación.',
  'regenerate_faction_queued' => 'Las previews de la facción ":name" se han añadido a la cola de generación.',
  'delete_success' => 'La preview de :type ":name" se ha eliminado correctamente.',
  'delete_faction_success' => 'Las previews de la facción ":name" se han eliminado correctamente.',
  'generate_all_queued' => 'Todas las previews faltantes se han añadido a la cola de generación.',
  'regenerate_all_queued' => 'Todas las previews se han añadido a la cola de regeneración.',
  'delete_all_heroes_success' => 'Todas las previews de héroes se han eliminado correctamente.',
  'delete_all_cards_success' => 'Todas las previews de cartas se han eliminado correctamente.',
  'delete_all_success' => 'Todas las previews se han eliminado correctamente.',
  'clean_success' => 'Los archivos huérfanos se han eliminado correctamente.',
  'no_orphaned_files' => 'No se encontraron archivos huérfanos.',
  
  // Error messages
  'regenerate_failed' => 'Error al regenerar la preview: :error',
  'regenerate_faction_failed' => 'Error al regenerar las previews de la facción: :error',
  'delete_failed' => 'Error al eliminar la preview: :error',
  'delete_faction_failed' => 'Error al eliminar las previews de la facción: :error',
  'generate_all_failed' => 'Error al generar las previews: :error',
  'regenerate_all_failed' => 'Error al regenerar las previews: :error',
  'delete_all_heroes_failed' => 'Error al eliminar las previews de héroes: :error',
  'delete_all_cards_failed' => 'Error al eliminar las previews de cartas: :error',
  'delete_all_failed' => 'Error al eliminar todas las previews: :error',
  'clean_failed' => 'Error al limpiar archivos huérfanos: :error',
  'no_selection' => 'Por favor selecciona un elemento.',
  
  // Dashboard labels
  'title' => 'Gestión de Previews',
  'status_overview' => 'Estado General',
  'heroes_status' => 'Estado de Héroes',
  'cards_status' => 'Estado de Cartas',
  'disk_usage' => 'Uso de Disco',
  'total_disk_usage' => 'Uso Total de Disco',
  'complete' => 'Completas',
  'partial' => 'Parciales',
  'missing' => 'Faltantes',
  'total' => 'Total',
  
  // Section titles
  'bulk_actions' => 'Acciones Masivas',
  'generation_actions' => 'Acciones de Generación',
  'deletion_actions' => 'Acciones de Eliminación',
  'maintenance_actions' => 'Mantenimiento',
  'individual_actions' => 'Acciones Individuales',
  'hero_actions' => 'Acciones por Héroe',
  'card_actions' => 'Acciones por Carta',
  'faction_actions' => 'Acciones por Facción',
  
  // Form labels
  'select_hero' => 'Seleccionar Héroe',
  'select_hero_placeholder' => 'Selecciona un héroe',
  'select_card' => 'Seleccionar Carta',
  'select_card_placeholder' => 'Selecciona una carta',
  'select_faction' => 'Seleccionar Facción',
  'select_faction_placeholder' => 'Selecciona una facción',
  'select_type' => 'Tipo de elementos',
  'all' => 'Todo (Héroes y Cartas)',
  'only_heroes' => 'Solo Héroes',
  'only_cards' => 'Solo Cartas',
  
  // Actions
  'generate_missing' => 'Generar faltantes',
  'regenerate_all' => 'Regenerar todas',
  'delete_all' => 'Eliminar todas',
  'delete_heroes' => 'Eliminar héroes',
  'delete_cards' => 'Eliminar cartas',
  'clean_orphaned' => 'Limpiar huérfanos',
];
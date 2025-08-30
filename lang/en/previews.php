<?php

return [
  'plural' => 'Previews',
  
  // Action titles
  'regenerate' => 'Regenerate preview',
  'regenerate_faction' => 'Regenerate faction previews',
  'delete' => 'Delete preview',
  'delete_faction' => 'Delete faction previews',
  
  // Confirmation messages
  'confirm_regenerate' => 'Are you sure you want to regenerate the preview of this item?',
  'confirm_regenerate_faction' => 'Are you sure you want to regenerate all previews of this faction?',
  'confirm_delete' => 'Are you sure you want to delete the preview of this item?',
  'confirm_delete_faction' => 'Are you sure you want to delete all previews of this faction?',
  'confirm_regenerate_all' => 'Are you sure you want to regenerate ALL previews? This may take a long time.',
  'confirm_delete_all' => 'Are you sure you want to delete ALL previews? This action cannot be undone.',
  'confirm_delete_all_heroes' => 'Are you sure you want to delete all hero previews?',
  'confirm_delete_all_cards' => 'Are you sure you want to delete all card previews?',
  
  // Success messages
  'regenerate_queued' => 'The preview of :type ":name" has been added to the generation queue.',
  'regenerate_faction_queued' => 'The previews of the faction ":name" have been added to the generation queue.',
  'delete_success' => 'The preview of :type ":name" was successfully deleted.',
  'delete_faction_success' => 'The previews of the faction ":name" were successfully deleted.',
  'generate_all_queued' => 'All missing previews have been added to the generation queue.',
  'regenerate_all_queued' => 'All previews have been added to the regeneration queue.',
  'delete_all_heroes_success' => 'All hero previews were successfully deleted.',
  'delete_all_cards_success' => 'All card previews were successfully deleted.',
  'delete_all_success' => 'All previews were successfully deleted.',
  'clean_success' => 'Orphaned files were successfully removed.',
  'no_orphaned_files' => 'No orphaned files were found.',
  
  // Error messages
  'regenerate_failed' => 'Failed to regenerate preview: :error',
  'regenerate_faction_failed' => 'Failed to regenerate faction previews: :error',
  'delete_failed' => 'Failed to delete preview: :error',
  'delete_faction_failed' => 'Failed to delete faction previews: :error',
  'generate_all_failed' => 'Failed to generate previews: :error',
  'regenerate_all_failed' => 'Failed to regenerate previews: :error',
  'delete_all_heroes_failed' => 'Failed to delete hero previews: :error',
  'delete_all_cards_failed' => 'Failed to delete card previews: :error',
  'delete_all_failed' => 'Failed to delete all previews: :error',
  'clean_failed' => 'Failed to clean orphaned files: :error',
  'no_selection' => 'Please select an item.',
  
  // Dashboard labels
  'title' => 'Preview Management',
  'status_overview' => 'Status Overview',
  'heroes_status' => 'Heroes Status',
  'cards_status' => 'Cards Status',
  'disk_usage' => 'Disk Usage',
  'total_disk_usage' => 'Total Disk Usage',
  'complete' => 'Complete',
  'partial' => 'Partial',
  'missing' => 'Missing',
  'total' => 'Total',
  
  // Section titles
  'bulk_actions' => 'Bulk Actions',
  'generation_actions' => 'Generation Actions',
  'deletion_actions' => 'Deletion Actions',
  'maintenance_actions' => 'Maintenance',
  'individual_actions' => 'Individual Actions',
  'hero_actions' => 'Hero Actions',
  'card_actions' => 'Card Actions',
  'faction_actions' => 'Faction Actions',
  
  // Form labels
  'select_hero' => 'Select Hero',
  'select_hero_placeholder' => '-- Select a hero --',
  'select_card' => 'Select Card',
  'select_card_placeholder' => '-- Select a card --',
  'select_faction' => 'Select Faction',
  'select_faction_placeholder' => '-- Select a faction --',
  'select_type' => 'Element type',
  'all' => 'All (Heroes and Cards)',
  'only_heroes' => 'Only Heroes',
  'only_cards' => 'Only Cards',
  
  // Actions
  'generate_missing' => 'Generate missing',
  'regenerate_all' => 'Regenerate all',
  'delete_all' => 'Delete all',
  'delete_heroes' => 'Delete heroes',
  'delete_cards' => 'Delete cards',
  'clean_orphaned' => 'Clean orphaned',
];
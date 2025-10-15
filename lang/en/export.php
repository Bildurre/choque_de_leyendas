<?php

return [

  // Pages
  'database_title' => 'Export - Database',
  'menu_database_title' => 'Database',
  'json_title' => 'Export - JSON',
  'menu_json_title' => 'JSON',
  
  // Database - Information
  'database_information' => 'Database Information',
  'current_database' => 'Current Database',
  'database_name' => 'Name',
  'table_count' => 'Number of Tables',
  'total_size' => 'Total Size',
  
  // Database - Export
  'database' => 'Export Database',
  'new_export' => 'New Export',
  'include_data' => 'Include data',
  'include_data_help' => 'If unchecked, only the database structure will be exported',
  'compress_file' => 'Compress file (ZIP)',
  'compress_file_help' => 'Compress the SQL file to reduce download size',
  'export_button' => 'Export Database',
  
  // Database - Import
  'database_restore' => 'Import Database',
  'upload_backup' => 'Upload Backup',
  'upload_backup_help' => 'Upload an SQL file or a ZIP containing an SQL file to restore it. Once uploaded, it will appear in the list of available backups.',
  'select_file' => 'Select file',
  'upload_button' => 'Upload File',
  
  // Database - Export list
  'other_exports' => 'Available Backups',
  'uploaded' => 'Uploaded',
  
  // JSON - Section
  'json_exports_section' => 'JSON Exports',
  'recent_exports' => 'Recent Exports',
  
  // JSON - Cards
  'export_cards' => 'Export Cards',
  'export_cards_help' => 'Export all cards in JSON format with all their data and translations.',
  'export_cards_button' => 'Export Cards',
  
  // JSON - Heroes
  'export_heroes' => 'Export Heroes',
  'export_heroes_help' => 'Export all heroes in JSON format with all their data and translations.',
  'export_heroes_button' => 'Export Heroes',
  
  // JSON - Counters
  'export_counters' => 'Export Counters',
  'export_counters_help' => 'Export all counters in JSON format with all their data and translations.',
  'export_counters_button' => 'Export Counters',
  
  // JSON - Classes
  'export_classes' => 'Export Classes',
  'export_classes_help' => 'Export all hero classes in JSON format with all their data and translations.',
  'export_classes_button' => 'Export Classes',
  
  // JSON - Single Faction
  'export_faction' => 'Export Faction',
  'export_faction_help' => 'Select a faction to export all its heroes and cards in JSON format.',
  'select_faction' => 'Select faction',
  'select_faction_placeholder' => 'Select a faction',
  'export_faction_button' => 'Export Faction',
  
  // JSON - All Factions
  'export_all_factions' => 'Export All Factions',
  'export_all_factions_help' => 'Export all factions in separate JSON files compressed in a ZIP.',
  'export_all_factions_button' => 'Export All Factions',
  
  // Actions
  'download' => 'Download',
  'download_all' => 'Download All',
  'delete_all' => 'Delete All',
  'restore' => 'Restore',
  
  // Confirmations
  'confirm_delete_single' => 'Are you sure you want to delete this export?',
  'confirm_delete_all' => 'Are you sure you want to delete all exports?',
  'restore_confirm' => 'Are you sure you want to restore the database? This action will overwrite all current data and cannot be undone.',
  
  // Success messages
  'deleted_single_success' => 'Export deleted successfully.',
  'deleted_all_success' => ':count export(s) deleted successfully.',
  'upload_success' => 'File :filename uploaded successfully.',
  'restore_success_logout' => 'Database restored successfully. Please log in again.',
  
  // Error messages
  'export_error' => 'Error exporting',
  'delete_error' => 'Error deleting export.',
  'upload_error' => 'Error uploading file',
  'restore_error' => 'Error restoring database',
  'download_error' => 'Error downloading exports',
  'no_exports_to_download' => 'No exports available to download.',
  'invalid_zip_file' => 'The file is not a valid ZIP.',
  'invalid_sql_file' => 'The file is not a valid SQL.',
  'invalid_file_type' => 'Invalid file type. Only .sql and .zip files are allowed.',
];
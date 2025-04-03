# Alanda: Choque de Leyendas - Web Project Documentation

## Project File Structure
```
choque_de_leyendas/
├── app/
│   ├── Models/
│   │   ├── User.php (updated with is_admin field)
│   │   ├── Faction.php (added for faction management)
│   │   ├── Deck.php (planned for deck management)
│   │   ├── HeroAttributeConfiguration.php (for managing hero base attributes)
│   │   └── HeroClass.php (NEW: for managing hero classes)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php (handles admin dashboard)
│   │   │   │   ├── FactionController.php (CRUD for factions)
│   │   │   │   ├── HeroAttributeConfigurationController.php (manages hero attribute configuration)
│   │   │   │   ├── HeroClassController.php (NEW: manages hero classes)
│   │   │   │   └── HeroController.php (placeholder for hero management)
│   │   ├── Middleware/
│   │   │   └── EnsureIsAdmin.php (restricts access to admin users)
│   │   └── Requests/
│   │       └── Auth/ (modified to use admin.dashboard routes)
│   └── Services/ (potential future addition for complex logic)
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php (modified with is_admin field)
│   │   ├── 2025_04_01_095111_create_factions_table.php (added)
│   │   ├── migration_for_hero_attribute_configurations.php (for hero base attributes)
│   │   └── migration_for_hero_classes.php (NEW: for hero classes)
│   ├── seeders/
│   │   ├── DatabaseSeeder.php (updated)
│   │   ├── AdminUserSeeder.php
│   │   ├── FactionSeeder.php
│   │   ├── HeroAttributeConfigurationSeeder.php (seeds initial hero attribute config)
│   │   └── HeroClassSeeder.php (NEW: seeds initial hero classes)
│   └── data/
│       └── factions.json
├── resources/
│   ├── scss/
│   │   ├── abstracts/
│   │   │   ├── _all.scss                              
│   │   │   ├── _colors.scss
│   │   │   ├── _fonts.scss
│   │   │   ├── _mixins.scss
│   │   │   └── _variables.scss
│   │   ├── base/
│   │   │   ├── _normalize.scss
│   │   │   └── _base.scss
│   │   ├── components/
│   │   │   ├── _game-dice.scss
│   │   │   ├── _alerts.scss
│   │   │   ├── _buttons.scss
│   │   │   ├── _forms.scss
│   │   │   └── _image-uploader.scss
│   │   ├── layout/
│   │   │   ├── _admin-layout.scss                     
│   │   │   ├── _admin-header.scss                     
│   │   │   └── _admin-sidebar.scss                    
│   │   ├── pages/
│   │   │   ├── _login.scss
│   │   │   ├── _dashboard.scss                        
│   │   │   ├── _welcome.scss
│   │   │   ├── _factions.scss
│   │   │   ├── _hero-attributes.scss
│   │   │   └── _hero-classes.scss (NEW: styles for hero classes)
│   │   ├── vendor/
│   │   ├── views/
│   │   └── _app.scss (updated to include new components)
│   ├── js/
│   │   ├── components/                                
│   │   │   ├── sidebar.js                             
│   │   │   └── image-uploader.js
│   │   ├── factions/
│   │   │   ├── index.js
│   │   │   └── edit.js
│   │   ├── utilities/                                
│   │   ├── pages/                                    
│   │   ├── app.js                                    
│   │   ├── alpine-init.js                             
│   │   └── bootstrap.js
│   └── views/
│       ├── admin/
│       │   ├── factions/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── show.blade.php
│       │   ├── hero-attributes/
│       │   │   └── edit.blade.php
│       │   ├── hero-classes/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   └── heroes/ (planned for hero management)
│       ├── auth/
│       │   └── login.blade.php (customized)
│       ├── components/
│       │   ├── application-logo.blade.php
│       │   ├── game-dice.blade.php
│       │   ├── image-uploader.blade.php
│       │   └── input-label.blade.php
│       ├── layouts/
│       │   ├── admin.blade.php (updated with dashboard link)                        
│       │   ├── app.blade.php
│       │   └── guest.blade.php
│       ├── dashboard.blade.php                        
│       └── welcome.blade.php
├── routes/
│   └── web.php (updated with admin prefix and resource routes)
└── README.md
```

## Project Overview
- **Framework**: Laravel 12.4
- **Frontend**: Blade + Alpine.js
- **Styling**: SCSS
- **Authentication**: Laravel Breeze (customized)

## Recent Implementation Updates

### Hero Attribute Configuration System
- Created `HeroAttributeConfiguration` model to manage base hero attributes
- Implemented migration for storing configuration
- Added seeder to initialize default configuration
- Created admin controller for managing hero attributes
- Developed a responsive configuration form with:
  - 3-column layout for desktop
  - Dynamic attribute distribution
  - Total points management
  - Validation of attribute point allocation
- Added sidebar navigation link to attribute configuration
- Created SCSS styles with responsive design considerations
- Implemented form validation and user-friendly interface

### Hero Classes Management System
- Created `HeroClass` model to manage hero classes
- Implemented migration for storing class information
- Added comprehensive CRUD operations for hero classes
- Developed admin controller for managing hero classes
- Created views for:
  - Listing hero classes
  - Creating new hero classes
  - Editing existing hero classes
- Implemented attribute modifier validation
- Added client-side JavaScript validation for modifier totals
- Created responsive design for class management pages
- Implemented superclass (fighter/caster) selection
- Added support for class-specific passive abilities
- Created detailed styling for class cards and forms

### Faction Management Form Implementation
- Created comprehensive edit form for factions
- Implemented dynamic image upload component (`ImageUploader`)
  - Supports drag and drop file upload
  - Provides preview functionality
  - Validates file size and format
  - Allows image removal
- Added client-side form validation for image uploads
- Created JavaScript module for handling image upload interactions
- Implemented server-side validation in `FactionController`
- Added support for icon upload, preview, and removal
- Created SCSS styles for form and image upload components

### Form Validation and Interaction
- Implemented client-side color input synchronization
- Added dynamic file name display
- Created interactive form elements with immediate feedback
- Implemented image removal and upload cancellation logic

### Styling and User Experience
- Created modular SCSS components for forms
- Added responsive design considerations
- Implemented consistent styling across form elements
- Created reusable form components with clear visual hierarchy

### Routes and Controller Updates
- Enhanced `FactionController` with robust update method
- Added server-side validation for faction updates
- Implemented icon management (upload, remove, update)
- Created route for faction management

## Upcoming Implementation Tasks
- Complete faction management CRUD views (create, edit, show)
- Hero management CRUD operations
- Card management interface
- Deck builder interface
- Balance analysis tools
- Rules and annexos content management
- PDF export functionality
- Public library interface for exploring game content

## Database Schema Design
- Faction → Heroes (one-to-many)
- Faction → Cards (one-to-many)
- Faction → Decks (one-to-many)
- Decks → Heroes (many-to-many with copies attribute)
- Decks → Cards (many-to-many with copies attribute)
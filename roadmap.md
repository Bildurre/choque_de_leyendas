# Alanda: Choque de Leyendas - Web Project Documentation

## Project Overview
- **Framework**: Laravel 12.4
- **Frontend**: Blade + Alpine.js
- **Styling**: SCSS
- **Authentication**: Laravel Breeze (customized)
- **Philosophies and Principles**: SOLID, DRY, KISS, YAGNI, Composition Over Inheritance, Law of Demeter

## Implementation Roadmap

## Implemented Blade Components
- resources/views/components/sidebar/nav-link.blade.php
- resources/views/components/sidebar/section.blade.php
- resources/views/components/header-actions-bar.blade.php
- resources/views/components/form-card.blade.php
- resources/views/components/form/group.blade.php
- resources/views/components/form/input.blade.php
- resources/views/components/form/textarea.blade.php
- resources/views/components/form/select.blade.php
- resources/views/components/form/color-input.blade.php
- resources/views/components/entity-card.blade.php
- resources/views/components/no-entities.blade.php
- resources/views/components/alert.blade.php
- resources/views/components/game/attribute-modifier.blade.php
- resources/views/components/game/attribute-modifiers-grid.blade.php
- resources/views/components/game/hero-class-card.blade.php
- resources/views/components/game/faction-card.blade.php
- resources/views/components/game/superclass-card.blade.php

### Form Requests
- Added comprehensive form requests for validation:
  - StoreFactionRequest and UpdateFactionRequest
  - UpdateHeroAttributeConfigurationRequest
  - StoreHeroClassRequest and UpdateHeroClassRequest
  - StoreSuperclassRequest and UpdateSuperclassRequest

### Services Implementation
- Developed service classes to handle business logic:
  - FactionService
  - HeroAttributeConfigurationService
  - HeroClassService
  - ImageService
  - SuperclassService

### Traits Added
- HasColorAttribute: Provides color-related functionality
- HasGameStatistics: Adds statistical methods for game-related models
- HasImageAttribute: Manages image-related operations
- HasSlug: Generates slugs for models

### Superclass Management System
- Created Superclass model to manage hero superclasses (Luchador/Conjurador)
- Implemented migration and database schema for superclasses
- Modified HeroClass model to establish relationship with superclasses
- Added comprehensive CRUD operations for superclass management
- Developed SuperclassController with all necessary methods
- Created elegant user interface for superclass management:
  - Listing page with superclass cards showing details and associated hero classes
  - Create form with validation
  - Edit form with validation
  - Delete functionality with dependency checking
- Implemented seeder for initial superclasses (Luchador/Conjurador)
- Added JavaScript functionality for client-side validations and confirmations
- Created SCSS styles for superclass pages with responsive design considerations
- Updated existing hero class views to incorporate superclass relationship
- Added navigation link in admin sidebar for superclass management

### Hero Attribute Configuration System
- Created HeroAttributeConfiguration model to manage base hero attributes
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
- Created HeroClass model to manage hero classes
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
- Implemented superclass selection
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
- Enhanced routes for superclass management

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
- Superclass → HeroClasses (one-to-many)
- Decks → Heroes (many-to-many with copies attribute)
- Decks → Cards (many-to-many with copies attribute)
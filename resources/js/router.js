/**
 * Router - handles loading of appropriate module based on current URL path
 */
export function setupPageHandlers() {
  const path = window.location.pathname;
  console.log('Setting up page handlers for path:', path);
  
  // Check if we're in admin section
  if (path.startsWith('/admin')) {
    // Get module name from URL (e.g., /admin/factions -> 'factions')
    const pathParts = path.split('/');
    const module = pathParts[2]?.toLowerCase();
    
    if (!module) {
      console.log('No module segment found in path');
      return; // Exit if no module segment found
    }
    
    // Module mapping to handle kebab-case to camelCase conversion for import
    const moduleMapping = {
      'hero-classes': 'hero-classes',
      'hero-superclasses': 'hero-superclasses', 
      'hero-abilities': 'hero-abilities',
      'attack-types': 'attack-types',
      'attack-subtypes': 'attack-subtypes',
      'attack-ranges': 'attack-ranges',
      'hero-attributes-configurations': 'hero-attributes-configurations',
      'equipment-types': 'equipment-types',
      'card-types': 'card-types',
      // Add other mappings as needed
    };
    
    const modulePath = moduleMapping[module] || module;
    
    if (!modulePath) {
      console.log(`No module mapping found for ${module}`);
      return;
    }
    
    // Determine if we're on a CRUD page
    let action = 'index';
    
    if (pathParts.length > 3) {
      if (pathParts[3] === 'create') {
        action = 'create';
      } else if (pathParts[pathParts.length - 1] === 'edit') {
        action = 'edit';
      } else if (!isNaN(parseInt(pathParts[3])) && pathParts.length === 4) {
        action = 'show';
      }
    }
    
    console.log(`Loading module: ${modulePath}, action: ${action}`);
    
    // Use dynamic import to load the appropriate module
    import(`./admin/modules/${modulePath}.js`)
      .then(moduleHandler => {
        // Check if the module has the specific action handler
        if (typeof moduleHandler[action] === 'function') {
          console.log(`Executing ${action} handler for ${modulePath}`);
          moduleHandler[action]();
        } else if (typeof moduleHandler.default === 'function') {
          // Fall back to default handler
          console.log(`Executing default handler for ${modulePath} with action ${action}`);
          moduleHandler.default(action);
        } else {
          console.warn(`No handler found for ${action} in module ${modulePath}`);
        }
      })
      .catch(error => {
        console.error(`Error loading module ${modulePath}:`, error);
      });
  }
  
  // Event to notify that page handlers have been set up
  document.dispatchEvent(new CustomEvent('pageHandlersSet'));
}

// Initialize page handlers when DOM is loaded
document.addEventListener('DOMContentLoaded', setupPageHandlers);
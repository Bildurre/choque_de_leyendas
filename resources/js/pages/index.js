/**
 * Setup page-specific handlers based on current URL
 */
export function setupPageHandlers() {
  const path = window.location.pathname;
  
  // Base admin path
  if (path.startsWith('/admin')) {
    // Get module name from URL (e.g., /admin/factions -> 'factions')
    const module = path.split('/')[2]?.toLowerCase();
    
    if (!module) return; // Exit if no module segment found
    
    // Define known modules to prevent console errors for undefined ones
    const knownModules = [
      'factions',
      'hero-classes',
      'hero-superclasses', 
      'hero-abilities',
      'attack-types',
      'attack-subtypes',
      'attack-ranges',
      'hero-attributes-configurations',
      'heroes',
      'equipment-types',
      'card-types',
      'cards'
    ];
    
    if (!knownModules.includes(module)) return;
    
    // Determine if we're on a CRUD page
    const isCreate = path.includes('/create');
    const isEdit = path.includes('/edit');
    const isShow = path.includes('/show');
    const action = isCreate ? 'create' : (isEdit ? 'edit' : (isShow ? 'show' : 'index'));
    
    // Use Vite's import.meta.glob for dynamic imports
    const moduleFiles = import.meta.glob('./modules/*.js');
    const modulePath = `./modules/${module}.js`;

    if (moduleFiles[modulePath]) {
      moduleFiles[modulePath]()
        .then(moduleHandler => {
          // Check if the module has the specific action handler
          if (moduleHandler[action]) {
            moduleHandler[action]();
          } else if (moduleHandler.default) {
            // Fall back to default handler
            moduleHandler.default(action);
          }
        })
        .catch(error => {
          console.error(`Error loading module ${module}:`, error);
        });
    }
  }
}
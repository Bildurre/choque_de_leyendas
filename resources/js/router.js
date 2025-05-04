/**
 * Router - handles loading of appropriate module based on current URL path
 */
export function setupPageHandlers() {
  const path = window.location.pathname;
  
  // Check if we're in admin section
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
    
    // Use dynamic import to load the appropriate module
    import(`./admin/modules/${module}.js`)
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
  
  // Here you could add logic for other sections (game, content, etc.)
}
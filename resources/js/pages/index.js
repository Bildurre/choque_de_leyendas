// resources/js/pages/index.js
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
      'superclasses', 
      'hero-abilities',
      'attack-types',
      'attack-subtypes',
      'attack-ranges',
      'hero-attributes'
    ];
    
    if (!knownModules.includes(module)) return;
    
    // Determine if we're on a CRUD page
    const isCreate = path.includes('/create');
    const isEdit = path.includes('/edit');
    const isShow = path.includes('/show');
    const action = isCreate ? 'create' : (isEdit ? 'edit' : (isShow ? 'show' : 'index'));
    
    // Try to import module handler dynamically without causing console errors
    try {
      import(/* @vite-ignore */ `./modules/${module}.js`)
        .then(moduleHandler => {
          // Check if the module has the specific action handler
          if (moduleHandler[action]) {
            moduleHandler[action]();
          } else if (moduleHandler.default) {
            // Fall back to default handler
            moduleHandler.default(action);
          }
        })
        .catch(() => {
          // Silent fail - module might not exist but we don't want console errors
          // console.debug(`No handler found for module: ${module}`);
        });
    } catch (e) {
      // Silent fail to prevent console errors
    }
  }
}
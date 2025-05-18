export default function initLanguageTabs() {
  const languageTabs = document.querySelectorAll('.language-tabs');
  // Obtener el idioma activo de la aplicación del atributo lang en el HTML
  const currentAppLocale = document.documentElement.lang || 'es';
  
  languageTabs.forEach(tabsContainer => {
    const tabButtons = tabsContainer.querySelectorAll('.language-tabs__tab');
    const tabPanels = tabsContainer.querySelectorAll('.language-tabs__panel');
    const fieldName = tabsContainer.dataset.field;
    
    // Obtener la pestaña activa actualmente en el DOM (la que tiene la clase active)
    const getCurrentActiveLocaleFromDOM = () => {
      const activeTab = tabsContainer.querySelector('.language-tabs__tab--active');
      return activeTab ? activeTab.dataset.locale : null;
    };
    
    // Almacenar el estado de las pestañas en localStorage
    const saveTabState = (locale) => {
      // Guardar para todos los campos (global)
      localStorage.setItem('activeLocale', locale);
      // Guardar para este campo específico (por si acaso se necesita)
      localStorage.setItem(`activeTab-${fieldName}`, locale);
    };
    
    // Cargar el estado de las pestañas desde localStorage
    const loadTabState = () => {
      // Prioridad:
      // 1. Idioma activo en el DOM (el que se ha renderizado con --active)
      // 2. Estado guardado para este campo específico
      // 3. Estado global de idioma
      // 4. Idioma actual de la app
      const domActiveLocale = getCurrentActiveLocaleFromDOM();
      const fieldSpecificLocale = localStorage.getItem(`activeTab-${fieldName}`);
      const globalLocale = localStorage.getItem('activeLocale');
      
      return domActiveLocale || fieldSpecificLocale || globalLocale || currentAppLocale;
    };
    
    // Cambiar de pestaña
    const switchTab = (locale) => {
      // Desactivar todas las pestañas y paneles
      tabButtons.forEach(button => {
        button.classList.remove('language-tabs__tab--active');
      });
      
      tabPanels.forEach(panel => {
        panel.classList.remove('language-tabs__panel--active');
      });
      
      // Activar la pestaña y panel seleccionados
      const activeButton = tabsContainer.querySelector(`.language-tabs__tab[data-locale="${locale}"]`);
      const activePanel = tabsContainer.querySelector(`.language-tabs__panel[data-locale="${locale}"]`);
      
      if (activeButton && activePanel) {
        activeButton.classList.add('language-tabs__tab--active');
        activePanel.classList.add('language-tabs__panel--active');
        saveTabState(locale);
      }
    };
    
    // Primero verificamos si ya hay una pestaña activa en el DOM
    // Si no la hay, cargamos el estado guardado o el idioma actual
    if (!getCurrentActiveLocaleFromDOM()) {
      const savedLocale = loadTabState();
      if (savedLocale) {
        switchTab(savedLocale);
      }
    }
    
    // Agregar event listeners a las pestañas
    tabButtons.forEach(button => {
      button.addEventListener('click', () => {
        const locale = button.dataset.locale;
        switchTab(locale);
        
        // Sincronizar todas las pestañas de idioma en la página al mismo idioma
        document.querySelectorAll('.language-tabs').forEach(otherTabsContainer => {
          if (otherTabsContainer !== tabsContainer) {
            const otherLocaleButtons = otherTabsContainer.querySelectorAll('.language-tabs__tab');
            const otherLocalePanels = otherTabsContainer.querySelectorAll('.language-tabs__panel');
            
            // Desactivar todas las pestañas y paneles
            otherLocaleButtons.forEach(btn => {
              btn.classList.remove('language-tabs__tab--active');
            });
            
            otherLocalePanels.forEach(panel => {
              panel.classList.remove('language-tabs__panel--active');
            });
            
            // Activar la pestaña y panel del mismo idioma
            const otherActiveButton = otherTabsContainer.querySelector(`.language-tabs__tab[data-locale="${locale}"]`);
            const otherActivePanel = otherTabsContainer.querySelector(`.language-tabs__panel[data-locale="${locale}"]`);
            
            if (otherActiveButton && otherActivePanel) {
              otherActiveButton.classList.add('language-tabs__tab--active');
              otherActivePanel.classList.add('language-tabs__panel--active');
            }
          }
        });
      });
    });
  });
}
export default function initLanguageTabs() {
  const languageTabs = document.querySelectorAll('.language-tabs');
  
  languageTabs.forEach(tabsContainer => {
    const tabButtons = tabsContainer.querySelectorAll('.language-tabs__tab');
    const tabPanels = tabsContainer.querySelectorAll('.language-tabs__panel');
    const fieldName = tabsContainer.dataset.field;
    
    // Almacenar el estado de las pestañas en localStorage
    const saveTabState = (locale) => {
      localStorage.setItem(`activeTab-${fieldName}`, locale);
    };
    
    // Cargar el estado de las pestañas desde localStorage
    const loadTabState = () => {
      return localStorage.getItem(`activeTab-${fieldName}`);
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
    
    // Restaurar el estado de las pestañas al cargar la página
    const savedLocale = loadTabState();
    if (savedLocale) {
      switchTab(savedLocale);
    }
    
    // Agregar event listeners a las pestañas
    tabButtons.forEach(button => {
      button.addEventListener('click', () => {
        const locale = button.dataset.locale;
        switchTab(locale);
      });
    });
  });
}
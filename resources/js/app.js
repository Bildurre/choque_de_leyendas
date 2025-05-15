import './bootstrap';
import '../scss/app.scss';

import initHeaderScroll from './components/public-header';
import initThemeSwitcher from './components/theme-switcher';
import initRandomAccentColor from './components/random-accent-color';
import initSidebar from './components/admin-sidebar';
import initConfirmActions from './components/confirm-action';
import initNotifications from './components/notifications';
import initLanguageTabs from './components/language-tabs';
import initWysiwygEditor from './components/wysiwyg-editor';
import initReorderableLists from './components/reorderable-list';
import initCollapsibleSections from './components/collapsible-section';
import initAccordions from './components/accordion';
import initConditionalFields from './components/conditional-fields';
import initImageUpload from './components/image-upload';
import initAttributeConfigForm from './components/attribute-config-form';
import initCostInput from './components/cost-input';
import initColorPicker from './components/color-picker';
import initFactionDeckForm from './components/faction-deck-form';
import initEntitySelector from './components/entity-selector';
import initAdminFilters from './components/filters';
import initCostFilters from './components/filter-cost';

document.addEventListener('DOMContentLoaded', () => {
  // Primero los que no dependen de otros
  initThemeSwitcher();
  initRandomAccentColor();
  initConfirmActions();
  initNotifications();
  initLanguageTabs();
  initWysiwygEditor();
  initReorderableLists();
  initConditionalFields();
  initImageUpload();
  initAttributeConfigForm(); 
  initCostInput();
  initColorPicker();
  initFactionDeckForm();
  initEntitySelector();
  initAdminFilters();
  initCostFilters();
  
  // Inicializar los collapsibles antes que los acordeones
  initCollapsibleSections();
  // Después inicializar los acordeones (dependen de los collapsibles)
  setTimeout(() => {
    initAccordions();
  }, 0);
  
  // Inicializar componentes específicos si existen en la página
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebar) {
    initSidebar();
  }
});
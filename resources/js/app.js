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
import initCardForm from './components/card-form';
import initCardFilters from './components/card-filters';
import initHeroAbilityForm from './components/hero-ability-form';

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
  initCardForm();
  initCardFilters();
  initHeroAbilityForm();
  
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
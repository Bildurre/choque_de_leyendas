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

document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.public-header');
  if (header) {
    initHeaderScroll();
  }
  const sidebar = document.querySelector('.admin-sidebar');
  if (sidebar) {
    initSidebar();
  }
  
  initThemeSwitcher();
  initRandomAccentColor();
  initConfirmActions();
  initNotifications();
  initLanguageTabs();
  initWysiwygEditor();
  initReorderableLists();
  initCollapsibleSections();
  initAccordions();
});
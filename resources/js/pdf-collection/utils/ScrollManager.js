// resources/js/pdf-collection/utils/ScrollManager.js
export default class ScrollManager {
  scrollToSection(section, offset = 128) {
    const rect = section.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const targetPosition = rect.top + scrollTop - offset;
    
    window.scrollTo({
      top: targetPosition,
      behavior: 'smooth'
    });
  }
}
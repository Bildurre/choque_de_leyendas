export default class EntityCardToggle {
  /**
   * Initialize toggle functionality for entity cards
   */
  static init() {
    const toggleButtons = document.querySelectorAll('[data-toggle="entity-details"]');
    
    toggleButtons.forEach(button => {
      button.addEventListener('click', function() {
        const card = this.closest('.entity-card');
        card.classList.toggle('expanded');
      });
    });
  }
}
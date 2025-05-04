/**
 * Entity Card Component - Handles card toggles and interactions
 */
export function initEntityCards() {
  // Handle expand/collapse toggles
  const toggleButtons = document.querySelectorAll('[data-toggle="entity-details"]');
  
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const card = this.closest('.entity-card');
      if (card) {
        card.classList.toggle('expanded');
      }
    });
  });
}
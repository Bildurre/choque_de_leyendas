document.addEventListener('DOMContentLoaded', () => {
  const toggleButtons = document.querySelectorAll('[data-toggle="entity-details"]');
  
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const card = this.closest('.entity-card');
      card.classList.toggle('expanded');
    });
  });
});
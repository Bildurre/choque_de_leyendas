/**
 * Superclasses index page functionality
 */
document.addEventListener('DOMContentLoaded', function() {
  // Manejo de la confirmación para eliminar superclase
  const deleteButtons = document.querySelectorAll('.delete-btn');
  
  deleteButtons.forEach(button => {
    button.addEventListener('click', function(event) {
      event.preventDefault();
      
      const superclassName = this.getAttribute('data-superclass-name');
      const heroClassCount = parseInt(this.getAttribute('data-hero-class-count') || '0');
      
      if (heroClassCount > 0) {
        alert(`No se puede eliminar la superclase "${superclassName}" porque está siendo utilizada por ${heroClassCount} clases de héroe.`);
        return;
      }
      
      if (confirm(`¿Estás seguro de querer eliminar la superclase "${superclassName}"?`)) {
        // Si el usuario confirma, enviamos el formulario
        this.closest('form').submit();
      }
    });
  });
  
  // Ocultar alertas después de 5 segundos
  const alerts = document.querySelectorAll('.alert');
  
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => {
        alert.style.display = 'none';
      }, 300);
    }, 5000);
  });
});
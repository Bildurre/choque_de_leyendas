document.addEventListener('DOMContentLoaded', function() {
  // Manejo de la confirmación para eliminar facción
  const deleteButtons = document.querySelectorAll('.delete-btn');
  
  deleteButtons.forEach(button => {
    button.addEventListener('click', function(event) {
      event.preventDefault();
      
      const factionId = this.getAttribute('data-faction-id');
      const factionName = this.getAttribute('data-faction-name');
      
      if (confirm(`¿Estás seguro de querer eliminar la facción "${factionName}"?`)) {
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
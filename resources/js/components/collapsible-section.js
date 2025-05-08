export default function initCollapsibleSections() {
  const toggleButtons = document.querySelectorAll('.collapsible-section__toggle');
  
  toggleButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const target = document.getElementById(targetId);
      
      if (!target) return;
      
      const isCollapsed = target.classList.toggle('is-collapsed');
      
      // Cambiar el icono del botón
      const icon = this.querySelector('.icon');
      if (icon) {
        if (isCollapsed) {
          icon.classList.remove('icon--chevron-up');
          icon.classList.add('icon--chevron-down');
        } else {
          icon.classList.remove('icon--chevron-down');
          icon.classList.add('icon--chevron-up');
        }
      }
      
      // Guardar estado en localStorage
      localStorage.setItem(`section-${targetId}`, isCollapsed ? 'collapsed' : 'expanded');
    });
  });
  
  // Inicializar estados desde localStorage
  document.querySelectorAll('.collapsible-section').forEach(section => {
    const id = section.id;
    const savedState = localStorage.getItem(`section-${id}`);
    
    if (savedState === 'collapsed') {
      section.classList.add('is-collapsed');
      
      // Actualizar el icono del botón
      const button = document.querySelector(`[data-target="${id}"]`);
      if (button) {
        const icon = button.querySelector('.icon');
        if (icon) {
          icon.classList.remove('icon--chevron-up');
          icon.classList.add('icon--chevron-down');
        }
      }
    }
  });
}
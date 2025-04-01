import Alpine from 'alpinejs'

// Configuración inicial de Alpine
document.addEventListener('alpine:init', () => {
  // Detectar cambios de tamaño de ventana para sidebar
  // window.addEventListener('resize', () => {
  //   if (window.innerWidth > 768) {
  //     document.body.classList.add('sidebar-open');
  //   } else {
  //     document.body.classList.remove('sidebar-open');
  //   }
  // });
});

window.Alpine = Alpine;
Alpine.start();
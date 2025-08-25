/**
 * Componente reutilizable para botón hamburguesa
 * Maneja la lógica común de toggle, accesibilidad y eventos
 */
export default function createHamburgerButton(options = {}) {
  // Configuración por defecto unificada basada en los layouts
  const config = {
    toggleButtonId: 'sidebar-toggle',
    // Configuración automática basada en el contexto
    context: 'auto', // 'auto', 'admin', 'public'
    breakpoint: 768, // px para tablet/desktop
    closeOnOutsideClick: true,
    closeOnEscape: true,
    closeOnResize: true,
    useLocalStorage: false,
    localStorageKey: 'menuVisible',
    ...options
  };

  // Auto-detectar contexto si no se especifica
  if (config.context === 'auto') {
    if (document.querySelector('.admin-layout')) {
      config.context = 'admin';
    } else if (document.querySelector('.public-header')) {
      config.context = 'public';
    }
  }

  // Configuración específica por contexto
  const contextConfigs = {
    admin: {
      targetSelector: '.admin-layout',
      activeClass: 'sidebar-visible',
      useLocalStorage: true,
      localStorageKey: 'sidebarVisible',
      autoHideOnDesktop: true,
      additionalCloseSelectors: ['.admin-sidebar']
    },
    public: {
      targetSelector: '.header-nav',
      activeClass: 'is-open',
      useLocalStorage: false,
      autoHideOnDesktop: true,
      additionalCloseSelectors: ['.header-nav']
    }
  };

  // Aplicar configuración del contexto
  const contextConfig = contextConfigs[config.context] || contextConfigs.public;
  Object.assign(config, contextConfig);

  // Estado interno
  let isOpen = false;
  let toggleButton = null;
  let target = null;
  let resizeTimer = null;

  /**
   * Verificar si estamos en viewport de tablet o superior
   */
  function isTabletOrLarger() {
    return window.innerWidth >= config.breakpoint;
  }

  /**
   * Abrir el menú
   */
  function open() {
    isOpen = true;
    toggleButton.setAttribute('aria-expanded', 'true');
    
    // Añadir clase activa al botón para animación CSS
    toggleButton.classList.add('sidebar-toggle--active');
    
    if (target) {
      target.classList.add(config.activeClass);
    }

    saveState();
    onOpen();
  }

  /**
   * Cerrar el menú
   */
  function close() {
    isOpen = false;
    toggleButton.setAttribute('aria-expanded', 'false');
    
    // Remover clase activa del botón para animación CSS
    toggleButton.classList.remove('sidebar-toggle--active');
    
    if (target) {
      target.classList.remove(config.activeClass);
    }

    saveState();
    onClose();
  }

  /**
   * Toggle del menú
   */
  function toggle() {
    if (isOpen) {
      close();
    } else {
      open();
    }
  }

  /**
   * Guardar estado en localStorage
   */
  function saveState() {
    if (config.useLocalStorage && !isTabletOrLarger()) {
      localStorage.setItem(config.localStorageKey, isOpen ? 'true' : 'false');
    }
  }

  /**
   * Cargar estado inicial
   */
  function loadInitialState() {
    if (config.useLocalStorage && !isTabletOrLarger()) {
      const savedState = localStorage.getItem(config.localStorageKey);
      if (savedState === 'true') {
        open();
      }
    }
  }

  /**
   * Manejar cambio de tamaño de ventana
   */
  function handleResize() {
    if (isTabletOrLarger()) {
      if (config.autoHideOnDesktop || isOpen) {
        close();
      }
    } else if (config.closeOnResize && config.context === 'admin') {
      // Solo para admin: siempre ocultar en móvil al cambiar tamaño
      close();
    }
  }

  /**
   * Hook llamado cuando se abre el menú
   */
  function onOpen() {
    if (typeof config.onOpen === 'function') {
      config.onOpen();
    }

    // Lógica específica por contexto
    if (config.context === 'public') {
      // Asegurar que el header sea visible cuando el menú está abierto
      const header = document.querySelector('.public-header');
      if (header) {
        header.classList.remove('header--hidden');
      }
    }
  }

  /**
   * Hook llamado cuando se cierra el menú
   */
  function onClose() {
    if (typeof config.onClose === 'function') {
      config.onClose();
    }
  }

  /**
   * Vincular eventos
   */
  function bindEvents() {
    // Evento click del botón
    toggleButton.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      toggle();
    });

    // Evento resize con debounce
    if (config.closeOnResize) {
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleResize, 250);
      });
    }

    // Cerrar con Escape
    if (config.closeOnEscape) {
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen) {
          close();
        }
      });
    }

    // Cerrar al hacer click fuera
    if (config.closeOnOutsideClick) {
      document.addEventListener('click', (e) => {
        if (isOpen && 
            !toggleButton.contains(e.target) && 
            (!target || !target.contains(e.target))) {
          
          // Verificación adicional para selectores específicos del contexto
          const shouldClose = config.additionalCloseSelectors?.every(selector => {
            const element = document.querySelector(selector);
            return !element || !element.contains(e.target);
          }) ?? true;

          if (shouldClose) {
            // Para admin: solo en móvil
            if (config.context === 'admin' && isTabletOrLarger()) {
              return;
            }
            close();
          }
        }
      });
    }

    // Eventos específicos del contexto público
    if (config.context === 'public') {
      const navLinks = document.querySelectorAll('.nav-link, .nav-dropdown__link');
      navLinks.forEach(link => {
        link.addEventListener('click', () => {
          if (isOpen && !isTabletOrLarger()) {
            close();
          }
        });
      });
    }
  }

  /**
   * Inicializar el componente
   */
  function init() {
    toggleButton = document.getElementById(config.toggleButtonId);
    
    if (!toggleButton) {
      console.warn(`Hamburger button with id "${config.toggleButtonId}" not found`);
      return null;
    }

    if (config.targetSelector) {
      target = document.querySelector(config.targetSelector);
      if (!target) {
        console.warn(`Target element "${config.targetSelector}" not found`);
        return null;
      }
    }

    bindEvents();
    handleResize();
    loadInitialState();

    // API pública
    return {
      open,
      close,
      toggle,
      isOpen: () => isOpen,
      isTabletOrLarger,
      destroy() {
        if (resizeTimer) {
          clearTimeout(resizeTimer);
        }
      }
    };
  }

  // Inicializar y retornar API
  return init();
}
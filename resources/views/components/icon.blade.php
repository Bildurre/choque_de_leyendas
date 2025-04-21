{{-- 
 Iconos disponibles:
 
 - edit: Icono de edición (lápiz)
 - delete: Icono de eliminación (papelera)
 - heroes: Icono de héroes (persona)
 - abilities: Icono de habilidades (rayo)
 - cards: Icono de cartas (cartas/documentos)
 - subtypes: Icono de subtipos (flechas/organización)
 - chevron-down: Icono de flecha hacia abajo (para expandir)
 - chevron-up: Icono de flecha hacia arriba (para colapsar)
 - chevron-left: Icono de flecha hacia la izquierda
 - chevron-right: Icono de flecha hacia la derecha
 - view: Icono de visualización (ojo)
 - plus: Icono para añadir (signo +)
 - settings: Icono de configuración (engranaje)
 - search: Icono de búsqueda (lupa)
 - download: Icono de descarga (flecha hacia abajo con línea)
 - dashboard: Icono de panel (cuadrícula)
 - rules: Icono de reglas (libro abierto)
 - stats: Icono de estadísticas (gráfico de barras)
 - export: Icono de exportación (flecha hacia arriba)
 - arrow-left: Icono de flecha hacia la izquierda
 - upload: Icono de subida al servidor (flecha hacia arriba con línea)
--}}

@props(['name', 'size' => '1rem'])

@php
  $paths = [
    'edit' => '<path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path>',
    
    'delete' => '<path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>',
    
    'heroes' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>',
    
    'abilities' => '<path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>',
    
    'cards' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>',
    
    'subtypes' => '<path d="M20 20v-8l-8 8M8 16H4v-4"></path><path d="M4 4h16v4M12 12v-4"></path>',
    
    'chevron-down' => '<polyline points="6 9 12 15 18 9"></polyline>',
    
    'chevron-up' => '<polyline points="18 15 12 9 6 15"></polyline>',
    
    'chevron-left' => '<polyline points="15 18 9 12 15 6"></polyline>',
    
    'chevron-right' => '<polyline points="9 18 15 12 9 6"></polyline>',
    
    'view' => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>',
    
    'plus' => '<line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line>',
    
    'settings' => '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>',
    
    'search' => '<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>',
    
    'download' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line>',
    
    'dashboard' => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
    
    'rules' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>',
    
    'stats' => '<line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line>',
    
    'export' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>',

    'arrow-left' => '<line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>',
    
    'upload' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line>',
  ];
  
  $iconContent = $paths[$name] ?? '';
  $classes = "icon icon--{$name}";
@endphp



@if(isset($paths[$name]))
  <svg xmlns="http://www.w3.org/2000/svg" 
       width="{{ $size }}" 
       height="{{ $size }}" 
       viewBox="0 0 24 24" 
       fill="none" 
       stroke="currentColor" 
       stroke-width="2" 
       stroke-linecap="round" 
       stroke-linejoin="round"
       class="{{ $classes }}">{!! $iconContent !!}</svg>
@else
  {{ $slot }}
@endif
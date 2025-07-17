export default function initRandomAccentColor() {
  const baseColors = [
    {
      name: 'green',
      main: '#29ab5f',
      light: '#5fcb8a',
      dark: '#1a7440',
      // Diferentes niveles de transparencia para cada color
      bgLight: 'rgba(41, 171, 95, 0.2)',  // light opacity
      bgSemi: 'rgba(41, 171, 95, 0.5)',   // semi opacity
      bgHard: 'rgba(41, 171, 95, 0.8)',   // hard opacity
      rgb: '41, 171, 95'
    },
    {
      name: 'red',
      main: '#f15959',
      light: '#f58080',
      dark: '#c62121',
      // Diferentes niveles de transparencia para cada color
      bgLight: 'rgba(241, 89, 89, 0.2)',  // light opacity
      bgSemi: 'rgba(241, 89, 89, 0.5)',   // semi opacity
      bgHard: 'rgba(241, 89, 89, 0.8)',   // hard opacity
      rgb: '241, 89, 89'
    },
    {
      name: 'blue',
      main: '#408cfd',
      light: '#6fadfe',
      dark: '#195ec0',
      // Diferentes niveles de transparencia para cada color
      bgLight: 'rgba(64, 140, 253, 0.2)',  // light opacity
      bgSemi: 'rgba(64, 140, 253, 0.5)',   // semi opacity
      bgHard: 'rgba(64, 140, 253, 0.8)',   // hard opacity
      rgb: '64, 140, 253'
    }
  ];
  
  // Función para mezclar aleatoriamente un array
  function shuffleArray(array) {
    const shuffled = [...array];
    for (let i = shuffled.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }
    return shuffled;
  }
  
  // Mezclamos los colores aleatoriamente
  const randomizedColors = shuffleArray(baseColors);
  
  // Asignamos los colores a primary, secondary y tertiary
  const [primaryColor, secondaryColor, tertiaryColor] = randomizedColors;
  
  // Aplicar a las variables CSS
  const root = document.documentElement;
  
  // Primary color (mantenemos compatibilidad con código existente)
  root.style.setProperty('--random-accent-color', primaryColor.main);
  root.style.setProperty('--random-accent-color-hover', primaryColor.light);
  root.style.setProperty('--random-accent-color-dark', primaryColor.dark);
  root.style.setProperty('--random-accent-color-bg-light', primaryColor.bgLight);
  root.style.setProperty('--random-accent-color-bg-semi', primaryColor.bgSemi);
  root.style.setProperty('--random-accent-color-bg-hard', primaryColor.bgHard);
  root.style.setProperty('--random-accent-color-bg', primaryColor.bgLight); // compatibilidad
  root.style.setProperty('--random-accent-color-rgb', primaryColor.rgb);
  
  // Secondary color
  root.style.setProperty('--random-accent-color-secondary', secondaryColor.main);
  root.style.setProperty('--random-accent-color-secondary-hover', secondaryColor.light);
  root.style.setProperty('--random-accent-color-secondary-dark', secondaryColor.dark);
  root.style.setProperty('--random-accent-color-secondary-bg-light', secondaryColor.bgLight);
  root.style.setProperty('--random-accent-color-secondary-bg-semi', secondaryColor.bgSemi);
  root.style.setProperty('--random-accent-color-secondary-bg-hard', secondaryColor.bgHard);
  root.style.setProperty('--random-accent-color-secondary-bg', secondaryColor.bgLight); // compatibilidad
  root.style.setProperty('--random-accent-color-secondary-rgb', secondaryColor.rgb);
  
  // Tertiary color
  root.style.setProperty('--random-accent-color-tertiary', tertiaryColor.main);
  root.style.setProperty('--random-accent-color-tertiary-hover', tertiaryColor.light);
  root.style.setProperty('--random-accent-color-tertiary-dark', tertiaryColor.dark);
  root.style.setProperty('--random-accent-color-tertiary-bg-light', tertiaryColor.bgLight);
  root.style.setProperty('--random-accent-color-tertiary-bg-semi', tertiaryColor.bgSemi);
  root.style.setProperty('--random-accent-color-tertiary-bg-hard', tertiaryColor.bgHard);
  root.style.setProperty('--random-accent-color-tertiary-bg', tertiaryColor.bgLight); // compatibilidad
  root.style.setProperty('--random-accent-color-tertiary-rgb', tertiaryColor.rgb);
  
  // Actualizar los elementos del logo que utilicen la clase logo-path
  const logoPaths = document.querySelectorAll('.logo-path');
  if (logoPaths.length > 0) {
    logoPaths.forEach(path => {
      path.setAttribute('fill', primaryColor.main);
    });
  }
}
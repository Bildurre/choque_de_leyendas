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
  
  // Función para elegir un color aleatorio
  function getRandomColorSet() {
    const randomIndex = Math.floor(Math.random() * baseColors.length);
    return baseColors[randomIndex];
  }
  
  // Elegimos un color aleatorio cada vez
  const randomColorSet = getRandomColorSet();
  
  // Aplicar a las variables CSS para random-accent-color
  const root = document.documentElement;
  root.style.setProperty('--random-accent-color', randomColorSet.main);
  root.style.setProperty('--random-accent-color-hover', randomColorSet.light);
  root.style.setProperty('--random-accent-color-dark', randomColorSet.dark);
  
  // Establecer todas las variables transparentes
  root.style.setProperty('--random-accent-color-bg-light', randomColorSet.bgLight);
  root.style.setProperty('--random-accent-color-bg-semi', randomColorSet.bgSemi);
  root.style.setProperty('--random-accent-color-bg-hard', randomColorSet.bgHard);
  
  // Para compatibilidad con código existente
  root.style.setProperty('--random-accent-color-bg', randomColorSet.bgLight);
  
  root.style.setProperty('--random-accent-color-rgb', randomColorSet.rgb);
  
  // Actualizar los elementos del logo que utilicen la clase logo-path
  const logoPaths = document.querySelectorAll('.logo-path');
  if (logoPaths.length > 0) {
    logoPaths.forEach(path => {
      path.setAttribute('fill', randomColorSet.main);
    });
  }
}
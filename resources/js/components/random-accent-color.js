// resources/js/components/random-accent-color.js
export default function initRandomAccentColor() {
  const baseColors = [
    {
      name: 'green',
      main: '#29ab5f',
      light: '#5fcb8a',
      dark: '#1a7440',
      bg: 'rgba(41, 171, 95, 0.1)',
      rgb: '41, 171, 95'
    },
    {
      name: 'red',
      main: '#f15959',
      light: '#f58080',
      dark: '#c62121',
      bg: 'rgba(241, 89, 89, 0.1)',
      rgb: '241, 89, 89'
    },
    {
      name: 'blue',
      main: '#408cfd',
      light: '#6fadfe',
      dark: '#195ec0',
      bg: 'rgba(64, 140, 253, 0.1)',
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
  root.style.setProperty('--random-accent-color-bg', randomColorSet.bg);
  root.style.setProperty('--random-accent-color-rgb', randomColorSet.rgb);
  
  // Actualizar los elementos del logo que utilicen la clase logo-path
  const logoPaths = document.querySelectorAll('.logo-path');
  if (logoPaths.length > 0) {
    logoPaths.forEach(path => {
      path.setAttribute('fill', randomColorSet.main);
    });
  }
}
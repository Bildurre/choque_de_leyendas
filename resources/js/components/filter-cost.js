export default function initCostFilters() {
  // Buscar todos los selectores de coste
  const costSelects = document.querySelectorAll('.filter-cost-select select');
  
  // Función para transformar un texto de coste en HTML
  function transformCostToHtml(costText) {
    if (!costText) return '';
    
    let html = '';
    for (let i = 0; i < costText.length; i++) {
      const dice = costText[i].toUpperCase();
      let colorClass = '';
      
      if (dice === 'R') colorClass = 'cost-option__dice--r';
      else if (dice === 'G') colorClass = 'cost-option__dice--g';
      else if (dice === 'B') colorClass = 'cost-option__dice--b';
      
      html += `<span class="cost-option__dice ${colorClass}">${dice}</span>`;
    }
    
    return `<div class="cost-option">${html}</div>`;
  }
  
  // Para cada select de coste, transformar sus opciones
  costSelects.forEach(select => {
    const observer = new MutationObserver(mutations => {
      mutations.forEach(mutation => {
        if (mutation.type === 'childList') {
          // Buscar todos los elementos de Choices.js que necesitan transformación
          const choicesItems = document.querySelectorAll('.choices__item');
          choicesItems.forEach(item => {
            const costText = item.textContent.trim();
            if (/^[RGB]+$/i.test(costText) && !item.querySelector('.cost-option')) {
              item.innerHTML = transformCostToHtml(costText);
            }
          });
        }
      });
    });
    
    // Observar cambios en el DOM para transformar los elementos cuando se creen
    observer.observe(document.body, {
      childList: true, 
      subtree: true
    });
  });
}
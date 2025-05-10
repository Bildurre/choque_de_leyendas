export default function initHeroAbilityForm() {
  const abilityForms = document.querySelectorAll('form.form');
  
  if (!abilityForms.length) return;
  
  abilityForms.forEach(form => {
    // Solo procesar formularios de habilidades (verificamos por elementos específicos)
    const attackRangeSelect = form.querySelector('#attack_range_id');
    const attackSubtypeSelect = form.querySelector('#attack_subtype_id');
    
    // Si no tiene elementos específicos de habilidades, no es un formulario de habilidades
    if (!attackRangeSelect || !attackSubtypeSelect) return;
    
    const areaContainer = form.querySelector('#area-container');
    
    // Función para actualizar la visibilidad del campo 'area'
    function updateAreaVisibility() {
      const attackRangeId = attackRangeSelect.value;
      const attackSubtypeId = attackSubtypeSelect.value;
      
      // Mostrar el campo 'area' solo si se han seleccionado tanto el rango como el subtipo
      if (attackRangeId !== '' && attackSubtypeId !== '') {
        areaContainer.style.display = 'block';
      } else {
        areaContainer.style.display = 'none';
      }
    }
    
    // Sincronizar los selectores de ataque
    function syncAttackSelects(triggered) {
      const translations = window.translations || {};
      const warningMessages = translations.hero_abilities || {
        select_attack_subtype_warning: 'Debes seleccionar también un subtipo de ataque',
        select_attack_range_warning: 'Debes seleccionar primero un rango de ataque'
      };
      
      if (triggered === 'range') {
        // Si se seleccionó un rango pero no hay subtipo, alertar
        if (attackRangeSelect.value !== '' && attackSubtypeSelect.value === '') {
          alert(warningMessages.select_attack_subtype_warning);
        }
      } else if (triggered === 'subtype') {
        // Si se seleccionó un subtipo pero no hay rango, alertar
        if (attackSubtypeSelect.value !== '' && attackRangeSelect.value === '') {
          alert(warningMessages.select_attack_range_warning);
        }
      }
      
      updateAreaVisibility();
    }
    
    // Asignar eventos
    attackRangeSelect.addEventListener('change', function() {
      syncAttackSelects('range');
    });
    attackSubtypeSelect.addEventListener('change', function() {
      syncAttackSelects('subtype');
    });
    
    // Inicializar estado
    updateAreaVisibility();
  });
}
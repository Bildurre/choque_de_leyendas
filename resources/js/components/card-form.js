export default function initCardForm() {
  // Seleccionar formulario de cartas
  const cardForms = document.querySelectorAll('form.form');
  
  // Solo continuar si hay algún formulario de cartas en la página
  if (!cardForms.length) return;
  
  cardForms.forEach(form => {
    // Solo procesar formularios de carta (verificamos por elementos específicos)
    const equipmentTypeSelect = form.querySelector('#equipment_type_id');
    const attackRangeSelect = form.querySelector('#attack_range_id');
    const attackSubtypeSelect = form.querySelector('#attack_subtype_id');
    
    // Si no tiene elementos específicos de cartas, no es un formulario de cartas
    if (!equipmentTypeSelect || !attackRangeSelect || !attackSubtypeSelect) return;
    
    const handsContainer = form.querySelector('#hands-container');
    const areaContainer = form.querySelector('#area-container');
    
    // Función para actualizar la visibilidad del campo 'hands'
    function updateHandsVisibility() {
      const equipmentTypeId = equipmentTypeSelect.value;
      
      if (equipmentTypeId === '') {
        handsContainer.style.display = 'none';
        return;
      }
      
      // Obtener el tipo de equipo seleccionado
      try {
        const equipmentTypesData = JSON.parse(
          form.querySelector('[data-equipment-types]')?.dataset.equipmentTypes || '{}'
        );
        
        const equipmentType = equipmentTypesData[equipmentTypeId];
        
        // Mostrar el campo 'hands' solo si es un arma
        if (equipmentType && equipmentType.category === 'weapon') {
          handsContainer.style.display = 'block';
        } else {
          handsContainer.style.display = 'none';
        }
      } catch (e) {
        console.error('Error al procesar datos de tipos de equipo:', e);
      }
    }
    
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
      const warningMessages = translations.cards || {
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
    equipmentTypeSelect.addEventListener('change', updateHandsVisibility);
    attackRangeSelect.addEventListener('change', function() {
      syncAttackSelects('range');
    });
    attackSubtypeSelect.addEventListener('change', function() {
      syncAttackSelects('subtype');
    });
    
    // Inicializar estados
    updateHandsVisibility();
    updateAreaVisibility();
  });
}
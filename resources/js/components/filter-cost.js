import Choices from 'choices.js';

export default function initCostFilters() {
  // Find all cost filter selects
  const costFilterSelects = document.querySelectorAll('[data-cost-filter="true"]');
  
  costFilterSelects.forEach(select => {
    const filterType = select.getAttribute('data-cost-filter-type') || 'cost_exact';
    
    // Create custom rendering functions for Choices.js
    const choices = new Choices(select, {
      removeItemButton: true,
      itemSelectText: window.translations?.forms?.choices?.itemSelect || '',
      noResultsText: window.translations?.forms?.choices?.noResults || 'No results found',
      noChoicesText: window.translations?.forms?.choices?.noChoices || 'No choices to choose from',
      placeholderValue: select.getAttribute('placeholder') || window.translations?.forms?.choices?.placeholder || 'Select options...',
      shouldSort: false, // IMPORTANTE: Mantener el orden original de las opciones
      sortFilter: () => 0, // Desactivar cualquier ordenamiento
      
      // Custom rendering for choice items (dropdown options)
      callbackOnCreateTemplates: function(template) {
        const {
          item,
          itemChoice,
          itemSelectable,
          itemDisabled,
          itemHighlighted,
          placeholder,
          group,
          groupHeading,
          button,
          activeState,
          focusState,
          openState,
          disabledState,
          highlightedState,
          selectedState,
          flippedState,
          loadingState,
          noResults,
          noChoices
        } = this.config.classNames;
        
        return {
          choice: (classNames, data) => {
            const { itemSelectText } = this.config;
            const classes = data.disabled 
              ? `${item} ${itemChoice} ${itemDisabled}` 
              : `${item} ${itemChoice} ${itemSelectable}`;
            const disabled = data.disabled 
              ? 'data-choice-disabled aria-disabled="true"' 
              : 'data-choice-selectable';
            
            return template(`
              <div class="${classes}" data-select-text="${itemSelectText}" data-choice ${disabled} data-id="${data.id}" data-value="${data.value}" ${
                data.groupId > 0 ? 'role="treeitem"' : 'role="option"'
              }>
                ${renderCostDice(data.value, data.label, filterType)}
              </div>
            `);
          },
          
          // Custom rendering for selected items
          item: (classNames, data) => {
            const {
              item,
              button,
              highlightedState,
              itemSelectable,
              placeholder
            } = this.config.classNames;
            
            const { removeItemButton } = this.config;
            
            return template(`
              <div class="${item} ${
                data.highlighted
                  ? highlightedState
                  : itemSelectable
              }" data-item data-id="${data.id}" data-value="${data.value}" ${
                data.active ? 'aria-selected="true"' : ''
              } ${data.disabled ? 'aria-disabled="true"' : ''}>
                ${renderCostDice(data.value, data.label, filterType)}
                ${
                  removeItemButton
                    ? `<button type="button" class="${button}" aria-label="Remove item: '${data.value}'" data-button>Remove item</button>`
                    : ''
                }
              </div>
            `);
          },
        };
      },
    });
    
    // Close dropdown after selection
    select.addEventListener('choice', function() {
      setTimeout(() => {
        choices.hideDropdown();
      }, 100);
    });
  });
}

/**
 * Render cost dice SVG icons
 * @param {string} cost - The cost string (e.g., "RRG")
 * @param {string} label - The label text
 * @param {string} filterType - The type of filter ('cost_exact' or 'cost_colors')
 * @returns {string} HTML string with dice icons
 */
function renderCostDice(cost, label, filterType = 'cost_exact') {
  // Handle "no cost" option with empty dice (only for cost_exact)
  if ((!cost || cost === '') && filterType === 'cost_exact') {
    const emptyDiceHtml = `
      <span class="icon-dice icon-dice--empty icon-dice--xs">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="10 10 180 180" stroke-linejoin="round">
          <polygon 
            points="100,180 30,140 30,60 100,100" 
            class="icon-dice__face"
            stroke="black" 
            stroke-width="4"
            fill="none"
          />
          <polygon 
            points="100,180 100,100 170,60 170,140" 
            class="icon-dice__face"
            stroke="black" 
            stroke-width="4"
            fill="none"
          />
          <polygon 
            points="100,100 30,60 100,20 170,60" 
            class="icon-dice__face"
            stroke="black" 
            stroke-width="4"
            fill="none"
          />
        </svg>
      </span>
    `;
    return `<span class="cost-filter-option">${emptyDiceHtml}</span>`;
  }
  
  let diceHtml = '';
  
  // For cost_colors, always render just one dice of each color
  if (filterType === 'cost_colors') {
    const dice = cost.toUpperCase();
    let colorClass = '';
    
    switch (dice) {
      case 'R':
        colorClass = 'red';
        break;
      case 'G':
        colorClass = 'green';
        break;
      case 'B':
        colorClass = 'blue';
        break;
      default:
        return `<span class="cost-filter-option__text">${label}</span>`;
    }
    
    diceHtml = `
      <span class="icon-dice icon-dice--${colorClass} icon-dice--xs">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="10 10 180 180" stroke-linejoin="round">
          <polygon 
            points="100,180 30,140 30,60 100,100" 
            class="icon-dice__face"
            stroke="black" 
            stroke-width="4"
          />
          <polygon 
            points="100,180 100,100 170,60 170,140" 
            class="icon-dice__face"
            stroke="black" 
            stroke-width="4"
          />
          <polygon 
            points="100,100 30,60 100,20 170,60" 
            class="icon-dice__face"
            stroke="black" 
            stroke-width="4"
          />
        </svg>
      </span>
    `;
  } else {
    // For cost_exact, render all dice in the string
    for (let i = 0; i < cost.length; i++) {
      const dice = cost[i].toUpperCase();
      let colorClass = '';
      
      switch (dice) {
        case 'R':
          colorClass = 'red';
          break;
        case 'G':
          colorClass = 'green';
          break;
        case 'B':
          colorClass = 'blue';
          break;
        default:
          continue;
      }
      
      diceHtml += `
        <span class="icon-dice icon-dice--${colorClass} icon-dice--xs">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="10 10 180 180" stroke-linejoin="round">
            <polygon 
              points="100,180 30,140 30,60 100,100" 
              class="icon-dice__face"
              stroke="black" 
              stroke-width="4"
            />
            <polygon 
              points="100,180 100,100 170,60 170,140" 
              class="icon-dice__face"
              stroke="black" 
              stroke-width="4"
            />
            <polygon 
              points="100,100 30,60 100,20 170,60" 
              class="icon-dice__face"
              stroke="black" 
              stroke-width="4"
            />
          </svg>
        </span>
      `;
    }
  }
  
  return `<span class="cost-filter-option">${diceHtml}</span>`;
}
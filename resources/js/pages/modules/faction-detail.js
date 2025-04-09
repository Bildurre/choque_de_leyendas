export default function factionDetailHandler(action) {
  if (action === 'show') {
    initFactionCharts();
  }
}

export function show() {
  initFactionCharts();
}

/**
 * Initialize charts for faction detail page
 */
function initFactionCharts() {
  // Check if faction-cards-chart element exists
  const cardsChartEl = document.getElementById('factionCardsChart');
  if (!cardsChartEl) return;
  
  // Get chart data from data attribute
  const chartDataStr = cardsChartEl.getAttribute('data-chart');
  if (!chartDataStr) return;
  
  try {
    const chartData = JSON.parse(chartDataStr);
    
    // Import Chart.js dynamically
    import('chart.js/auto').then((ChartModule) => {
      const Chart = ChartModule.default;
      new Chart(cardsChartEl, {
        type: 'bar',
        data: {
          labels: chartData.labels,
          datasets: [{
            label: 'Cartas por Tipo',
            data: chartData.data,
            backgroundColor: chartData.backgroundColors,
            borderColor: chartData.borderColors,
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(255, 255, 255, 0.1)'
              },
              ticks: {
                color: '#e0e0e0'
              }
            },
            x: {
              grid: {
                color: 'rgba(255, 255, 255, 0.1)'
              },
              ticks: {
                color: '#e0e0e0'
              }
            }
          },
          plugins: {
            legend: {
              labels: {
                color: '#e0e0e0'
              }
            }
          }
        }
      });
    }).catch(error => {
      console.error('Error loading Chart.js:', error);
    });
  } catch (error) {
    console.error('Error parsing chart data:', error);
  }
}
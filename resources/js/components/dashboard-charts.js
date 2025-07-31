import Chart from 'chart.js/auto';

export default function initDashboardCharts() {
  const chartElements = document.querySelectorAll('[data-chart-type]');
  if (!chartElements.length) return;
  
  const charts = {};
  
  function getThemeColors() {
    const computedStyle = getComputedStyle(document.documentElement);
    return {
      textColor: computedStyle.getPropertyValue('--color-text-primary').trim(),
      gridColor: computedStyle.getPropertyValue('--color-card-border').trim(),
      gameBlue: computedStyle.getPropertyValue('--color-game-blue').trim(),
      gameGreen: computedStyle.getPropertyValue('--color-game-green').trim(),
      gameRed: computedStyle.getPropertyValue('--color-game-red').trim(),
      success: computedStyle.getPropertyValue('--color-success').trim(),
      textSecondary: computedStyle.getPropertyValue('--color-text-secondary').trim()
    };
  }
  
  function createChart(canvas) {
    const type = canvas.dataset.chartType;
    const data = JSON.parse(canvas.dataset.chartData || '{}');
    const customOptions = JSON.parse(canvas.dataset.chartOptions || '{}');
    const ctx = canvas.getContext('2d');
    const colors = getThemeColors();
    
    // Common options for all charts
    const commonOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          titleColor: '#fff',
          bodyColor: '#fff',
          borderColor: '#333',
          borderWidth: 1,
          padding: 10,
          cornerRadius: 4,
          displayColors: true
        }
      }
    };

    // Check if it's a small faction chart
    const isSmallChart = canvas.id && canvas.id.startsWith('faction-chart-');

    // Type-specific options
    const typeOptions = {
      bar: {
        scales: {
          x: {
            grid: {
              color: colors.gridColor,
              display: false
            },
            ticks: {
              color: colors.textColor,
              font: {
                size: isSmallChart ? 10 : 12
              }
            }
          },
          y: {
            grid: {
              color: colors.gridColor,
              display: !isSmallChart
            },
            ticks: {
              color: colors.textColor,
              beginAtZero: true,
              font: {
                size: isSmallChart ? 10 : 12
              },
              display: !isSmallChart
            }
          }
        },
        ...(isSmallChart && {
          layout: {
            padding: {
              top: 10
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        })
      },
      line: {
        scales: {
          x: {
            grid: {
              color: colors.gridColor,
              display: false
            },
            ticks: {
              color: colors.textColor
            }
          },
          y: {
            grid: {
              color: colors.gridColor
            },
            ticks: {
              color: colors.textColor,
              beginAtZero: true
            }
          }
        }
      }
    };

    // Merge options
    const options = {
      ...commonOptions,
      ...(typeOptions[type] || {}),
      ...customOptions
    };

    // Create the chart
    const chart = new Chart(ctx, {
      type: type,
      data: data,
      options: options
    });

    // Store reference
    charts[canvas.id] = chart;
  }
  
  // Initialize all charts
  chartElements.forEach(canvas => {
    createChart(canvas);
  });
  
  // Update charts on theme change
  const themeObserver = new MutationObserver(() => {
    const colors = getThemeColors();
    
    Object.values(charts).forEach(chart => {
      // Update text colors
      if (chart.options.plugins.legend.labels) {
        chart.options.plugins.legend.labels.color = colors.textColor;
      }
      
      // Update scales colors
      if (chart.options.scales) {
        Object.values(chart.options.scales).forEach(scale => {
          if (scale.ticks) scale.ticks.color = colors.textColor;
          if (scale.grid) scale.grid.color = colors.gridColor;
        });
      }
      
      chart.update();
    });
  });
  
  themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-theme']
  });
}
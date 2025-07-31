import Chart from 'chart.js/auto';

export default function initDashboardCharts() {
  const chartElements = document.querySelectorAll('[data-chart-type]');
  if (!chartElements.length) return;
  
  const charts = {};
  
  function getThemeColors() {
    const computedStyle = getComputedStyle(document.documentElement);
    return {
      textColor: computedStyle.getPropertyValue('--color-text-primary').trim(),
      gridColor: computedStyle.getPropertyValue('--color-text-muted').trim(),
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
    
    // Add border configuration to datasets for bar charts
    if (type === 'bar' && data.datasets) {
      data.datasets = data.datasets.map(dataset => ({
        ...dataset,
        borderColor: colors.textSecondary,
        borderWidth: 1
      }));
    }
    
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
          borderColor: colors.gridColor,
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
              display: false,
              borderColor: colors.gridColor
            },
            ticks: {
              color: colors.textColor,
              font: {
                size: isSmallChart ? 10 : 12
              }
            },
            border: {
              color: colors.gridColor,
              display: true
            }
          },
          y: {
            grid: {
              color: colors.gridColor,
              display: !isSmallChart,
              borderColor: colors.gridColor,
              drawTicks: true,
              tickColor: colors.gridColor
            },
            ticks: {
              color: colors.textColor,
              beginAtZero: true,
              font: {
                size: isSmallChart ? 10 : 12
              },
              display: !isSmallChart
            },
            border: {
              color: colors.gridColor,
              display: true
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
              display: false,
              borderColor: colors.gridColor
            },
            ticks: {
              color: colors.textColor
            },
            border: {
              color: colors.gridColor,
              display: true
            }
          },
          y: {
            grid: {
              color: colors.gridColor,
              borderColor: colors.gridColor,
              drawTicks: true,
              tickColor: colors.gridColor
            },
            ticks: {
              color: colors.textColor,
              beginAtZero: true
            },
            border: {
              color: colors.gridColor,
              display: true
            }
          }
        }
      },
      doughnut: {
        elements: {
          arc: {
            borderColor: colors.textColor,
            borderWidth: 1
          }
        }
      },
      pie: {
        elements: {
          arc: {
            borderColor: colors.textColor,
            borderWidth: 1
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
      
      // Update tooltip border
      if (chart.options.plugins.tooltip) {
        chart.options.plugins.tooltip.borderColor = colors.textColor;
      }
      
      // Update scales colors
      if (chart.options.scales) {
        Object.values(chart.options.scales).forEach(scale => {
          if (scale.ticks) scale.ticks.color = colors.textColor;
          if (scale.grid) {
            scale.grid.color = colors.gridColor;
            scale.grid.borderColor = colors.gridColor;
            scale.grid.tickColor = colors.gridColor;
          }
          if (scale.border) scale.border.color = colors.gridColor;
        });
      }
      
      // Update dataset borders for bar charts
      if (chart.config.type === 'bar' && chart.data.datasets) {
        chart.data.datasets.forEach(dataset => {
          dataset.borderColor = colors.textColor;
          dataset.borderWidth = 1;
        });
      }
      
      // Update arc borders for doughnut/pie charts
      if ((chart.config.type === 'doughnut' || chart.config.type === 'pie') && chart.options.elements?.arc) {
        chart.options.elements.arc.borderColor = colors.textColor;
        chart.options.elements.arc.borderWidth = 1;
      }
      
      chart.update();
    });
  });
  
  themeObserver.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ['data-theme']
  });
}
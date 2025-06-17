// passageiros-por-classe.js
const ctxBar = document.getElementById('barChart').getContext('2d');

let chart = new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: ['Primeira Classe', 'Classe Econômica', 'Vagão Leito', 'Vagão Restaurante'],
        datasets: [{
            label: 'Nº de Passageiros',
            data: [80, 200, 40, 30],
            backgroundColor: [
                '#990000',
                '#cc0000',
                '#e06666',
                '#f4cccc'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#f0f0f0'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y;
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: { color: '#f0f0f0' },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#f0f0f0' },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            }
        }
    }
});

function atualizarBarChart() {
    const novaData = [
        parseInt(document.getElementById('classe1').value) || 0,
        parseInt(document.getElementById('classe2').value) || 0,
        parseInt(document.getElementById('classe3').value) || 0,
        parseInt(document.getElementById('classe4').value) || 0
    ];
    chart.data.datasets[0].data = novaData;
    chart.update();
}

document.addEventListener('DOMContentLoaded', () => {
    atualizarBarChart();
});
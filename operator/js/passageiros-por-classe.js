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
                    color: '#1f1f1f'
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
                ticks: { color: '#555555' },
                grid: {
                    color: 'rgba(0, 0, 0, 0.08)'
                }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#555555' },
                grid: {
                    color: 'rgba(0, 0, 0, 0.08)'
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

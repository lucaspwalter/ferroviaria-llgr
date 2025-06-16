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
                '#990000', // Vermelho mais escuro
                '#cc0000', // Vermelho médio
                '#e06666', // Vermelho claro
                '#f4cccc'  // Vermelho muito claro
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Importante para o CSS controlar a altura
        plugins: {
            legend: {
                labels: {
                    color: '#f0f0f0' // Cor do texto da legenda para o tema escuro
                }
            },
            tooltip: { // Melhorias na tooltip
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
                ticks: { color: '#f0f0f0' }, // Cor dos rótulos do eixo X
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)' // Cor da grade do eixo X
                }
            },
            y: {
                beginAtZero: true,
                ticks: { color: '#f0f0f0' }, // Cor dos rótulos do eixo Y
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)' // Cor da grade do eixo Y
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

// Garante que o gráfico seja desenhado na carga inicial
document.addEventListener('DOMContentLoaded', () => {
    atualizarBarChart(); // Desenha o gráfico com os valores iniciais
});
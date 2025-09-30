let ctx = document.getElementById('graficoPizza').getContext('2d');

let grafico = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Colisão', 'Descarrilamento', 'Falha técnica', 'Outros'],
    datasets: [{
      data: [40, 30, 20, 10],
      backgroundColor: ['#b30000', '#cc3333', '#e06666', '#f4aaaa'],
      borderColor: '#000',
      borderWidth: 1,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false
      }
    }
  }
});

function atualizarGrafico() {
  const colisao = parseInt(document.getElementById('colisao').value) || 0;
  const descarrilamento = parseInt(document.getElementById('descarrilamento').value) || 0;
  const falha = parseInt(document.getElementById('falha').value) || 0;
  const outros = parseInt(document.getElementById('outros').value) || 0;

  const total = colisao + descarrilamento + falha + outros;

  if (total !== 100) {
    alert("A soma dos percentuais deve ser exatamente 100%!");
    return;
  }

  grafico.data.datasets[0].data = [colisao, descarrilamento, falha, outros];
  grafico.update();
}
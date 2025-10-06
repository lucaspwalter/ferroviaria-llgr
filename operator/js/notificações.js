function criarNotificacao(app, tempo, titulo, mensagem, temOpcaoEditar = true) {
    const notificacaoDiv = document.createElement('div');
    notificacaoDiv.classList.add('notificacao');
    let editarOperadorHtml = '';
    if (temOpcaoEditar) {
        editarOperadorHtml = `
            <div class="notificacao-acoes-operador">
                <span class="btn-operador-editar">Editar (Operador)</span>
            </div>
        `;
    }
    notificacaoDiv.innerHTML = `
        <div class="notificacao-icone">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http:
                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
            </svg>
        </div>
        <div class="notificacao-conteudo">
            <div class="notificacao-header">
                <span class="notificacao-app">${app}</span>
                <span class="notificacao-tempo">${tempo}</span>
            </div>
            <div class="notificacao-titulo">${titulo}</div>
            <div class="notificacao-msg">${mensagem}</div>
        </div>
        ${editarOperadorHtml}
    `;
    if (temOpcaoEditar) {
        const btnEditar = notificacaoDiv.querySelector('.btn-operador-editar');
        if (btnEditar) {
            btnEditar.addEventListener('click', () => {
                console.log(`Editar notificação: ${titulo}`);
            });
        }
    }
    return notificacaoDiv;
}
document.addEventListener('DOMContentLoaded', () => {
    const notificacoesLista = document.getElementById('notificacoes-lista');
    notificacoesLista.innerHTML = '';
    notificacoesLista.appendChild(criarNotificacao(
        'Trem 9',
        'há 5 min',
        'Atenção: Manutenção na Via',
        'Seu trem está atrasado devido a manutenção emergencial na via. Previsão de normalização em 20 minutos.'
    ));
    notificacoesLista.appendChild(criarNotificacao(
        'Trem 6',
        'há 20 min',
        'Aviso: Lotação Elevada',
        'O próximo trem sentido Luz está com alta lotação. Considere aguardar o próximo trem para maior conforto.'
    ));
    notificacoesLista.appendChild(criarNotificacao(
        'Trem 5',
        'há 1h',
        'Informação: Estação Fechada',
        'A estação Palmeiras-Barra Funda está temporariamente fechada para embarque e desembarque.'
    ));
    notificacoesLista.appendChild(criarNotificacao(
        'Trem 3',
        'há 2h',
        'Alerta: Objeto na Via',
        'Objeto identificado na via da Linha 11-Coral. Equipe de manutenção acionada para remoção imediata.'
    ));
    notificacoesLista.appendChild(criarNotificacao(
        'Trem 1',
        'há 3h',
        'Status: Operação Normal',
        'Todas as linhas operando normalmente, sem ocorrências no momento. Boa viagem!',
        false
    ));
});
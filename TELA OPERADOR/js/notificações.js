// seu_projeto/TELA USUARIO/js/notificacoes.js

// Função para criar uma notificação com a opção de "editar (operador)"
// O parâmetro 'temOpcaoEditar' controla se o botão de edição será mostrado.
function criarNotificacao(app, tempo, titulo, mensagem, temOpcaoEditar = true) {
    const notificacaoDiv = document.createElement('div');
    notificacaoDiv.classList.add('notificacao');

    let editarOperadorHtml = '';
    if (temOpcaoEditar) {
        // HTML para o botão "Editar (Operador)"
        editarOperadorHtml = `
            <div class="notificacao-acoes-operador">
                <span class="btn-operador-editar">Editar (Operador)</span>
            </div>
        `;
    }

    notificacaoDiv.innerHTML = `
        <div class="notificacao-icone">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            ${editarOperadorHtml} </div>
    `;

    // Adiciona um evento de clique para a opção de editar (apenas estético, sem alert)
    if (temOpcaoEditar) {
        const btnEditar = notificacaoDiv.querySelector('.btn-operador-editar');
        if (btnEditar) {
            btnEditar.addEventListener('click', () => {
                // Não faz nada ao clicar, mantendo apenas a estética
                // Você pode adicionar efeitos visuais aqui se quiser, por exemplo:
                // btnEditar.style.backgroundColor = '#666';
                // setTimeout(() => btnEditar.style.backgroundColor = '#4a4a4a', 300);
            });
        }
    }

    return notificacaoDiv;
}

// --- LÓGICA PARA CARREGAR NOTIFICAÇÕES QUANDO A PÁGINA ESTIVER PRONTA ---
document.addEventListener('DOMContentLoaded', () => {
    const notificacoesLista = document.getElementById('notificacoes-lista');

    // Opcional: Limpa notificações existentes, útil para evitar duplicação em recargas ou desenvolvimento
    notificacoesLista.innerHTML = '';

    // Adiciona algumas notificações de exemplo para demonstração
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

    // Exemplo de uma notificação SEM a opção de editar (se desejar variar as notificações)
    notificacoesLista.appendChild(criarNotificacao(
        'Trem 1',
        'há 3h',
        'Status: Operação Normal',
        'Todas as linhas operando normalmente, sem ocorrências no momento. Boa viagem!',
        false // Define este parâmetro como 'false' para NÃO mostrar a opção de editar para esta notificação
    ));
});
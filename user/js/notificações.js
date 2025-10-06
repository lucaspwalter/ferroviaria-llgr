const notificacoes = [
    {
        app: "Trem 9",
        tempo: "há 5 min",
        titulo: "Atenção: manutenção",
        msg: "Seu trem está atrasado devido a manutenção na via. Previsão de normalização em 20 minutos.",
        icone: `<svg viewBox="0 0 24 24" fill="none"><path d="M12 2C7.03 2 3 6.03 3 11c0 4.97 4.03 9 9 9s9-4.03 9-9c0-4.97-4.03-9-9-9zm0 16c-3.87 0-7-3.13-7-7 0-3.87 3.13-7 7-7s7 3.13 7 7c0 3.87-3.13 7-7 7zm-1-4h2v2h-2zm0-8h2v6h-2z" fill="currentColor"/></svg>`
    },
    {
        app: "Trem 6",
        tempo: "há 20 min",
        titulo: "Aviso: Lotação",
        msg: "O próximo trem sentido Luz está com alta lotação. Considere aguardar o próximo.",
        icone: `<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><path d="M12 8v4M12 16h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>`
    },
    {
        app: "Trem 5",
        tempo: "há 1h",
        titulo: "Informação: Nova Parada",
        msg: "A estação Palmeiras-Barra Funda está temporariamente fechada para embarque.",
        icone:  `<svg viewBox="0 0 24 24" fill="none"><path d="M12 2C7.03 2 3 6.03 3 11c0 4.97 4.03 9 9 9s9-4.03 9-9c0-4.97-4.03-9-9-9zm0 16c-3.87 0-7-3.13-7-7 0-3.87 3.13-7 7-7s7 3.13 7 7c0 3.87-3.13 7-7 7zm-1-4h2v2h-2zm0-8h2v6h-2z" fill="currentColor"/></svg>`
    },
    {
        app: "Trem 3",
        tempo: "há 2h",
        titulo: "Alerta: Objeto na via",
        msg: "Objeto identificado na via da Linha 11-Coral. Equipe de manutenção acionada.",
        icone:  `<svg viewBox="0 0 24 24" fill="none"><path d="M12 2C7.03 2 3 6.03 3 11c0 4.97 4.03 9 9 9s9-4.03 9-9c0-4.97-4.03-9-9-9zm0 16c-3.87 0-7-3.13-7-7 0-3.87 3.13-7 7-7s7 3.13 7 7c0 3.87-3.13 7-7 7zm-1-4h2v2h-2zm0-8h2v6h-2z" fill="currentColor"/></svg>`
    }
];
const lista = document.getElementById('notificacoes-lista');
notificacoes.forEach(n => {
    const div = document.createElement('div');
    div.className = 'notificacao';
    div.innerHTML = `
        <div class="notificacao-icone">${n.icone}</div>
        <div class="notificacao-conteudo">
            <div class="notificacao-header">
                <span class="notificacao-app">${n.app}</span>
                <span class="notificacao-tempo">${n.tempo}</span>
            </div>
            <div class="notificacao-titulo">${n.titulo}</div>
            <div class="notificacao-msg">${n.msg}</div>
        </div>
    `;
    lista.appendChild(div);
});
const reclamacao = document.getElementById('reclamacao-link');
if (reclamacao) {
    reclamacao.addEventListener('click', () => {
        window.location.href = 'chat.html';
    });
}
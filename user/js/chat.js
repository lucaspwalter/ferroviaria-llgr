const chatForm = document.getElementById('chat-form');
const chatInput = document.getElementById('chat-input');
const chatMessages = document.getElementById('chat-messages');

function addMessage(text, sender = 'user') {
    const msgDiv = document.createElement('div');
    msgDiv.className = 'msg ' + sender;
    msgDiv.textContent = text;
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function botReply() {
    setTimeout(() => {
        addMessage('Vamos checar o que podemos fazer.', 'bot');
    }, 700);
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const text = chatInput.value.trim();
    if (text) {
        addMessage(text, 'user');
        chatInput.value = '';
        botReply();
    }
});
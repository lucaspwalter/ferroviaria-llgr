function showToast(message, type = 'info', duration = 4000) {
    const allowedTypes = ['success', 'error', 'warning', 'info'];
    const toastType = allowedTypes.includes(type) ? type : 'info';

    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${toastType}`;

    const messageElement = document.createElement('p');
    messageElement.className = 'toast-message';
    messageElement.textContent = message;

    const closeButton = document.createElement('button');
    closeButton.className = 'toast-close';
    closeButton.type = 'button';
    closeButton.setAttribute('aria-label', 'Fechar notificação');
    closeButton.textContent = 'X';

    const closeToast = () => {
        toast.classList.add('toast-hide');
        toast.addEventListener('animationend', () => toast.remove(), { once: true });
    };

    closeButton.addEventListener('click', closeToast);
    toast.appendChild(messageElement);
    toast.appendChild(closeButton);
    container.appendChild(toast);

    setTimeout(closeToast, duration);
}

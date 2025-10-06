function showAlert(message, type = 'success') {
    const alertDiv = document.getElementById('alert');
    if (!alertDiv) return;
    alertDiv.className = `alert alert-${type} show`;
    alertDiv.textContent = message;
    setTimeout(() => {
        alertDiv.classList.remove('show');
    }, 5000);
}
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        const errorMsg = field.parentElement.querySelector('.error-message');
        if (!field.value.trim()) {
            field.classList.add('error');
            if (errorMsg) {
                errorMsg.classList.add('show');
                errorMsg.textContent = 'Este campo é obrigatório';
            }
            isValid = false;
        } else {
            field.classList.remove('error');
            if (errorMsg) {
                errorMsg.classList.remove('show');
            }
        }
    });
    return isValid;
}
function setupFieldValidation() {
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error');
            const errorMsg = this.parentElement.querySelector('.error-message');
            if (errorMsg) {
                errorMsg.classList.remove('show');
            }
        });
    });
}
async function submitForm(formId, backendUrl, action = 'cadastrar') {
    if (!validateForm(formId)) {
        showAlert('Por favor, preencha todos os campos obrigatórios', 'error');
        return false;
    }
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    formData.append('acao', action);
    const submitBtn = form.querySelector('.btn-primary');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Salvando...';
    try {
        const response = await fetch(backendUrl, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.sucesso) {
            showAlert(result.mensagem, 'success');
            form.reset();
            if (typeof loadData === 'function') {
                setTimeout(() => loadData(), 1000);
            }
        } else {
            showAlert(result.mensagem || 'Erro ao salvar', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showAlert('Erro ao comunicar com o servidor', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
    return false;
}
async function loadData(backendUrl, tableBodyId) {
    const tbody = document.getElementById(tableBodyId);
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="100%" class="loading">Carregando dados</td></tr>';
    try {
        const response = await fetch(`${backendUrl}?acao=listar`);
        const result = await response.json();
        if (result.sucesso && result.dados.length > 0) {
            tbody.innerHTML = '';
            result.dados.forEach(item => {
                const row = createTableRow(item);
                tbody.appendChild(row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="100%" class="empty-state"><p>Nenhum registro encontrado</p></td></tr>';
        }
    } catch (error) {
        console.error('Erro:', error);
        tbody.innerHTML = '<tr><td colspan="100%" class="empty-state"><p>Erro ao carregar dados</p></td></tr>';
    }
}
function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
}
function formatDateTime(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleString('pt-BR');
}
function formatCurrency(value) {
    if (!value) return 'R$ 0,00';
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}
function getStatusBadge(status) {
    const statusMap = {
        'ativo': 'success',
        'operacional': 'success',
        'ativa': 'success',
        'agendado': 'info',
        'agendada': 'info',
        'em_andamento': 'warning',
        'concluído': 'success',
        'concluída': 'success',
        'inativo': 'secondary',
        'inativa': 'secondary',
        'cancelado': 'danger',
        'cancelada': 'danger',
        'manutenção': 'warning',
        'crítico': 'danger',
        'urgente': 'warning',
        'aviso': 'info',
        'informativo': 'info',
        'resolvido': 'success'
    };
    const badgeClass = statusMap[status] || 'secondary';
    return `<span class="badge badge-${badgeClass}">${status}</span>`;
}
async function deleteRecord(id, backendUrl, confirmMessage = 'Tem certeza que deseja excluir este registro?') {
    if (!confirm(confirmMessage)) return;
    const formData = new FormData();
    formData.append('acao', 'deletar');
    formData.append('id', id);
    try {
        const response = await fetch(backendUrl, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.sucesso) {
            showAlert(result.mensagem, 'success');
            if (typeof loadData === 'function') {
                setTimeout(() => loadData(), 1000);
            }
        } else {
            showAlert(result.mensagem || 'Erro ao excluir', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showAlert('Erro ao comunicar com o servidor', 'error');
    }
}
async function loadSelect(backendUrl, selectId, valueField = 'id', textField = 'nome') {
    const select = document.getElementById(selectId);
    if (!select) return;
    try {
        const response = await fetch(`${backendUrl}?acao=listar`);
        const result = await response.json();
        if (result.sucesso) {
            select.innerHTML = '<option value="">Selecione...</option>';
            result.dados.forEach(item => {
                const option = document.createElement('option');
                option.value = item[valueField];
                option.textContent = item[textField];
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Erro ao carregar select:', error);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    setupFieldValidation();
});
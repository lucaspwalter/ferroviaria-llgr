<?php
session_start();
$erro = $_SESSION['erro'] ?? '';
$sucesso = $_SESSION['sucesso'] ?? '';
unset($_SESSION['erro'], $_SESSION['sucesso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - LLGR</title>
    <link rel="stylesheet" href="../css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/cadastro.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https:
</head>
<body>
<header></header>
<main class="login-container">
    <form id="formCadastro" action="../../user-backend/cadastro_backend.php" method="POST">
        <h1>Crie sua <span class="conta-vermelho">conta</span></h1>
        <?php if ($erro): ?>
            <div class="mensagem erro">
                <i class="bi bi-exclamation-circle"></i>
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="mensagem sucesso">
                <i class="bi bi-check-circle"></i>
                <?= htmlspecialchars($sucesso) ?>
            </div>
        <?php endif; ?>
        <!-- DADOS PESSOAIS -->
        <div class="form-section">
            <h3 class="section-title"><i class="bi bi-person-circle"></i> Dados Pessoais</h3>
            <div class="fields-grid">
                <div class="input-box">
                    <input 
                        name="nome" 
                        id="nome"
                        placeholder="Nome Completo" 
                        type="text" 
                        required 
                        minlength="3"
                        maxlength="100"
                    >
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="input-box">
                    <input 
                        name="email" 
                        id="email"
                        placeholder="E-mail" 
                        type="email" 
                        required 
                    >
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="input-box">
                    <input 
                        name="cpf" 
                        id="cpf"
                        placeholder="CPF (opcional)" 
                        type="text" 
                        maxlength="14"
                    >
                    <i class="bi bi-card-text"></i>
                    <small class="field-hint">Formato: 000.000.000-00</small>
                </div>
                <div class="input-box">
                    <input 
                        name="telefone" 
                        id="telefone"
                        placeholder="Telefone (opcional)" 
                        type="tel" 
                        maxlength="15"
                    >
                    <i class="bi bi-telephone"></i>
                    <small class="field-hint">Formato: (00) 00000-0000</small>
                </div>
                <div class="input-box">
                    <input 
                        name="data_nascimento" 
                        id="data_nascimento"
                        placeholder="Data de Nascimento" 
                        type="date"
                    >
                    <i class="bi bi-calendar"></i>
                </div>
            </div>
        </div>
        <!-- ENDEREÇO -->
        <div class="form-section">
            <h3 class="section-title"><i class="bi bi-geo-alt"></i> Endereço</h3>
            <div class="fields-grid">
                <div class="input-box full-width">
                    <input 
                        name="endereco" 
                        id="endereco"
                        placeholder="Endereço Completo (opcional)" 
                        type="text" 
                        maxlength="200"
                    >
                    <i class="bi bi-house"></i>
                </div>
                <div class="input-row">
                    <div class="input-box">
                        <input 
                            name="cidade" 
                            id="cidade"
                            placeholder="Cidade" 
                            type="text" 
                            maxlength="100"
                        >
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="input-box">
                        <select name="estado" id="estado">
                            <option value="">Estado</option>
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="ES">ES</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select>
                        <i class="bi bi-map"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- SENHA -->
        <div class="form-section">
            <h3 class="section-title"><i class="bi bi-shield-lock"></i> Segurança</h3>
            <div class="fields-grid">
                <div class="input-box">
                    <input 
                        name="senha" 
                        id="senha"
                        placeholder="Senha" 
                        type="password" 
                        required 
                        minlength="8"
                    >
                    <i class="bi bi-lock-fill"></i>
                    <small class="field-hint">Mínimo 8 caracteres, 1 maiúscula, 1 número</small>
                </div>
                <div class="input-box">
                    <input 
                        name="confirmar_senha" 
                        id="confirmar_senha"
                        placeholder="Confirmar Senha" 
                        type="password" 
                        required 
                        minlength="8"
                    >
                    <i class="bi bi-lock-fill"></i>
                </div>
                <div class="password-strength full-width" id="passwordStrength" style="display: none;">
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="strength-text" id="strengthText"></span>
                </div>
            </div>
        </div>
        <button type="submit" class="login">
            <i class="bi bi-person-plus"></i> Cadastrar
        </button>
        <div class="register-link">
            <p>Já tem conta? <a href="login.php">Entrar</a></p>
        </div>
    </form>
</main>
<script src="../js/mobile-navbar.js"></script>
<script>
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    }
});
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = value;
    }
});
const senhaInput = document.getElementById('senha');
const strengthDiv = document.getElementById('passwordStrength');
const strengthFill = document.getElementById('strengthFill');
const strengthText = document.getElementById('strengthText');
senhaInput.addEventListener('input', function() {
    const senha = this.value;
    if (senha.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }
    strengthDiv.style.display = 'block';
    let strength = 0;
    if (senha.length >= 8) strength++;
    if (senha.length >= 12) strength++;
    if (/[a-z]/.test(senha) && /[A-Z]/.test(senha)) strength++;
    if (/[0-9]/.test(senha)) strength++;
    if (/[^A-Za-z0-9]/.test(senha)) strength++;
    const colors = ['
    const texts = ['Muito Fraca', 'Fraca', 'Média', 'Forte', 'Muito Forte'];
    const widths = ['20%', '40%', '60%', '80%', '100%'];
    strengthFill.style.width = widths[strength - 1] || '0%';
    strengthFill.style.backgroundColor = colors[strength - 1] || '
    strengthText.textContent = texts[strength - 1] || 'Muito Fraca';
    strengthText.style.color = colors[strength - 1] || '
});
document.getElementById('formCadastro').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar_senha').value;
    const cpf = document.getElementById('cpf').value;
    if (senha !== confirmarSenha) {
        e.preventDefault();
        alert('As senhas não coincidem!');
        return false;
    }
    if (senha.length < 8) {
        e.preventDefault();
        alert('A senha deve ter no mínimo 8 caracteres!');
        return false;
    }
    if (!/[A-Z]/.test(senha)) {
        e.preventDefault();
        alert('A senha deve conter pelo menos uma letra maiúscula!');
        return false;
    }
    if (!/[0-9]/.test(senha)) {
        e.preventDefault();
        alert('A senha deve conter pelo menos um número!');
        return false;
    }
    if (cpf && !validarCPF(cpf)) {
        e.preventDefault();
        alert('CPF inválido!');
        return false;
    }
    return true;
});
function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11) return false;
    if (/^(\d)\1{10}$/.test(cpf)) return false;
    let soma = 0;
    let resto;
    for (let i = 1; i <= 9; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for (let i = 1; i <= 10; i++) {
        soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;
    return true;
}
</script>
</body>
</html>
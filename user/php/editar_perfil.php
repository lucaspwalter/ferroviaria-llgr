<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - LLGR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/editarPerfil.css">
    <link rel="stylesheet" href="../css/editar-perfil-extra.css">
</head>
<body>
    <header>
        <nav>
            <a class="logo" href="../html/telainicialU.html">LLGR</a>
            <div class="mobile-menu">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
            <ul class="nav-list">
                <li><a href="../html/telainicialU.html">Início</a></li>
                <li><a href="rotas_usuario.php">Rotas</a></li>
                <li><a href="notificacoes_usuario.php">Notificações</a></li>
                <li><a href="../html/chatU.php">Reclame Aqui</a></li>
                <li><a href="perfil.php">Perfil</a></li>
                <li><a href="logout_usuario.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <div class="edit-profile-container">
        <div class="edit-profile-box">
            <h2>Editar Perfil <span class="llgr-red">LLGR</span></h2>
            <div id="alert" class="alert-box"></div>
            <form id="perfilForm">
                <div class="input-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="input-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                </div>
                <div class="input-group">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento">
                </div>
                <div class="input-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Rua, número, complemento">
                </div>
                <div class="input-row">
                    <div class="input-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" id="cidade" name="cidade">
                    </div>
                    <div class="input-group">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado">
                            <option value="">Selecione...</option>
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
                    </div>
                </div>
                <button type="submit" class="btn-save">Salvar Alterações</button>
                <button type="button" class="btn-cancel" onclick="window.location.href='perfil.php'">Cancelar</button>
            </form>
            <hr class="divisor-linha">
            <h3 class="titulo-senha">Alterar Senha</h3>
            <form id="senhaForm">
                <div class="input-group">
                    <label for="senha_atual">Senha Atual *</label>
                    <input type="password" id="senha_atual" name="senha_atual" required>
                </div>
                <div class="input-group">
                    <label for="senha_nova">Nova Senha *</label>
                    <input type="password" id="senha_nova" name="senha_nova" required minlength="8">
                    <small class="small-hint">Mínimo 8 caracteres</small>
                </div>
                <div class="input-group">
                    <label for="senha_confirma">Confirmar Nova Senha *</label>
                    <input type="password" id="senha_confirma" name="senha_confirma" required minlength="8">
                </div>
                <button type="submit" class="btn-save">Alterar Senha</button>
            </form>
        </div>
    </div>
    <script src="../js/mobile-navbar.js"></script>
    <script>
        async function carregarDados() {
            try {
                const response = await fetch('../../user-backend/perfil_backend.php?acao=buscar_perfil');
                const result = await response.json();
                if (result.sucesso) {
                    const dados = result.dados;
                    document.getElementById('nome').value = dados.nome || '';
                    document.getElementById('telefone').value = dados.telefone || '';
                    document.getElementById('data_nascimento').value = dados.data_nascimento || '';
                    document.getElementById('endereco').value = dados.endereco || '';
                    document.getElementById('cidade').value = dados.cidade || '';
                    document.getElementById('estado').value = dados.estado || '';
                }
            } catch (error) {
                console.error('Erro:', error);
            }
        }
        document.getElementById('perfilForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('acao', 'atualizar_perfil');
            const submitBtn = this.querySelector('.btn-save');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Salvando...';
            try {
                const response = await fetch('../../user-backend/perfil_backend.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                showAlert(result.mensagem, result.sucesso ? 'success' : 'error');
                if (result.sucesso) {
                    setTimeout(function() {
                        window.location.href = 'perfil.php';
                    }, 1500);
                }
            } catch (error) {
                showAlert('Erro ao comunicar com servidor', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Salvar Alterações';
            }
        });
        document.getElementById('senhaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const senhaNova = document.getElementById('senha_nova').value;
            const senhaConfirma = document.getElementById('senha_confirma').value;
            if (senhaNova !== senhaConfirma) {
                showAlert('As senhas não coincidem', 'error');
                return;
            }
            const formData = new FormData(this);
            formData.append('acao', 'atualizar_senha');
            const submitBtn = this.querySelector('.btn-save');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Alterando...';
            try {
                const response = await fetch('../../user-backend/perfil_backend.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                showAlert(result.mensagem, result.sucesso ? 'success' : 'error');
                if (result.sucesso) {
                    this.reset();
                }
            } catch (error) {
                showAlert('Erro ao comunicar com servidor', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Alterar Senha';
            }
        });
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.style.display = 'block';
            alert.style.backgroundColor = type === 'success' ? 'rgba(40, 167, 69, 0.2)' : 'rgba(220, 53, 69, 0.2)';
            alert.style.border = '1px solid ' + (type === 'success' ? '#28a745' : '#dc3545');
            alert.style.color = type === 'success' ? '#28a745' : '#dc3545';
            window.scrollTo(0, 0);
            setTimeout(function() {
                alert.style.display = 'none';
            }, 5000);
        }
        window.addEventListener('DOMContentLoaded', carregarDados);
    </script>
</body>
</html>

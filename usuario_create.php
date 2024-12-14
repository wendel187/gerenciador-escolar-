<?php
session_start();
require 'conexaoBD.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $senha = $_POST['senha'];
    $confirmaSenha = $_POST['confirmar_senha']; 
    $tipo = $_POST['tipo'];
    $idAssociado = $_POST['id_associado'] ?? null; // ID do aluno ou professor

    if (!empty($username) && !empty($senha) && !empty($tipo)) {
        if ($senha === $confirmaSenha) {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            // Cadastro de um usuário na tabela apropriada
            if ($tipo === 'admin') {
                // Cadastro de administrador
                $sqlUsuario = "INSERT INTO usuarios (username, senha) VALUES (?, ?)";
                $stmtUsuario = mysqli_prepare($conexao, $sqlUsuario);
                mysqli_stmt_bind_param($stmtUsuario, "ss", $username, $senhaHash);
            } elseif ($tipo === 'aluno') {
                // Cadastro de aluno, associado ao idAluno
                $sqlUsuario = "INSERT INTO usuarios_alunos (username, senha, aluno_id) VALUES (?, ?, ?)";
                $stmtUsuario = mysqli_prepare($conexao, $sqlUsuario);
                mysqli_stmt_bind_param($stmtUsuario, "ssi", $username, $senhaHash, $idAssociado);
            } elseif ($tipo === 'professor') {
                // Cadastro de professor, associado ao idProfessor
                $sqlUsuario = "INSERT INTO usuarios_professores (username, senha, professor_id) VALUES (?, ?, ?)";
                $stmtUsuario = mysqli_prepare($conexao, $sqlUsuario);
                mysqli_stmt_bind_param($stmtUsuario, "ssi", $username, $senhaHash, $idAssociado);
            }

            if (mysqli_stmt_execute($stmtUsuario)) {
                $_SESSION['mensagem'] = "Usuário cadastrado com sucesso!";
                header("Location: usuarios.php");
                exit();
            } else {
                $_SESSION['mensagem'] = "Erro ao cadastrar o usuário. Tente novamente.";
            }
        } else {
            $_SESSION['mensagem'] = "As senhas não coincidem. Tente novamente.";
        }
    } else {
        $_SESSION['mensagem'] = "Todos os campos são obrigatórios.";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .alert-info {
            margin-top: 10px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
    </style>
    <script>
        function mostrarCampoAssociado() {
            const tipo = document.getElementById("tipo").value;
            const campoAssociado = document.getElementById("campoAssociado");

            // Oculta o campo de ID associado inicialmente
            campoAssociado.style.display = "none";

            // Mostra o campo de ID associado se for aluno ou professor
            if (tipo === "aluno" || tipo === "professor") {
                campoAssociado.style.display = "block";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>Cadastrar Usuário</h4>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-info">
                        <?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nome de Usuário</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Usuário</label>
                        <select class="form-select" id="tipo" name="tipo" onchange="mostrarCampoAssociado()" required>
                            <option value="">Selecione</option>
                            <option value="admin">Administrador</option>
                            <option value="aluno">Aluno</option>
                            <option value="professor">Professor</option>
                        </select>
                    </div>
                    <div class="mb-3" id="campoAssociado" style="display:none;">
                        <label for="id_associado" class="form-label">ID Associado</label>
                        <input type="number" class="form-control" id="id_associado" name="id_associado">
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

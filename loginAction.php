<?php
session_start();
require_once('conexaoBD.php'); // Inclui a conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['txtnome'];
    $senha = $_POST['txtsenha'];
    $tipo = $_POST['tipo_usuario']; // Recebe o tipo de usuário (admin, professor, aluno)

    // Verifica o tipo de usuário e define a tabela apropriada
    if ($tipo === 'admin') {
        $tabela = 'usuarios';
    } elseif ($tipo === 'professor') {
        $tabela = 'usuarios_professores';
    } elseif ($tipo === 'aluno') {
        $tabela = 'usuarios_alunos';
    } else {
        header("Location: index.php?error=Tipo de usuário inválido");
        exit();
    }

    // Consulta para verificar o usuário na tabela apropriada
    $sql = "SELECT * FROM $tabela WHERE username = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 's', $nome);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Verifica se o usuário existe
    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            // Armazena informações básicas do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['tipo'] = $tipo;

            // Redireciona para o painel apropriado com base no tipo de usuário
            if ($tipo === 'admin') {
                header("Location: principal.php"); // Painel de administrador
                exit();
            } elseif ($tipo === 'professor') {
                $_SESSION['id_professor'] = $usuario['id']; // ID do professor
                header("Location: painel_professor.php"); // Painel de professor
                exit();
            } elseif ($tipo === 'aluno') {
                $_SESSION['id_aluno'] = $usuario['id']; // ID do aluno
                header("Location: painel_aluno.php"); // Painel de aluno
                exit();
            }
        } else {
            // Senha incorreta
            header("Location: index.php?error=Senha incorreta");
            exit();
        }
    } else {
        // Nome de usuário não encontrado
        header("Location: index.php?error=Usuário não encontrado");
        exit();
    }
} else {
    // Acesso direto à página sem envio do formulário
    header("Location: index.php?error=Acesso não autorizado");
    exit();
}

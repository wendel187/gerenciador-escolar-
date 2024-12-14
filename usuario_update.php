<?php
session_start();
require 'conexaoBD.php';

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $tipo = $_POST['tipo'];
    $password = $_POST['password'];

    // Se uma nova senha foi fornecida, hash a senha antes de atualizar
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Atualiza a tabela de usuários
        $sql = "UPDATE usuarios SET username = ?, password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $username, $hashed_password, $id);
        mysqli_stmt_execute($stmt);
        
        // Atualiza a tabela de alunos ou professores, dependendo do tipo
        if ($tipo === 'aluno') {
            $sql = "UPDATE usuarios_alunos SET username = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $username, $id);
            mysqli_stmt_execute($stmt);
        } elseif ($tipo === 'professor') {
            $sql = "UPDATE usuarios_professores SET username = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $username, $id);
            mysqli_stmt_execute($stmt);
        }
    } else {
        // Se a senha não foi alterada, atualiza apenas o nome de usuário
        $sql = "UPDATE usuarios SET username = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $username, $id);
        mysqli_stmt_execute($stmt);
        
        // Atualiza a tabela de alunos ou professores, dependendo do tipo
        if ($tipo === 'aluno') {
            $sql = "UPDATE usuarios_alunos SET username = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $username, $id);
            mysqli_stmt_execute($stmt);
        } elseif ($tipo === 'professor') {
            $sql = "UPDATE usuarios_professores SET username = ? WHERE id = ?";
            $stmt = mysqli_prepare($conexao, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $username, $id);
            mysqli_stmt_execute($stmt);
        }
    }

    $_SESSION['mensagem'] = "Usuário atualizado com sucesso.";
    header('Location: usuarios.php'); // Redireciona para a lista de usuários
    exit();
} else {
    $_SESSION['mensagem'] = "Método de requisição inválido.";
    header('Location: usuarios.php'); // Redireciona para a lista de usuários
    exit();
}
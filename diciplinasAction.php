<?php
session_start();
require 'conexaoBD.php';

// Função para redirecionar com mensagem
function redirectWithMessage($message) {
    $_SESSION['mensagem'] = $message;
    header('Location: diciplinas.php');
    exit;
}

// Criação de uma nova disciplina
if (isset($_POST['create_disciplina'])) {
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));

    // SQL de inserção no banco
    $sql = "INSERT INTO disciplinas (nome, descricao) VALUES ('$nome', '$descricao')";

    if (mysqli_query($conexao, $sql)) {
        redirectWithMessage('Disciplina criada com sucesso');
    } else {
        redirectWithMessage('Erro: ' . mysqli_error($conexao)); // Mostra erro
    }
}

// Atualização de uma disciplina existente
if (isset($_POST['update_disciplina'])) {
    $disciplina_id = mysqli_real_escape_string($conexao, $_POST['disciplina_id']);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST['descricao']));

    // SQL de atualização
    $sql = "UPDATE disciplinas SET nome = '$nome', descricao = '$descricao' WHERE id = '$disciplina_id'";

    if (mysqli_query($conexao, $sql)) {
        redirectWithMessage('Disciplina atualizada com sucesso');
    } else {
        redirectWithMessage('Erro: ' . mysqli_error($conexao)); // Mostra erro
    }
}

// Exclusão de uma disciplina
if (isset($_POST['delete_disciplina'])) {
    $disciplina_id = mysqli_real_escape_string($conexao, $_POST['delete_disciplina']);
    
    // SQL de exclusão
    $sql = "DELETE FROM disciplinas WHERE id = '$disciplina_id'";
    
    if (mysqli_query($conexao, $sql)) {
        redirectWithMessage('Disciplina deletada com sucesso');
    } else {
        redirectWithMessage('Erro: ' . mysqli_error($conexao)); // Mostra erro
    }
}
?>

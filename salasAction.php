<?php
session_start();
require 'conexaoBD.php';

// Função para redirecionar com mensagem
function redirectWithMessage($message) {
    $_SESSION['mensagem'] = $message;
    header('Location: salas.php');
    exit;
}

// Criar nova sala
if (isset($_POST['create_sala'])) {
    $numero = mysqli_real_escape_string($conexao, $_POST['numero']);
    $sql = "INSERT INTO salas (numero) VALUES ('$numero')";

    if (mysqli_query($conexao, $sql)) {
        redirectWithMessage('Sala criada com sucesso');
    } else {
        redirectWithMessage('Erro: ' . mysqli_error($conexao));
    }
}

// Atualizar sala existente
if (isset($_POST['update_sala'])) {
    $sala_id = mysqli_real_escape_string($conexao, $_POST['sala_id']);
    $numero = mysqli_real_escape_string($conexao, $_POST['numero']);
    $sql = "UPDATE salas SET numero = '$numero' WHERE id = '$sala_id'";

    if (mysqli_query($conexao, $sql)) {
        redirectWithMessage('Sala atualizada com sucesso');
    } else {
        redirectWithMessage('Erro: ' . mysqli_error($conexao));
    }
}

// Excluir sala
if (isset($_POST['delete_sala'])) {
    $sala_id = mysqli_real_escape_string($conexao, $_POST['delete_sala']);
    $sql = "DELETE FROM salas WHERE id = '$sala_id'";

    if (mysqli_query($conexao, $sql)) {
        redirectWithMessage('Sala deletada com sucesso');
    } else {
        redirectWithMessage('Erro: ' . mysqli_error($conexao));
    }
}
?>

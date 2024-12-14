<?php
session_start();
require 'conexaoBD.php';

if (isset($_POST['delete_turma'])) {
    $turma_id = mysqli_real_escape_string($conexao, $_POST['turma_id']); // Obtem o ID da turma

    // Consulta de exclusão
    $query = "DELETE FROM turmas WHERE id = '$turma_id'";
    $query_run = mysqli_query($conexao, $query);

    if ($query_run) {
        $_SESSION['mensagem'] = "Turma excluída com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir a turma: " . mysqli_error($conexao);
    }
}

// Redireciona de volta para a página de listagem de turmas
header("Location: turma.php");
exit;
?>

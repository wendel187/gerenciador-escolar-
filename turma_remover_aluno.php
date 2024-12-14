<?php
session_start();
require 'conexaoBD.php';

// Verifica se o ID do aluno e da turma foram enviados
if (isset($_POST['aluno_id']) && isset($_POST['turma_id'])) {
    $aluno_id = $_POST['aluno_id'];
    $turma_id = $_POST['turma_id'];

    // Remove o aluno da turma
    $sql = "DELETE FROM matriculas WHERE aluno_id = ? AND turma_id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $aluno_id, $turma_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $_SESSION['mensagem'] = 'Aluno removido da turma com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Erro ao remover aluno da turma.';
    }
    header('Location: turma_ver_alunos.php?id=' . $turma_id);
    exit;
} else {
    header('Location: turma.php'); // Redireciona se os IDs não forem fornecidos
    exit;
}

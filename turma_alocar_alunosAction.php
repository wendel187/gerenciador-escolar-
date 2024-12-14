<?php
session_start();
require 'conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $turma_id = $_POST['turma_id'];
    $aluno_id = $_POST['aluno_id'];

    // Consulta para verificar se a matrícula já existe
    $checkSql = "SELECT * FROM matriculas WHERE turma_id = ? AND aluno_id = ?";
    $stmt = mysqli_prepare($conexao, $checkSql);
    mysqli_stmt_bind_param($stmt, "ii", $turma_id, $aluno_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        // Se não existir, insere a nova matrícula
        $insertSql = "INSERT INTO matriculas (turma_id, aluno_id) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($conexao, $insertSql);
        mysqli_stmt_bind_param($insertStmt, "ii", $turma_id, $aluno_id);
        mysqli_stmt_execute($insertStmt);

        $_SESSION['mensagem'] = "Aluno alocado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Este aluno já está alocado nesta turma.";
    }

    // Redireciona de volta para a página de alocação
    header("Location: turma_alocar_alunos.php?id=$turma_id");
    exit;
}

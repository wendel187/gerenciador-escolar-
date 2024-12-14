<?php
session_start();
require 'conexaoBD.php';

// Verifica se a requisição foi feita com o método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o ID foi enviado
    if (isset($_POST['id'])) {
        $usuarioId = mysqli_real_escape_string($conexao, $_POST['id']);

        // SQL para excluir o usuário da tabela de usuários
        $sqlDeleteUsuario = "DELETE FROM usuarios WHERE id = '$usuarioId'";
        $sqlDeleteAluno = "DELETE FROM usuarios_alunos WHERE id = '$usuarioId'";
        $sqlDeleteProfessor = "DELETE FROM usuarios_professores WHERE id = '$usuarioId'";

        // Executa as consultas de exclusão
        $resultadoUsuario = mysqli_query($conexao, $sqlDeleteUsuario);
        $resultadoAluno = mysqli_query($conexao, $sqlDeleteAluno);
        $resultadoProfessor = mysqli_query($conexao, $sqlDeleteProfessor);

        // Verifica se as exclusões foram bem-sucedidas
        if ($resultadoUsuario || $resultadoAluno || $resultadoProfessor) {
            $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao excluir usuário: " . mysqli_error($conexao);
        }
    } else {
        $_SESSION['mensagem'] = "ID de usuário não fornecido.";
    }
} else {
    $_SESSION['mensagem'] = "Método de requisição inválido.";
}

// Redireciona de volta para a lista de usuários
header("Location: usuarios.php"); // Altere para o nome correto do arquivo de listagem
exit();
?>
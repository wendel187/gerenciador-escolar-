<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: index.php?error=Acesso restrito aos professores");
    exit();
}

// Verifica se a requisição foi feita para excluir uma aula
if (isset($_POST['delete_aula'])) {
    $aula_id = $_POST['delete_aula'];

    try {
        // Exclui a aula do banco de dados
        $sql = "DELETE FROM aulas WHERE id = :aula_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':aula_id', $aula_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona de volta para a página da turma
        header("Location: ver_aula.php?turma_id=" . $_GET['turma_id'] . "&success=Aula excluída com sucesso!");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao excluir aula: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: painel_professor.php?error=Ação não permitida");
    exit();
}
?>

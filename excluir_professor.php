<?php
include 'conexaoBD.php';

$id = $_GET['id'];

// Exclui o professor
$sql = "DELETE FROM professores WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    header('Location: teachers.php'); // Redireciona de volta para a lista de professores
    exit();
} else {
    echo "Erro ao excluir professor.";
}
?>

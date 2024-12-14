<?php
require 'conexaoBD.php';

$tipo = $_GET['tipo'];

if ($tipo === 'professor') {
    $query = "SELECT id, nome FROM professores";
} elseif ($tipo === 'aluno') {
    $query = "SELECT id, nome FROM alunos";
} else {
    echo json_encode([]);
    exit;
}

$result = mysqli_query($conexao, $query);

$ids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ids[] = $row;
}

echo json_encode($ids);
?>

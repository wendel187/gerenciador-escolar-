<?php
session_start();
require 'conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turma_id = mysqli_real_escape_string($conexao, $_POST['turma_id']);
    $disciplina = mysqli_real_escape_string($conexao, $_POST['disciplina']);
    $professor = mysqli_real_escape_string($conexao, $_POST['professor']);
    $sala = mysqli_real_escape_string($conexao, $_POST['sala']);
    $turno = mysqli_real_escape_string($conexao, $_POST['turno']);

    // Captura o horário no formato "08:00 - 10:00"
    $horario = mysqli_real_escape_string($conexao, $_POST['horario']);
    
    $data_inicio = mysqli_real_escape_string($conexao, $_POST['data_inicio']);
    $data_fim = mysqli_real_escape_string($conexao, $_POST['data_fim']);
    
    // Atualiza a turma no banco de dados
    $sql = "UPDATE turmas SET disciplina_id='$disciplina', professor_id='$professor', sala_id='$sala', turno='$turno', horario='$horario', data_inicio='$data_inicio', data_fim='$data_fim' WHERE id='$turma_id'";
    
    if (mysqli_query($conexao, $sql)) {
        header("Location: turma.php?msg=Turma atualizada com sucesso");
    } else {
        echo "Erro ao atualizar a turma: " . mysqli_error($conexao);
    }
}
?>

<?php
session_start();
require 'conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se os campos obrigatórios estão definidos e não estão vazios
    $disciplina_id = isset($_POST['disciplina_id']) ? (int)$_POST['disciplina_id'] : null;
    $professor_id = isset($_POST['professor_id']) ? (int)$_POST['professor_id'] : null;
    $sala_id = isset($_POST['sala_id']) ? (int)$_POST['sala_id'] : null;
    $turno = isset($_POST['turno']) ? $_POST['turno'] : null;
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $horario = $_POST['horario'];
    $dias_aula = isset($_POST['dias_aula']) ? implode(", ", $_POST['dias_aula']) : null;

    // Verifica se todos os valores necessários foram atribuídos
    if ($disciplina_id && $professor_id && $sala_id && $turno && $data_inicio && $data_fim && $horario && $dias_aula) {
        // Prepara a query para inserção
        $sql = "INSERT INTO turmas (disciplina_id, professor_id, sala_id, turno, data_inicio, data_fim, horario, dias_aula) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);

        // Bind dos parâmetros com os valores capturados
        mysqli_stmt_bind_param($stmt, 'iiisssss', $disciplina_id, $professor_id, $sala_id, $turno, $data_inicio, $data_fim, $horario, $dias_aula);

        // Executa a query e verifica se a inserção foi bem-sucedida
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensagem'] = "Turma cadastrada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao cadastrar a turma: " . mysqli_error($conexao);
        }
    } else {
        $_SESSION['mensagem'] = "Por favor, preencha todos os campos obrigatórios.";
    }

    // Redireciona para a página de visualização das turmas
    header("Location: turma.php");
    exit;
}
?>
<?php
session_start();
require 'conexaoBD.php';

if (isset($_GET['id'])) {
    $turma_id = mysqli_real_escape_string($conexao, $_GET['id']);

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
header("Location: turma_index.php");
exit;
?>

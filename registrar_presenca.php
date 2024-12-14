<?php
require_once('conexaoBD.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['aula_id'])) {
    $aula_id = $_GET['aula_id'];

    // Consultar os alunos matriculados na turma associada à aula
    $sql = "SELECT m.id AS matricula_id, a.nome AS aluno_nome
            FROM matriculas m
            JOIN alunos a ON m.aluno_id = a.id
            JOIN turmas t ON m.turma_id = t.id
            JOIN aulas aa ON t.id = aa.turma_id
            WHERE aa.id = :aula_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':aula_id', $aula_id, PDO::PARAM_INT);
    $stmt->execute();
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Registrar a presença dos alunos
        $dataAtual = date('Y-m-d'); // Salvar a data atual em uma variável
        foreach ($alunos as $aluno) {
            $status = isset($_POST['presenca_' . $aluno['matricula_id']]) ? 'presente' : 'ausente';
            
            // Verificar se já existe registro de presença para o aluno naquela aula
            $sql_check = "SELECT * FROM frequencia WHERE matricula_id = :matricula_id AND data_aula = :data_aula";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bindParam(':matricula_id', $aluno['matricula_id'], PDO::PARAM_INT);
            $stmt_check->bindParam(':data_aula', $dataAtual, PDO::PARAM_STR); // Passar a variável
            $stmt_check->execute();
            $check = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if (!$check) {
                // Inserir a presença
                $sql_insert = "INSERT INTO frequencia (matricula_id, data_aula, status) 
                               VALUES (:matricula_id, :data_aula, :status)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(':matricula_id', $aluno['matricula_id'], PDO::PARAM_INT);
                $stmt_insert->bindParam(':data_aula', $dataAtual, PDO::PARAM_STR); // Passar a variável
                $stmt_insert->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt_insert->execute();
            }
        }

        // Após registrar as presenças, exibir uma mensagem simples de sucesso
        echo "<script>alert('Presença registrada com sucesso!');</script>";
    }
} else {
    // Caso não tenha aula_id
    echo "ID da aula não especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Presença</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h3>Registrar Presença</h3>
        <form method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Presença</th>
                        <th>Falta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($alunos as $aluno) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($aluno['aluno_nome']) . "</td>";
                        echo "<td>";
                        echo "<input type='radio' name='presenca_" . $aluno['matricula_id'] . "' value='presente' class='form-check-input'>";
                        echo "</td>";
                        echo "<td>";
                        echo "<input type='radio' name='presenca_" . $aluno['matricula_id'] . "' value='ausente' class='form-check-input'>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Registrar Presença</button>
        </form>
    </div>
</body>
</html>

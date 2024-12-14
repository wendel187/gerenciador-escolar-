<?php
// Incluir o arquivo de conexão com o banco de dados
require_once('conexaoBD.php');

// Se o usuário não estiver logado, redireciona para a página de login
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
try {
    if (!isset($conn)) {
        $conn = new PDO("mysql:host=localhost;dbname=gerenciamento_escolar", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}

// Recuperar o id da aula a ser editada
if (isset($_GET['aula_id'])) {
    $aula_id = $_GET['aula_id'];

    // Buscar informações da aula
    $sql = "SELECT * FROM aulas WHERE id = :aula_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':aula_id', $aula_id, PDO::PARAM_INT);
    $stmt->execute();
    $aula = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$aula) {
        die("Aula não encontrada!");
    }

    $data_aula = $aula['data'];
} else {
    die("ID da aula não fornecido!");
}

// Processar o envio do formulário para editar a chamada
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];  // 'presente', 'ausente' ou 'justificado'
    $matricula_id = $_POST['matricula_id'];  // ID do aluno

    // Atualizar o status da frequência
    $sql = "UPDATE frequencia SET status = :status WHERE matricula_id = :matricula_id AND data_aula = :data_aula";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':matricula_id', $matricula_id, PDO::PARAM_INT);
    $stmt->bindParam(':data_aula', $data_aula, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        echo "Frequência atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar frequência.";
    }
}

// Consultar as frequências da aula para exibição
$sql = "SELECT f.matricula_id, f.status, m.aluno_nome
        FROM frequencia f
        JOIN matriculas m ON f.matricula_id = m.id
        WHERE f.data_aula = :data_aula";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':data_aula', $data_aula, PDO::PARAM_STR);
$stmt->execute();
$frequencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Chamada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h4>Editar Chamada - Aula: <?php echo htmlspecialchars($aula['nome_aula']); ?></h4>
        <form method="POST">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nome do Aluno</th>
                        <th>Status da Frequência</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($frequencias as $frequencia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($frequencia['aluno_nome']); ?></td>
                            <td>
                                <select name="status" class="form-select">
                                    <option value="presente" <?php if ($frequencia['status'] == 'presente') echo 'selected'; ?>>Presente</option>
                                    <option value="ausente" <?php if ($frequencia['status'] == 'ausente') echo 'selected'; ?>>Ausente</option>
                                    <option value="justificado" <?php if ($frequencia['status'] == 'justificado') echo 'selected'; ?>>Justificado</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="matricula_id" value="<?php echo $frequencia['matricula_id']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Salvar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
        <a href="lista_aulas.php" class="btn btn-danger">Voltar</a>
    </div>
</body>
</html>

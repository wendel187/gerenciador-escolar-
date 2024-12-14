<?php
session_start();
require_once('conexaoBD.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matricula_id = $_POST['matricula_id'];
    $nota = $_POST['nota'];
    $descricao = $_POST['descricao'];
    $data_lancamento = date('Y-m-d');

    $sql = "INSERT INTO notas (matricula_id, nota, descricao, data_lancamento) 
            VALUES (:matricula_id, :nota, :descricao, :data_lancamento)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':matricula_id', $matricula_id, PDO::PARAM_INT);
    $stmt->bindParam(':nota', $nota);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':data_lancamento', $data_lancamento);

    if ($stmt->execute()) {
        header("Location: boletim.php?turma_id=" . $_POST['turma_id'] . "&success=Nota adicionada com sucesso");
    } else {
        echo "Erro ao adicionar nota.";
    }
}

// Listar alunos para selecionar
if (isset($_GET['turma_id'])) {
    $turma_id = $_GET['turma_id'];
    $sql = "SELECT m.id AS matricula_id, a.nome AS aluno_nome 
            FROM matriculas m
            JOIN alunos a ON m.aluno_id = a.id
            WHERE m.turma_id = :turma_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
    $stmt->execute();
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Nota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Adicionar Nota</h2>
    <form method="POST">
        <input type="hidden" name="turma_id" value="<?php echo htmlspecialchars($turma_id); ?>">
        <div class="mb-3">
            <label for="matricula_id" class="form-label">Aluno</label>
            <select name="matricula_id" id="matricula_id" class="form-select" required>
                <option value="">Selecione um aluno</option>
                <?php foreach ($alunos as $aluno): ?>
                    <option value="<?php echo $aluno['matricula_id']; ?>">
                        <?php echo htmlspecialchars($aluno['aluno_nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="nota" class="form-label">Nota</label>
            <input type="number" step="0.01" min="0" max="10" name="nota" id="nota" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="boletim.php?turma_id=<?php echo $turma_id; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

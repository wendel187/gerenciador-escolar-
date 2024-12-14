<?php
session_start();
require 'conexaoBD.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php?error=Acesso restrito');
    exit();
}

// Inserir um novo período
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $ponto_minimo = $_POST['ponto_minimo'];
    $ponto_maximo = $_POST['ponto_maximo'];
    $turma_id = $_POST['turma_id'];

    try {
        $sql = "INSERT INTO periodos (data_inicio, data_fim, ponto_minimo, ponto_maximo, turma_id) 
                VALUES (:data_inicio, :data_fim, :ponto_minimo, :ponto_maximo, :turma_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->bindParam(':ponto_minimo', $ponto_minimo);
        $stmt->bindParam(':ponto_maximo', $ponto_maximo);
        $stmt->bindParam(':turma_id', $turma_id);
        $stmt->execute();
        $_SESSION['success'] = 'Período adicionado com sucesso!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao adicionar período: ' . $e->getMessage();
    }
    header("Location: gerenciar_periodos.php?turma_id=$turma_id");
    exit();
}

// Recupera os períodos da turma
$turma_id = $_GET['turma_id'] ?? null;
if (!$turma_id) {
    header('Location: painel_professor.php?error=Turma não especificada');
    exit();
}

try {
    $sql = "SELECT * FROM periodos WHERE turma_id = :turma_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
    $stmt->execute();
    $periodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = 'Erro ao carregar períodos: ' . $e->getMessage();
    $periodos = [];
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciar Períodos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container mt-4">
    <?php include('mensagem.php'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Gerenciar Períodos
                        <a href="painel_professor.php" class="btn btn-danger float-end">Voltar</a>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Formulário para adicionar período -->
                    <form action="" method="POST" class="mb-4">
                        <input type="hidden" name="turma_id" value="<?= htmlspecialchars($turma_id) ?>">
                        <div class="mb-3">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_fim" class="form-label">Data de Fim</label>
                            <input type="date" id="data_fim" name="data_fim" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ponto_minimo" class="form-label">Ponto Mínimo</label>
                            <input type="number" id="ponto_minimo" name="ponto_minimo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ponto_maximo" class="form-label">Ponto Máximo</label>
                            <input type="number" id="ponto_maximo" name="ponto_maximo" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar Período</button>
                    </form>

                    <!-- Lista de períodos -->
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data de Início</th>
                            <th>Data de Fim</th>
                            <th>Ponto Mínimo</th>
                            <th>Ponto Máximo</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($periodos)): ?>
                            <?php foreach ($periodos as $periodo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($periodo['id']) ?></td>
                                    <td><?= htmlspecialchars($periodo['data_inicio']) ?></td>
                                    <td><?= htmlspecialchars($periodo['data_fim']) ?></td>
                                    <td><?= htmlspecialchars($periodo['ponto_minimo']) ?></td>
                                    <td><?= htmlspecialchars($periodo['ponto_maximo']) ?></td>
                                    <td>
                                        <a href="editar_periodo.php?id=<?= $periodo['id'] ?>" class="btn btn-success btn-sm">
                                            <span class="bi-pencil-fill"></span>&nbsp;Editar
                                        </a>
                                        <form action="excluir_periodo.php" method="POST" class="d-inline">
                                            <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="excluir_periodo" value="<?= $periodo['id'] ?>" class="btn btn-danger btn-sm">
                                                <span class="bi-trash3-fill"></span>&nbsp;Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Nenhum período encontrado</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

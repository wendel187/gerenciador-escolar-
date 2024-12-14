<?php
session_start();
require 'conexaoBD.php';

$turma_id = $_GET['id']; // Recebe o ID da turma

// Verifica se uma pesquisa foi realizada
$termoPesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

// Consulta para buscar alunos com base no termo de pesquisa
$sql = "SELECT * FROM alunos WHERE nome LIKE '%$termoPesquisa%' OR email LIKE '%$termoPesquisa%' ORDER BY nome";
$alunos = mysqli_query($conexao, $sql);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alocar Alunos na Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        
        <div class="card">
            <div class="card-header">
                <h4>Alocar Alunos na Turma ID: <?= $turma_id ?>
                    <a href="turma.php" class="btn btn-danger float-end">Voltar</a>
                </h4>
            </div>
            
            <div class="card-body">
                <!-- Formulário de Pesquisa -->
                <form action="turma_alocar_alunos.php" method="GET" class="mb-3">
                    <input type="hidden" name="id" value="<?= $turma_id ?>">
                    <div class="input-group">
                        <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar aluno por nome ou email" value="<?= htmlspecialchars($termoPesquisa) ?>">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i>&nbsp;Buscar</button>
                    </div>
                </form>

                <!-- Tabela de Alunos -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data de Nascimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($alunos) > 0): ?>
                            <?php while ($aluno = mysqli_fetch_assoc($alunos)): ?>
                                <tr>
                                    <td><?= $aluno['id'] ?></td>
                                    <td><?= $aluno['nome'] ?></td>
                                    <td><?= $aluno['email'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?></td>
                                    <td>
                                        <!-- Botão para Alocar o Aluno -->
                                        <form action="turma_alocar_alunosAction.php" method="POST" class="d-inline">
                                            <input type="hidden" name="turma_id" value="<?= $turma_id ?>">
                                            <input type="hidden" name="aluno_id" value="<?= $aluno['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm" title="Alocar aluno">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhum aluno encontrado</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

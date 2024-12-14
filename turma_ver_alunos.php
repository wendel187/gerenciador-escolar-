<?php
session_start();
require 'conexaoBD.php';

// Verifica se o ID da turma foi passado
if (!isset($_GET['id'])) {
    header('Location: turma.php'); // Redireciona se o ID não for fornecido
    exit;
}

// Captura o ID da turma
$id_turma = $_GET['id'];

// Consulta para buscar os alunos matriculados na turma
$sql = "
    SELECT alunos.id, alunos.nome
    FROM alunos
    JOIN matriculas ON alunos.id = matriculas.aluno_id
    WHERE matriculas.turma_id = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_turma);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ver Alunos da Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Alunos da Turma
                            <a href="turma.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($aluno = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?=$aluno['id']?></td>
                                            <td><?=$aluno['nome']?></td>
                                            <td>
                                                <form action="turma_remover_aluno.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="aluno_id" value="<?=$aluno['id'];?>">
                                                    <input type="hidden" name="turma_id" value="<?=$id_turma;?>">
                                                    <button onclick="return confirm('Tem certeza que deseja remover este aluno da turma?')" type="submit" name="remove_aluno" class="btn btn-danger btn-sm">
                                                        <span class="bi-x-circle-fill"></span>&nbsp;Remover
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Nenhum aluno encontrado nesta turma</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php require_once('rodape.php'); ?>
</body>
</html>

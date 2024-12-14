<?php
session_start();
require 'conexaoBD.php';

// Consulta para buscar as turmas com as informações relacionadas
$sql = "
    SELECT turmas.id, turmas.horario, turmas.dias_aula, 
           disciplinas.nome AS disciplina_nome, salas.numero AS sala_numero, 
           professores.nome AS professor_nome
    FROM turmas
    JOIN disciplinas ON turmas.disciplina_id = disciplinas.id
    JOIN salas ON turmas.sala_id = salas.id
    JOIN professores ON turmas.professor_id = professores.id
";
$turmas = mysqli_query($conexao, $sql);
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Turmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lista de Turmas
                            <a href="turma_create.php" class="btn btn-primary float-end">Adicionar Turma</a>
                            <a href="principal.php" class="btn btn-danger float-end me-2">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Disciplina</th>
                                    <th>Sala</th>
                                    <th>Professor</th>
                                    <th>Horário</th>
                                    <th>Dias de Aula</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($turmas) > 0): ?>
                                    <?php while ($turma = mysqli_fetch_assoc($turmas)): ?>
                                        <tr>
                                            <td><?=$turma['id']?></td>
                                            <td><?=$turma['disciplina_nome']?></td>
                                            <td><?=$turma['sala_numero']?></td>
                                            <td><?=$turma['professor_nome']?></td>
                                            <td><?=$turma['horario']?></td>
                                            <td><?=$turma['dias_aula']?></td>
                                            <td>
    <a href="turma_view.php?id=<?=$turma['id']?>" class="btn btn-secondary btn-sm">
        <span class="bi-eye-fill"></span>&nbsp;Ver Turma
    </a>
    <a href="turma_ver_alunos.php?id=<?=$turma['id']?>" class="btn btn-info btn-sm">
        <span class="bi-person-fill"></span>&nbsp;Ver Alunos
    </a>
    <a href="turma_edit.php?id=<?=$turma['id']?>" class="btn btn-success btn-sm">
        <span class="bi-pencil-fill"></span>&nbsp;Editar
    </a>
    <a href="turma_alocar_alunos.php?id=<?=$turma['id']?>" class="btn btn-primary btn-sm">
        <span class="bi-person-plus-fill"></span>&nbsp;Alocar Alunos
    </a>
    <form action="turma_deleteAction.php" method="POST" class="d-inline">
        <input type="hidden" name="turma_id" value="<?=$turma['id'];?>">
        <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_turma" class="btn btn-danger btn-sm">
            <span class="bi-trash3-fill"></span>&nbsp;Excluir
        </button>
    </form>
</td>

                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhuma turma encontrada</td>
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

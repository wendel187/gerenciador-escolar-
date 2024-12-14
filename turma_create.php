<?php
session_start();
require 'conexaoBD.php';

// Busca disciplinas, professores e salas para preencher os selects
$disciplinas = mysqli_query($conexao, 'SELECT * FROM disciplinas');
$professores = mysqli_query($conexao, 'SELECT * FROM professores');
$salas = mysqli_query($conexao, 'SELECT * FROM salas');
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Turmas</title>
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
                        <h4>Cadastro de Turmas
                            <a href="turma.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="turma_createAction.php" method="POST">
                            <div class="mb-3">
                                <label for="disciplina_id" class="form-label">Disciplina</label>
                                <select name="disciplina_id" class="form-select" id="disciplina_id" required>
                                    <option value="">Selecione uma disciplina</option>
                                    <?php while ($disciplina = mysqli_fetch_assoc($disciplinas)): ?>
                                        <option value="<?=$disciplina['id']?>"><?=$disciplina['nome']?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="professor_id" class="form-label">Professor</label>
                                <select name="professor_id" class="form-select" id="professor_id" required>
                                    <option value="">Selecione um professor</option>
                                    <?php while ($professor = mysqli_fetch_assoc($professores)): ?>
                                        <option value="<?=$professor['id']?>"><?=$professor['nome']?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sala_id" class="form-label">Sala</label>
                                <select name="sala_id" class="form-select" id="sala_id" required>
                                    <option value="">Selecione uma sala</option>
                                    <?php while ($sala = mysqli_fetch_assoc($salas)): ?>
                                        <option value="<?=$sala['id']?>"><?=$sala['numero']?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="horario" class="form-label">Horário</label>
                                <input type="text" name="horario" class="form-control" id="horario" placeholder="Ex: 08:00 - 10:00" required>
                            </div>
                            <div class="mb-3">
    <label for="turno" class="form-label">Turno</label>
    <select name="turno" class="form-select" id="turno" required>
        <option value="">Selecione o turno</option>
        <option value="manhã">Manhã</option>
        <option value="tarde">Tarde</option>
        <option value="noite">Noite</option>
    </select>
</div>

                            <div class="mb-3">
                                <label for="dias_aula" class="form-label">Dias de Aula</label><br>
                                <input type="checkbox" name="dias_aula[]" value="Segunda"> Segunda
                                <input type="checkbox" name="dias_aula[]" value="Terça"> Terça
                                <input type="checkbox" name="dias_aula[]" value="Quarta"> Quarta
                                <input type="checkbox" name="dias_aula[]" value="Quinta"> Quinta
                                <input type="checkbox" name="dias_aula[]" value="Sexta"> Sexta
                            </div>
                            <div class="mb-3">
                                <label for="data_inicio" class="form-label">Data de Início</label>
                                <input type="date" name="data_inicio" class="form-control" id="data_inicio" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_fim" class="form-label">Data de Fim</label>
                                <input type="date" name="data_fim" class="form-control" id="data_fim" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar Turma</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php require_once('rodape.php'); ?>
</body>
</html>

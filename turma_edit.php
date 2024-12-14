<?php
session_start();
require 'conexaoBD.php';

if (isset($_GET['id'])) {
    $turma_id = mysqli_real_escape_string($conexao, $_GET['id']);

    // Consulta para obter os dados da turma
    $sql = "SELECT * FROM turmas WHERE id='$turma_id'";
    $query = mysqli_query($conexao, $sql);
    $turma = mysqli_fetch_array($query);
    if (!$turma) {
        echo "<h5>Turma não encontrada</h5>";
        exit;
    }
} else {
    header("Location: turma.php"); // Redireciona se não houver ID
    exit;
}

// Consultas para obter os nomes
$disciplinas = mysqli_query($conexao, "SELECT * FROM disciplinas");
$professores = mysqli_query($conexao, "SELECT * FROM professores");
$salas = mysqli_query($conexao, "SELECT * FROM salas");

if (!$salas) {
    echo "Erro na consulta de salas: " . mysqli_error($conexao);
    exit;
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar Turma
                            <a href="turma.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="turma_updateAction.php" method="POST">
                            <input type="hidden" name="turma_id" value="<?=$turma['id'];?>">

                            <div class="mb-3">
                                <label>Disciplina</label>
                                <select name="disciplina" class="form-control" required>
                                    <?php while ($disciplina = mysqli_fetch_array($disciplinas)): ?>
                                        <option value="<?=$disciplina['id'];?>" <?= ($disciplina['id'] == $turma['disciplina_id']) ? 'selected' : ''; ?>>
                                            <?=$disciplina['nome'];?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Professor</label>
                                <select name="professor" class="form-control" required>
                                    <?php while ($professor = mysqli_fetch_array($professores)): ?>
                                        <option value="<?=$professor['id'];?>" <?= ($professor['id'] == $turma['professor_id']) ? 'selected' : ''; ?>>
                                            <?=$professor['nome'];?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Sala</label>
                                <select name="sala" class="form-control" required>
                                    <?php while ($sala = mysqli_fetch_array($salas)): ?>
                                        <option value="<?=$sala['id'];?>" <?= ($sala['id'] == $turma['sala_id']) ? 'selected' : ''; ?>>
                                            <?=$sala['numero'];?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Turno</label>
                                <select name="turno" class="form-control" required>
                                    <option value="manhã" <?= ($turma['turno'] == 'manhã') ? 'selected' : ''; ?>>Manhã</option>
                                    <option value="tarde" <?= ($turma['turno'] == 'tarde') ? 'selected' : ''; ?>>Tarde</option>
                                    <option value="noite" <?= ($turma['turno'] == 'noite') ? 'selected' : ''; ?>>Noite</option>
                                </select>
                            </div>

                            <div class="mb-3">
    <label>Horário das Aulas</label>
    <input type="text" name="horario" class="form-control" value="<?=$turma['horario'];?>" required placeholder="Ex: 08:00 - 10:00">
</div>


                            <div class="mb-3">
                                <label>Data de Início</label>
                                <input type="date" name="data_inicio" class="form-control" value="<?=$turma['data_inicio'];?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Data de Fim</label>
                                <input type="date" name="data_fim" class="form-control" value="<?=$turma['data_fim'];?>" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

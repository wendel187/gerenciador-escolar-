<?php
session_start();
require 'conexaoBD.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexao, $_GET['id']);
    $sql = "SELECT * FROM salas WHERE id = '$id'";
    $query = mysqli_query($conexao, $sql);
    $sala = mysqli_fetch_array($query);
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Editar Sala
                            <a href="salas.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="salasAction.php" method="POST">
                            <input type="hidden" name="sala_id" value="<?= $sala['id'] ?>">
                            <div class="mb-3">
                                <label>Número da Sala</label>
                                <input type="number" name="numero" value="<?= $sala['numero'] ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="update_sala" class="btn btn-primary">Atualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

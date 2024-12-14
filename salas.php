<?php
session_start();
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lista de Salas
                            <a href="salasCreate.php" class="btn btn-primary float-end">Adicionar Sala</a>
                            <a href="principal.php" class="btn btn-danger float-end me-2">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Número da Sala</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = 'SELECT * FROM salas';
                                $salas = mysqli_query($conexao, $sql);
                                if (mysqli_num_rows($salas) > 0) {
                                    foreach ($salas as $sala) {
                                ?>
                                <tr>
                                    <td><?=$sala['id']?></td>
                                    <td><?=$sala['numero']?></td>
                                    <td>
                                        <a href="salasEdit.php?id=<?=$sala['id']?>" class="btn btn-success btn-sm">Editar</a>
                                        <form action="salasAction.php" method="POST" class="d-inline">
                                            <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_sala" value="<?=$sala['id']?>" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<h5>Nenhuma sala encontrada</h5>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuário - Visualizar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Visualizar Professor
                            <a href="teachers.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['id'])) {
                            $professor_id = mysqli_real_escape_string($conexao, $_GET['id']);
                            $sql = "SELECT * FROM professores WHERE id='$professor_id'";
                            $query = mysqli_query($conexao, $sql);
                            if (mysqli_num_rows($query) > 0) {
                                $professor = mysqli_fetch_array($query);
                        ?>
                        <div class="mb-3">
                            <label>Nome</label>
                            <p class="form-control">
                                <?=$professor['nome'];?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <p class="form-control">
                                <?=$professor['email'];?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>Especialidade</label>
                            <p class="form-control">
                                <?=$professor['especialidade'];?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>Data de Contratação</label>
                            <p class="form-control">
                                <?=date('d/m/Y', strtotime($professor['data_contratacao']));?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>CPF</label>
                            <p class="form-control">
                                <?=$professor['cpf'];?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>Endereço</label>
                            <p class="form-control">
                                <?=$professor['endereco'];?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>Telefone</label>
                            <p class="form-control">
                                <?=$professor['telefone'];?>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label>Salário</label>
                            <p class="form-control">
                                R$ <?=number_format($professor['salario'], 2, ',', '.');?>
                            </p>
                        </div>
                        <?php
                            } else {
                                echo "<h5>Professor não encontrado</h5>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

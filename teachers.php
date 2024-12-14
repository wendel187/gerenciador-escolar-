<?php
session_start();
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Professores</title>
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
                        <h4>Lista de Professores
                            <a href="professor_create.php" class="btn btn-primary float-end">Adicionar Professor</a>
                            <a href="principal.php" class="btn btn-danger float-end me-2">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Especialidade</th>
                                    <th>Salário</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Exibe os professores cadastrados
                                $sql = "SELECT * FROM professores";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($professores as $professor) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($professor['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($professor['nome']) . "</td>";
                                    echo "<td>" . htmlspecialchars($professor['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($professor['especialidade']) . "</td>";
                                    echo "<td>R$ " . number_format($professor['salario'], 2, ',', '.') . "</td>";
                                    echo "<td>";
                                    // Botão de visualizar
                                    echo "<a href='professor_view.php?id=" . $professor['id'] . "' class='btn btn-secondary btn-sm'><span class='bi-eye-fill'></span>&nbsp;Visualizar</a> ";
                                    // Botão de editar
                                    echo "<a href='editar_professor.php?id=" . $professor['id'] . "' class='btn btn-success btn-sm'><span class='bi-pencil-fill'></span>&nbsp;Editar</a> ";
                                    // Botão de excluir
                                    echo "<a href='excluir_professor.php?id=" . $professor['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Tem certeza que deseja excluir?')\"><span class='bi-trash3-fill'></span>&nbsp;Excluir</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
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

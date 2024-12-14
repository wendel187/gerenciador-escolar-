<?php
session_start();
require 'conexaoBD.php';

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $usuarioId = mysqli_real_escape_string($conexao, $_GET['id']);

    // Consulta o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE id = '$usuarioId'";
    $resultado = mysqli_query($conexao, $sql);

    // Verifica se o usuário foi encontrado
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
    } else {
        $_SESSION['mensagem'] = "Usuário não encontrado.";
        header("Location: usuarios.php");
        exit();
    }
} else {
    $_SESSION['mensagem'] = "ID de usuário não fornecido.";
    header("Location: usuarios.php");
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualizar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Detalhes do Usuário
                            <a href="usuarios.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><strong>ID:</strong></label>
                            <p class="form-control-static"><?= $usuario['id'] ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Nome de Usuário:</strong></label>
                            <p class="form-control-static"><?= htmlspecialchars($usuario['username']) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Tipo:</strong></label>
                            <p class="form-control-static"><?= ucfirst($usuario['tipo']) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Data de Cadastro:</strong></label>
                            <p class="form-control-static"><?= date('d/m/Y H:i:s', strtotime($usuario['data_cadastro'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

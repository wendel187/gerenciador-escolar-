<?php
session_start();
require 'conexaoBD.php';

// Verifica se o ID do usuário foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Busca os dados do usuário
    $sql = "SELECT id, username, tipo FROM usuarios 
            UNION 
            SELECT id, username, 'aluno' AS tipo FROM usuarios_alunos 
            UNION 
            SELECT id, username, 'professor' AS tipo FROM usuarios_professores 
            WHERE id = ?";
    
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    // Verifica se o usuário existe
    if (!$usuario) {
        $_SESSION['mensagem'] = "Usuário não encontrado.";
        header('Location: usuario_index.php'); // Redireciona para a lista de usuários
        exit();
    }
} else {
    $_SESSION['mensagem'] = "ID do usuário não fornecido.";
    header('Location: usuario_index.php'); // Redireciona para a lista de usuários
    exit();
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h4>Editar Usuário</h4>
        <form action="usuario_update.php" method="POST">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $usuario['username'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nova Senha (deixe em branco se não quiser alterar)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" id="tipo" name="tipo" required>
                    <option value="aluno" <?= ($usuario['tipo'] == 'aluno') ? 'selected' : '' ?>>Aluno</option>
                    <option value="professor" <?= ($usuario['tipo'] == 'professor') ? 'selected' : '' ?>>Professor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="usuarios.php" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
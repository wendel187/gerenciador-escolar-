<?php
session_start();
require 'conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $especialidade = $_POST['especialidade'];
    $data_contratacao = $_POST['data_contratacao'];
    $salario = $_POST['salario'];

    // Inserir dados no banco
    $sql = "INSERT INTO professores (nome, cpf, endereco, telefone, email, especialidade, data_contratacao, salario) 
            VALUES (:nome, :cpf, :endereco, :telefone, :email, :especialidade, :data_contratacao, :salario)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':especialidade', $especialidade);
    $stmt->bindParam(':data_contratacao', $data_contratacao);
    $stmt->bindParam(':salario', $salario);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Professor cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao cadastrar professor.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Professores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Cadastro de Professores</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" class="form-control" name="cpf" required>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" name="endereco" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" name="telefone">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="especialidade" class="form-label">Especialidade:</label>
                <input type="text" class="form-control" name="especialidade">
            </div>
            <div class="mb-3">
                <label for="data_contratacao" class="form-label">Data de Contratação:</label>
                <input type="date" class="form-control" name="data_contratacao" required>
            </div>
            <div class="mb-3">
                <label for="salario" class="form-label">Salário:</label>
                <input type="text" class="form-control" name="salario" required pattern="\d+(\.\d{2})?" title="Insira um valor numérico válido">
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
        <a href="teachers.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</body>
</html>

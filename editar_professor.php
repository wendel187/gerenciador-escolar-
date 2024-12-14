<?php
include 'conexaoBD.php';

$id = $_GET['id'];

// Busca o professor pelo ID
$sql = "SELECT * FROM professores WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$professor = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $especialidade = $_POST['especialidade'];
    $data_contratacao = $_POST['data_contratacao'];
    $salario = $_POST['salario']; // Novo campo de salário

    // Atualiza os dados do professor
    $sql = "UPDATE professores SET 
                nome = :nome, 
                cpf = :cpf, 
                endereco = :endereco, 
                telefone = :telefone, 
                email = :email, 
                especialidade = :especialidade, 
                data_contratacao = :data_contratacao,
                salario = :salario 
            WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':especialidade', $especialidade);
    $stmt->bindParam(':data_contratacao', $data_contratacao);
    $stmt->bindParam(':salario', $salario); // Bind do campo salário
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Tenta executar a consulta e verifica se houve sucesso
    if ($stmt->execute()) {
        header('Location: teachers.php'); // Redireciona de volta para a lista de professores
        exit();
    } else {
        echo "Erro ao atualizar professor.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Professor</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    h2 {
        color: #333;
    }
    form {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin: 10px 0 5px;
    }
    input[type="text"],
    input[type="email"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    input[type="submit"],
    button {
        background: #28a745;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    input[type="submit"]:hover,
    button:hover {
        background: #218838;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 5px;
        overflow: hidden;
    }
    th, td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }
    th {
        background: #007bff;
        color: white;
    }
    tr:hover {
        background: #f1f1f1;
    }
    a {
        color: #007bff;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>
    <h2>Editar Professor</h2>
    <form method="POST" action="editar_professor.php?id=<?php echo $id; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($professor['nome']); ?>" required>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" value="<?php echo htmlspecialchars($professor['cpf']); ?>" required>

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" value="<?php echo htmlspecialchars($professor['endereco']); ?>" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" value="<?php echo htmlspecialchars($professor['telefone']); ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($professor['email']); ?>" required>

        <label for="especialidade">Especialidade:</label>
        <input type="text" name="especialidade" value="<?php echo htmlspecialchars($professor['especialidade']); ?>">

        <label for="data_contratacao">Data de Contratação:</label>
        <input type="date" name="data_contratacao" value="<?php echo htmlspecialchars($professor['data_contratacao']); ?>" required>

        <!-- Novo campo de Salário -->
        <label for="salario">Salário:</label>
        <input type="text" name="salario" value="<?php echo htmlspecialchars($professor['salario']); ?>" required>

        <input type="submit" value="Salvar">
        <button type="button" class="button" onclick="window.location.href='teachers.php';">Voltar</button>
    </form>
</body>

</html>

<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_aluno'])) {
    header("Location: index.php?error=Acesso não autorizado");
    exit();
}

// Obtém informações do aluno
$id_aluno = $_SESSION['id_aluno'];
$sql = "SELECT * FROM usuarios_alunos WHERE id = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id_aluno);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$aluno = mysqli_fetch_assoc($result);

// Verifica se o aluno foi encontrado
if (!$aluno) {
    die("Erro: Aluno não encontrado.");
}

// Obtém as turmas do aluno através da tabela matriculas
$sql_turmas = "
    SELECT t.*, p.nome 
    FROM turmas t
    JOIN professores p ON t.professor_id = p.id
    JOIN matriculas m ON t.id = m.turma_id
    WHERE m.aluno_id = ?";
$stmt_turmas = mysqli_prepare($conexao, $sql_turmas);
mysqli_stmt_bind_param($stmt_turmas, 'i', $id_aluno);
mysqli_stmt_execute($stmt_turmas);
$result_turmas = mysqli_stmt_get_result($stmt_turmas);

// Cabeçalho da página
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Turmas - Sistema de Gerenciamento Escolar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos gerais */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
        }

        .navbar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .navbar a:hover {
            background-color: #0056b3;
        }

        .main-content {
            margin-left: 270px;
 padding: 20px;
        }

        h1 {
            color: #343a40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Menu</h2>
    <a href="minhas_turmas.php">Minhas Turmas</a>
    <a href="perfil.php">Meu Perfil</a>
    <a href="sair.php">Sair</a>
</div>

<div class="main-content">
    <h1>Minhas Turmas</h1>

    <?php if ($result_turmas->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID da Turma</th>
                <th>Nome da Turma</th>
                <th>Nome do Professor</th>
            </tr>
            <?php while ($turma = mysqli_fetch_assoc($result_turmas)): ?>
                <tr>
                    <td><?php echo $turma['id']; ?></td>
                    <td><?php echo $turma['nome_turma']; ?></td>
                    <td><?php echo $turma['nome']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Nenhuma turma encontrada para este aluno.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
// Fecha a conexão com o banco de dados
mysqli_close($conexao);
?> 
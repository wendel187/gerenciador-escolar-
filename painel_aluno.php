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

// Obtém notas do aluno
$sql_notas = "
    SELECT n.id, n.descricao, n.nota, n.data_lancamento
    FROM notas n
    INNER JOIN matriculas m ON n.matricula_id = m.id
    WHERE m.aluno_id = ?";
$stmt_notas = mysqli_prepare($conexao, $sql_notas);
mysqli_stmt_bind_param($stmt_notas, 'i', $id_aluno);
mysqli_stmt_execute($stmt_notas);
$result_notas = mysqli_stmt_get_result($stmt_notas);

// Obtém turmas do aluno
$sql_turmas = "
    SELECT t.id AS turma_id, t.nome_turma, t.turno, t.data_inicio, t.data_fim, t.horario, t.dias_aula, p.nome AS nome_professor
    FROM turmas t
    INNER JOIN matriculas m ON t.id = m.turma_id
    INNER JOIN professores p ON t.professor_id = p.id
    WHERE m.aluno_id = ?";
$stmt_turmas = mysqli_prepare($conexao, $sql_turmas);
mysqli_stmt_bind_param($stmt_turmas, 'i', $id_aluno);
mysqli_stmt_execute($stmt_turmas);
$result_turmas = mysqli_stmt_get_result($stmt_turmas);

// Cabeçalho do painel
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno - Sistema de Gerenciamento Escolar</title>
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
        .navbar a i {
            margin-right: 10px;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            text-align: center;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 20px;
            color: white;
        }
        .header .aluno-nome {
            font-size: 18px;
            font-weight: bold;
        }
        .logout-btn { 
            background-color: #dc3545;
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c82333;
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
            background-color: #007bff;
            color: white;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #222;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
    </style>
    <script>
        function confirmLogout() {
            if (confirm("Deseja realmente sair?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</head>
<body>
    <div class="navbar">
        <a href="minhas_turmas.php"><i class="fas fa-users"></i> Minhas Turmas</a>
    </div>

    <div class="main-content">
        <div class="header">
            <span class="aluno-nome">Bem-vindo, <?php echo htmlspecialchars($aluno['username']); ?></span>
            <button class="logout-btn" onclick="confirmLogout()">Sair</button>
        </div>

        <section>
            <h2>Informações do Aluno</h2>
            <p>ID: <?php echo htmlspecialchars($aluno['id']); ?></p>
            <p>Nome: <?php echo htmlspecialchars($aluno['username']); ?></p>
            <p>Turma: Não disponível</p>
        </section>

        <section>
            <h2>Notas</h2>
            <table>
                <tr>
                    <th>Descrição</th>
                    <th>Nota</th>
                    <th>Data de Lançamento</th>
                </tr>
                <?php while ($nota = mysqli_fetch_assoc($result_notas)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($nota['descricao']); ?></td>
                    <td><?php echo htmlspecialchars($nota['nota']); ?></td>
                    <td><?php echo htmlspecialchars($nota['data_lancamento']); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section>
            <h2>Minhas Turmas</h2>
            <table>
                <tr>
                    <th>ID da Turma</th>
                    <th>Nome da Turma</th>
                    <th>Nome do Professor</th>
                    <th>Data de Início</th>
                    <th>Turno</th>
                    <th>Horário</th>
                    <th>Dias de Aula</th>
                </tr>
                <?php while ($turma = mysqli_fetch_assoc($result_turmas)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($turma['turma_id']); ?></td>
                    <td><?php echo htmlspecialchars($turma['nome_turma']); ?></td>
                    <td><?php echo htmlspecialchars($turma['nome_professor']); ?></td>
                    <td><?php echo htmlspecialchars($turma['data_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($turma['turno']); ?></td>
                    <td><?php echo htmlspecialchars($turma['horario']); ?></td>
                    <td><?php echo htmlspecialchars($turma['dias_aula']); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </div>

    <footer>
        &copy; 2024 Sistema de Gerenciamento Escolar
    </footer>
</body>
</html>
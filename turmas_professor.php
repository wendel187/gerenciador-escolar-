<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: index.php?error=Acesso restrito aos professores");
    exit();
}

// Recuperar o professor_id do usuário logado
try {
    $sql = "SELECT professor_id FROM usuarios_professores WHERE id = :usuario_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && isset($usuario['professor_id'])) {
        $id_professor = $usuario['professor_id'];
    } else {
        header("Location: index.php?error=Professor não encontrado");
        exit();
    }

    // Recuperar as turmas do professor
    try {
        $sql = "SELECT t.id AS turma_id, d.nome AS disciplina, t.sala_id, t.horario, t.dias_aula
                FROM turmas t
                JOIN disciplinas d ON t.disciplina_id = d.id
                WHERE t.professor_id = :professor_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':professor_id', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        $turmas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao recuperar as turmas: " . $e->getMessage();
        exit();
    }

    // Recuperar nome do professor
    $sql = "SELECT nome FROM professores WHERE id = :id_professor";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_professor', $id_professor, PDO::PARAM_INT);
    $stmt->execute();
    $professor = $stmt->fetch(PDO::FETCH_ASSOC);

    $professor_nome = $professor ? $professor['nome'] : "Nome do professor não encontrado";
} catch (PDOException $e) {
    $professor_nome = "Erro ao recuperar o nome: " . $e->getMessage();
}

require_once('cabecalho.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
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

        .header .professor-name {
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

        .turmas-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .turmas-table th, .turmas-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .turmas-table th {
            background-color: #007bff;
            color: white;
        }

        .actions button {
            margin-right: 10px;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .actions .view-btn {
            background-color: #28a745;
        }
        
        .actions button:hover {
            opacity: 0.8;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm("Deseja realmente excluir esta turma?")) {
                window.location.href = "remover_turma.php?id=" + id;
            }
        }
    </script>
</head>
<body>
    <div class="navbar">
        <a href="painel_professor.php"><i class="fas fa-home"></i> Início</a>
        <a href="notas.php"><i class="fas fa-book"></i> Notas</a>
        <a href="usuarios.php"><i class="fas fa-user"></i> Meus Dados</a>
        <a href="configuracoes.php"><i class="fas fa-cogs"></i> Configurações</a>
    </div>

    <div class="main-content">
        <div class="header">
            <span class="professor-name">Bem-vindo, <?php echo htmlspecialchars($professor_nome); ?></span>
            <button class="logout-btn" onclick="confirmLogout()">Sair</button>
        </div>

        <h2>Minhas Turmas</h2>

        <?php if (count($turmas) > 0): ?>
            <table class="turmas-table">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Sala</th>
                        <th>Horário</th>
                        <th>Dias da Aula</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turmas as $turma): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($turma['disciplina']); ?></td>
                            <td><?php echo htmlspecialchars($turma['sala_id']); ?></td>
                            <td><?php echo htmlspecialchars($turma['horario']); ?></td>
                            <td><?php echo htmlspecialchars($turma['dias_aula']); ?></td>
                            <td class="actions">
                                <button class="view-btn" onclick="window.location.href='ver_turma.php?id=<?php echo $turma['turma_id']; ?>'">Ver Turma</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Você não tem turmas cadastradas ainda.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento Escolar. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

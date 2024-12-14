<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: index.php?error=Acesso restrito aos professores");
    exit();
}

// Recupera o ID da turma da URL
if (isset($_GET['id'])) {
    $turma_id = $_GET['id'];

    // Recupera informações da turma
    try {
        $sql = "SELECT t.id AS turma_id, d.nome AS disciplina, t.sala_id, t.horario, t.dias_aula, p.nome AS professor_nome
                FROM turmas t
                JOIN disciplinas d ON t.disciplina_id = d.id
                JOIN professores p ON t.professor_id = p.id
                WHERE t.id = :turma_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
        $stmt->execute();
        $turma = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$turma) {
            header("Location: painel_professor.php?error=Turma não encontrada");
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro ao recuperar informações da turma: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: painel_professor.php?error=Turma não especificada");
    exit();
}

require_once('cabecalho.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Turma - Sistema de Gerenciamento Escolar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
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

        .icon-container {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .icon-card {
            width: 200px;
            padding: 20px;
            background-color: #f8f9fa;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .icon-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .icon-card i {
            font-size: 40px;
            color: #007bff;
        }

        .icon-card h3 {
            margin-top: 10px;
            font-size: 18px;
            color: #333;
        }

        .icon-card p {
            color: #666;
        }
    </style>
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
            <span class="professor-name">Bem-vindo, <?php echo htmlspecialchars($turma['professor_nome']); ?></span>
            <button class="logout-btn" onclick="confirmLogout()">Sair</button>
        </div>

        <h2>Turma: <?php echo htmlspecialchars($turma['disciplina']); ?></h2>
        <p><strong>Sala:</strong> <?php echo htmlspecialchars($turma['sala_id']); ?></p>
        <p><strong>Horário:</strong> <?php echo htmlspecialchars($turma['horario']); ?></p>
        <p><strong>Dias da Aula:</strong> <?php echo htmlspecialchars($turma['dias_aula']); ?></p>

        <!-- Ícones centrais -->
        <div class="icon-container">
            <div class="icon-card" onclick="window.location.href='chamada.php?turma_id=<?php echo $turma['turma_id']; ?>'">
                <i class="fas fa-check-square"></i>
                <h3>Chamada</h3>
                <p>Registrar presença</p>
            </div>
            <div class="icon-card" onclick="window.location.href='boletim.php?turma_id=<?php echo $turma['turma_id']; ?>'">
                <i class="fas fa-file-alt"></i>
                <h3>Boletim</h3>
                <p>Visualizar notas</p>
            </div>
            <div class="icon-card" onclick="window.location.href='ver_aula.php?turma_id=<?php echo $turma['turma_id']; ?>'">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Aulas</h3>
                <p>Gerenciar aulas</p>
            </div>
        
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento Escolar. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: index.php?error=Acesso restrito aos professores");
    exit();
}

// Recupera o ID da turma da URL
if (isset($_GET['turma_id'])) {
    $turma_id = $_GET['turma_id'];

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

        // Recupera as aulas já cadastradas, incluindo o nome da aula
        $sql_aulas = "SELECT id, nome_aula, data_aula FROM aulas WHERE turma_id = :turma_id ORDER BY data_aula DESC";
        $stmt_aulas = $conn->prepare($sql_aulas);
        $stmt_aulas->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
        $stmt_aulas->execute();
        $aulas = $stmt_aulas->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao recuperar informações da turma ou aulas: " . $e->getMessage();
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
    <title>Ver Aulas - Sistema de Gerenciamento Escolar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos para a página */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Garante que o conteúdo ocupe toda a altura da página */
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
            flex-grow: 1; /* Faz o conteúdo principal crescer */
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

        .aulas-list {
            margin-top: 30px;
        }

        .aula-item {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .aula-item p {
            font-size: 16px;
            color: #333;
        }

        .btn-adicionar {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-adicionar:hover {
            background-color: #218838;
        }

        .btn-excluir {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-excluir:hover {
            background-color: #c82333;
        }

        .btn-voltar {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-voltar:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
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

        <h2>Aulas da Turma: <?php echo htmlspecialchars($turma['disciplina']); ?></h2>

        <div class="aulas-list">
            <?php if (count($aulas) > 0): ?>
                <?php foreach ($aulas as $aula): ?>
                    <div class="aula-item">
                        <p><strong>Nome da Aula:</strong> <?php echo htmlspecialchars($aula['nome_aula']); ?></p>
                        <p><strong>Data da Aula:</strong> <?php echo date("d/m/Y", strtotime($aula['data_aula'])); ?></p>
                        <form action="excluir_aula.php" method="POST" style="display:inline;">
                            <button class="btn-excluir" type="submit" name="delete_aula" value="<?php echo $aula['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta aula?')">Excluir</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Não há aulas cadastradas para esta turma.</p>
            <?php endif; ?>
        </div>

        <button class="btn-adicionar" onclick="window.location.href='adicionar_aula.php?turma_id=<?php echo $turma['turma_id']; ?>'">
            Adicionar Nova Aula
        </button>

        <!-- Botão Voltar para as Turmas -->
        <button class="btn-voltar" onclick="window.location.href='ver_turma.php'">Voltar para as Turmas</button>
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento Escolar. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

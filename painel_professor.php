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

    // Recuperar as turmas e disciplinas do professor
    try {
        $sql = "SELECT d.nome AS disciplina, t.sala_id, t.horario, t.dias_aula
                FROM turmas t
                JOIN disciplinas d ON t.disciplina_id = d.id
                WHERE t.professor_id = :professor_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':professor_id', $id_professor, PDO::PARAM_INT);
        $stmt->execute();
        $disciplinas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao recuperar as disciplinas: " . $e->getMessage();
        exit();
    }

    // Busca o nome do professor
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
    <title>Sistema de Gerenciamento Escolar - Painel do Professor</title>
    <!-- Link para os ícones do Font Awesome -->
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

        .icon-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .icon-card {
            flex: 1;
            max-width: 150px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .icon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .icon-card i {
            font-size: 40px;
            color: #2d89ef;
            margin-bottom: 10px;
        }

        .icon-card h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .icon-card p {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
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
        <a href="#"><i class="fas fa-home"></i> Início</a>
        <a href="turmas_professor.php"><i class="fas fa-chalkboard"></i> Minhas Turmas</a>
 
       
    </div>

    <div class="main-content">
        <div class="header">
            <span class="professor-name">Bem-vindo, <?php echo htmlspecialchars($professor_nome); ?></span>
            <button class="logout-btn" onclick="confirmLogout()">Sair</button>
        </div>

        <!-- Ícones centrais -->
        
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento Escolar. Todos os direitos reservados.</p>
    </footer>
</body>
</html>

<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado e se é um administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'admin') {
    // Redireciona para a página de login ou exibe uma mensagem de erro
    header("Location: index.php?error=Acesso restrito aos administradores");
    exit();
}

require_once('cabecalho.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento Escolar - Área do Administrador</title>
    
    <style>
        /* Estilo específico da área principal */
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
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s;
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

        /* Estilo para o quadrado azul */
        .card {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
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

        /* Estilos do dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #343a40;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #007bff;
        }

        .show {
            display: block;
        }
    </style>
    <script>
        // Função para alternar a visibilidade do menu dropdown
        function toggleDropdown() {
            var dropdownContent = document.getElementById("dropdown-content");
            dropdownContent.classList.toggle("show");
        }

        // Fecha o dropdown se o usuário clicar fora dele
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-button')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        // Função para confirmar o logout
        function confirmLogout() {
            if (confirm("Deseja realmente sair?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</head>
<body>
    <!-- Barra de navegação à esquerda -->
    <div class="navbar">
        <a href="#">Início</a>

        <a href="teachers.php">Professores</a>
        <a href="aluno.php">Alunos</a>

        <!-- Dropdown Menu para Turmas / Disciplinas -->
        <div class="dropdown">
            <a href="javascript:void(0);" class="dropdown-button" onclick="toggleDropdown()">Turmas / Disciplinas</a>
            <div class="dropdown-content" id="dropdown-content">
                <a href="diciplinas.php">Disciplinas</a>
                <a href="salas.php">Salas</a>
                <a href="turma.php">Turmas</a>
            </div>
        </div>

        <a href="usuarios.php">Usuários</a>
        <a href="#">Configurações</a>
    </div>

    <!-- Área principal -->
    <div class="main-content">
        <!-- Cabeçalho -->
        <div class="header">
            <h1>Sistema de Gerenciamento Escolar - Área do Administrador</h1>
            <button class="logout-btn" onclick="confirmLogout()">Sair</button>
        </div>

        <!-- Conteúdo principal -->
        <section id="summary">
            <h2>Resumo</h2>
            <div class="card">
                <h4>Total de Alunos: 
                    <?php 
                    $sql = "SELECT COUNT(*) as total FROM alunos";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $alunos = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $alunos['total']; 
                    ?>
                </h4>
            </div>
            <div class="card">
                <h4>Total de Professores: 
                    <?php 
                    $sql = "SELECT COUNT(*) as total FROM professores";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $professores = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $professores['total']; 
                    ?>
                </h4>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento Escolar. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
<?php require_once('rodape.php'); ?> 

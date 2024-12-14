<?php
// Incluir o arquivo de conexão com o banco de dados
require_once('conexaoBD.php'); 

// Se o usuário não estiver logado, redireciona para a página de login
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
try {
    if (!isset($conn)) {
        $conn = new PDO("mysql:host=localhost;dbname=gerenciamento_escolar", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aulas e Presença</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php include('mensagem.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Lista de Aulas
                            <a href="ver_turma.php" class="btn btn-danger float-end me-2">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID da Aula</th>
                                    <th>Nome da Aula</th>
                                    <th>Data da Aula</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Recupera o id do professor logado
                                $sql = "SELECT professor_id FROM usuarios_professores WHERE id = :usuario_id";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':usuario_id', $_SESSION['usuario_id'], PDO::PARAM_INT);
                                $stmt->execute();
                                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                                $id_professor = $usuario['professor_id'];

                                // Consulta para obter as aulas que o professor leciona
                                $sql = "SELECT a.id AS aula_id, a.nome_aula, a.data, a.data_aula
                                        FROM aulas a
                                        JOIN turmas t ON a.turma_id = t.id
                                        WHERE t.professor_id = :professor_id";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':professor_id', $id_professor, PDO::PARAM_INT);
                                $stmt->execute();
                                $aulas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Exibe as aulas e ações
                                foreach ($aulas as $aula) {
                                    // Verifica se data_aula está válida
                                    $data_aula = ($aula['data_aula'] == '0000-00-00' || empty($aula['data_aula'])) ? 'Data não definida' : $aula['data_aula'];
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($aula['aula_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($aula['nome_aula']) . "</td>";
                                    echo "<td>" . htmlspecialchars($data_aula) . "</td>";
                                    echo "<td>";
                                    // Botão de registrar presença
                                    echo "<a href='registrar_presenca.php?aula_id=" . $aula['aula_id'] . "' class='btn btn-primary btn-sm'><span class='bi-check-circle'></span>&nbsp;Registrar Presença</a> ";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php require_once('rodape.php'); ?>
</body>
</html>

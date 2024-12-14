<?php
session_start();
require_once('conexaoBD.php');

// Verifica se o usuário está logado e se é um professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: index.php?error=Acesso restrito aos professores");
    exit();
}

// Verifica se o ID da turma foi passado pela URL
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
    } catch (PDOException $e) {
        echo "Erro ao recuperar informações da turma: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: painel_professor.php?error=Turma não especificada");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_aula = $_POST['nome_aula'];
    $data_aula = $_POST['data_aula'];

    try {
        $sql = "INSERT INTO aulas (turma_id, nome_aula, data_aula) VALUES (:turma_id, :nome_aula, :data_aula)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':turma_id', $turma_id, PDO::PARAM_INT);
        $stmt->bindParam(':nome_aula', $nome_aula, PDO::PARAM_STR);
        $stmt->bindParam(':data_aula', $data_aula, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: ver_aula.php?turma_id=$turma_id");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao adicionar aula: " . $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar Aula - Sistema de Gerenciamento Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Adicionar Aula à Turma: <?php echo htmlspecialchars($turma['disciplina']); ?>
                <a href="ver_aula.php?turma_id=<?php echo $turma['turma_id']; ?>" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <form method="POST" action="">
                <div class="mb-3">
                  <label for="nome_aula" class="form-label">Nome da Aula</label>
                  <input type="text" name="nome_aula" class="form-control" id="nome_aula" required>
                </div>
                <div class="mb-3">
                  <label for="data_aula" class="form-label">Data da Aula</label>
                  <input type="date" name="data_aula" class="form-control" id="data_aula" required>
                </div>
                <div class="mb-3">
                  <button type="submit" class="btn btn-primary">Adicionar Aula</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIe" crossorigin="anonymous"></script>
  </body>
</html>

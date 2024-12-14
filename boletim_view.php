<?php
session_start();
require 'conexaoBD.php';

// Verificar se o ID do aluno foi passado
if (isset($_GET['id'])) {
    $aluno_id = $_GET['id'];

    // Buscar os dados do aluno
    $query_aluno = "SELECT * FROM alunos WHERE id = :aluno_id";
    $stmt = $conn->prepare($query_aluno);
    $stmt->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
    $stmt->execute();
    $aluno = $stmt->fetch();

    if ($aluno) {
        // Buscar as notas do aluno
        $query_notas = "SELECT * FROM notas WHERE matricula_id = :aluno_id";
        $stmt = $conn->prepare($query_notas);
        $stmt->bindParam(':aluno_id', $aluno_id, PDO::PARAM_INT);
        $stmt->execute();
        $notas = $stmt->fetchAll();

        // Calcular a média das notas
        $total_notas = 0;
        $quantidade_notas = count($notas);

        if ($quantidade_notas > 0) {
            foreach ($notas as $nota) {
                $total_notas += $nota['nota'];
            }
            $media = $total_notas / $quantidade_notas;
            $status = ($media >= 7) ? 'Aprovado' : 'Reprovado';
        } else {
            $media = 0;
            $status = 'Sem Notas';
        }
    } else {
        $_SESSION['mensagem'] = "Aluno não encontrado.";
        header("Location: boletim.php");
        exit;
    }
} else {
    $_SESSION['mensagem'] = "ID do aluno não foi informado.";
    header("Location: boletim.php");
    exit;
}

// Se o formulário for enviado para adicionar uma nova nota
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nota = $_POST['nota'];
    $descricao = $_POST['descricao'];
    $data_lancamento = date('Y-m-d');

    // Inserir a nova nota
    $sql = "INSERT INTO notas (matricula_id, nota, descricao, data_lancamento) 
            VALUES (:matricula_id, :nota, :descricao, :data_lancamento)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':matricula_id', $aluno_id, PDO::PARAM_INT);
    $stmt->bindParam(':nota', $nota);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':data_lancamento', $data_lancamento);

    if ($stmt->execute()) {
        header("Location: boletim_view.php?id=" . $aluno_id . "&success=Nota adicionada com sucesso");
    } else {
        echo "Erro ao adicionar nota.";
    }
}
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boletim - <?php echo isset($aluno) ? htmlspecialchars($aluno['nome']) : "Aluno Não Encontrado"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
      .imprimir-btn {
        margin-top: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>
                Boletim de <?php echo isset($aluno) ? htmlspecialchars($aluno['nome']) : "Aluno Não Encontrado"; ?>
                <a href="boletim.php" class="btn btn-danger float-end me-2">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <?php if (isset($aluno)): ?>
                <h5>Notas</h5>
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Nota</th>
                      <th>Descrição</th>
                      <th>Data de Lançamento</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($notas) > 0): ?>
                      <?php foreach ($notas as $nota): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($nota['nota']); ?></td>
                          <td><?php echo htmlspecialchars($nota['descricao']); ?></td>
                          <td><?php echo date('d/m/Y', strtotime($nota['data_lancamento'])); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr><td colspan="3">Nenhuma nota registrada para este aluno.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>

                <h5>Adicionar Nova Nota</h5>
                <form action="boletim_view.php?id=<?php echo $aluno_id; ?>" method="POST">
                  <div class="mb-3">
                    <label for="nota" class="form-label">Nota:</label>
                    <input type="number" class="form-control" id="nota" name="nota" required>
                  </div>
                  <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">Adicionar Nota</button>
                </form>

                <hr>

                <h5>Informações do Boletim</h5>
                <p><strong>Média: </strong><?php echo number_format($media, 2, ',', '.'); ?></p>
                <p><strong>Status: </strong><?php echo $status; ?></p>

                <button class="btn btn-info imprimir-btn" onclick="window.print()">Imprimir Boletim</button>
              <?php else: ?>
                <p class="text-danger">Aluno não encontrado.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php require_once('rodape.php'); ?>
  </body>
</html>

<?php
session_start();
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boletim de Alunos</title>
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
              <h4>Boletim de Alunos
                <a href="aluno_create.php" class="btn btn-primary float-end">Adicionar Aluno</a>
                <a href="ver_turma.php" class="btn btn-danger float-end me-2">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Data Nascimento</th>
                    <th>Notas</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Obtendo o ID da turma a partir da URL
                  if (isset($_GET['turma_id'])) {
                      $turma_id = intval($_GET['turma_id']);

                      // Consulta para buscar os alunos matriculados na turma
                      $sql = "SELECT a.id, a.nome, a.email, a.data_nascimento 
                              FROM alunos a
                              INNER JOIN matriculas m ON a.id = m.aluno_id
                              WHERE m.turma_id = ?";
                      
                      // Preparando e executando a consulta
                      $stmt = $conexao->prepare($sql);
                      $stmt->bind_param('i', $turma_id);
                      $stmt->execute();
                      $result = $stmt->get_result();

                      if ($result->num_rows > 0) {
                          foreach ($result as $aluno) {
                  ?>
                  <tr>
                    <td><?= $aluno['id'] ?></td>
                    <td><?= $aluno['nome'] ?></td>
                    <td><?= $aluno['email'] ?></td>
                    <td><?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?></td>
                    <td>
                      <a href="boletim_view.php?id=<?= $aluno['id'] ?>&turma_id=<?= $turma_id ?>" class="btn btn-info btn-sm">
                        <span class="bi-book"></span>&nbsp;Ver Boletim
                      </a>
                    </td>
                    <td>
                      <a href="aluno_edit.php?id=<?= $aluno['id'] ?>" class="btn btn-success btn-sm">
                        <span class="bi-pencil-fill"></span>&nbsp;Editar
                      </a>
                      <form action="aluno_createAction.php" method="POST" class="d-inline">
                        <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_aluno" value="<?= $aluno['id'] ?>" class="btn btn-danger btn-sm">
                          <span class="bi-trash3-fill"></span>&nbsp;Excluir
                        </button>
                      </form>
                    </td>
                  </tr>
                  <?php
                          }
                      } else {
                          echo '<tr><td colspan="6" class="text-center">Nenhum aluno encontrado nesta turma</td></tr>';
                      }
                  } else {
                      echo '<tr><td colspan="6" class="text-center">ID da turma não fornecido</td></tr>';
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

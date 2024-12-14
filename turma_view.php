<?php
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Turma - Visualizar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Visualizar Turma
                <a href="turma.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <?php
              if (isset($_GET['id'])) {
                  $turma_id = mysqli_real_escape_string($conexao, $_GET['id']);
                  
                  $sql = "
                      SELECT turmas.id, disciplinas.nome AS disciplina_nome, salas.numero AS sala_numero, 
                             professores.nome AS professor_nome, turmas.horario, turmas.dias_aula, 
                             turmas.data_inicio, turmas.data_fim
                      FROM turmas
                      JOIN disciplinas ON turmas.disciplina_id = disciplinas.id
                      JOIN salas ON turmas.sala_id = salas.id
                      JOIN professores ON turmas.professor_id = professores.id
                      WHERE turmas.id = '$turma_id'
                  ";
                  
                  $query = mysqli_query($conexao, $sql);
                  
                  if (mysqli_num_rows($query) > 0) {
                      $turma = mysqli_fetch_array($query);
              ?>
                  <div class="mb-3">
                    <label>Disciplina</label>
                    <p class="form-control"><?=$turma['disciplina_nome'];?></p>
                  </div>
                  <div class="mb-3">
                    <label>Sala</label>
                    <p class="form-control"><?=$turma['sala_numero'];?></p>
                  </div>
                  <div class="mb-3">
                    <label>Professor Responsável</label>
                    <p class="form-control"><?=$turma['professor_nome'];?></p>
                  </div>
                  <div class="mb-3">
                    <label>Horário</label>
                    <p class="form-control"><?=$turma['horario'];?></p>
                  </div>
                  <div class="mb-3">
                    <label>Dias de Aula</label>
                    <p class="form-control"><?=$turma['dias_aula'];?></p>
                  </div>
                  <div class="mb-3">
                    <label>Data de Início</label>
                    <p class="form-control"><?=date('d/m/Y', strtotime($turma['data_inicio']));?></p>
                  </div>
                  <div class="mb-3">
                    <label>Data de Fim</label>
                    <p class="form-control"><?=date('d/m/Y', strtotime($turma['data_fim']));?></p>
                  </div>
              <?php
                  } else {
                      echo "<h5>Turma não encontrada</h5>";
                  }
              } else {
                  echo "<h5>ID da turma não fornecido</h5>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

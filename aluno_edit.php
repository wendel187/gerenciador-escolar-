<?php
session_start();
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aluno - Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
 
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Editar aluno
                <a href="aluno.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <?php
              if (isset($_GET['id'])) {
                $aluno_id = mysqli_real_escape_string($conexao, $_GET['id']);
                $sql = "SELECT * FROM alunos WHERE id='$aluno_id'";
                $query = mysqli_query($conexao, $sql);
                if (mysqli_num_rows($query) > 0) {
                  $aluno = mysqli_fetch_array($query);
              ?>
              <form action="aluno_createAction.php" method="POST">
                <input type="hidden" name="aluno_id" value="<?=$aluno['id']?>">
                <div class="mb-3">
                  <label>Nome</label>
                  <input type="text" name="nome" value="<?=$aluno['nome']?>" class="form-control">
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <input type="text" name="email" value="<?=$aluno['email']?>" class="form-control">
                </div>
                <div class="mb-3">
                  <label>Data de Nascimento</label>
                  <input type="date" name="data_nascimento" value="<?=$aluno['data_nascimento']?>" class="form-control">
                </div>
                <div class="mb-3">
                  <label>CPF</label>
                  <input type="text" name="cpf" value="<?=$aluno['cpf']?>" class="form-control">
                </div>
                <div class="mb-3">
                  <label>Endereço</label>
                  <input type="text" name="endereco" value="<?=$aluno['endereco']?>" class="form-control">
                </div>
                <div class="mb-3">
                  <label>Telefone</label>
                  <input type="text" name="telefone" value="<?=$aluno['telefone']?>" class="form-control">
                </div>
                <div class="mb-3">
                  <button type="submit" name="update_aluno" class="btn btn-primary">Salvar</button>
                </div>
              </form>
              <?php
              } else {
                echo "<h5>Aluno não encontrado</h5>";
              }
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

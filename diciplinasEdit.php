<?php
session_start();
require 'conexaoBD.php';

// Verifica se o ID da disciplina foi passado na URL
if (isset($_GET['id'])) {
    $disciplina_id = mysqli_real_escape_string($conexao, $_GET['id']);
    $sql = "SELECT * FROM disciplinas WHERE id = '$disciplina_id'";
    $result = mysqli_query($conexao, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $disciplina = mysqli_fetch_assoc($result);
    } else {
        header('Location: diciplinas.php');
        exit;
    }
} else {
    header('Location: diciplinas.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disciplina - Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Editar Disciplina
                <a href="diciplinas.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <form action="diciplinasAction.php" method="POST">
                <input type="hidden" name="disciplina_id" value="<?=$disciplina['id']?>">
                <div class="mb-3">
                  <label>Nome</label>
                  <input type="text" name="nome" class="form-control" value="<?=$disciplina['nome']?>" required>
                </div>
                <div class="mb-3">
                  <label>Descrição</label>
                  <textarea name="descricao" class="form-control" rows="3" required><?=$disciplina['descricao']?></textarea>
                </div>
                <div class="mb-3">
                  <button type="submit" name="update_disciplina" class="btn btn-primary">Salvar</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

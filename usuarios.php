<?php
session_start();
require 'conexaoBD.php';
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários</title>
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
              <h4> Lista de Usuários
                <a href="usuario_create.php" class="btn btn-primary float-end">Adicionar Usuário</a>
                <a href="principal.php" class="btn btn-danger float-end me-2">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome de Usuário</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Consulta unificada para buscar os usuários com o tipo correto
                  $sql = 'SELECT id, username, tipo FROM usuarios 
                          UNION 
                          SELECT id, username, "aluno" AS tipo FROM usuarios_alunos 
                          UNION 
                          SELECT id, username, "professor" AS tipo FROM usuarios_professores';
                  $usuarios = mysqli_query($conexao, $sql);
                  if (mysqli_num_rows($usuarios) > 0) {
                    foreach($usuarios as $usuario) {
                  ?>
                  <tr>
                    <td><?=$usuario['id']?></td>
                    <td><?=$usuario['username']?></td>
                    <td><?=ucfirst($usuario['tipo'])?></td>
                    <td>
                      <a href="usuario_view.php?id=<?=$usuario['id']?>" class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></span>&nbsp;Visualizar</a>
                      <a href="usuario_edit.php?id=<?=$usuario['id']?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>&nbsp;Editar</a>
                      <!-- Formulário de Exclusão -->
                      <form action="usuario_delete.php" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="<?=$usuario['id']?>">
                        <button onclick="return confirm('Tem certeza que deseja excluir este usuário?')" type="submit" class="btn btn-danger btn-sm">
                            <span class="bi-trash3-fill"></span>&nbsp;Excluir
                        </button>
                      </form>
                    </td>
                  </tr>
                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="4"><h5>Nenhum usuário encontrado</h5></td></tr>';
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

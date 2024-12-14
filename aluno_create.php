<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuário - Criar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4>Adicionar Aluno
                <a href="aluno.php" class="btn btn-danger float-end">Voltar</a>
              </h4>
            </div>
            <div class="card-body">
              <form action="aluno_createAction.php" method="POST">
                <div class="mb-3">
                  <label>Nome</label>
                  <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Data de Nascimento</label>
                  <input type="date" name="data_nascimento" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Endereço</label>
                  <input type="text" name="endereco" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>CPF</label>
                  <input type="text" name="cpf" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Telefone</label>
                  <input type="text" name="telefone" class="form-control" required>
                </div>
                <div class="mb-3">
                  <button type="submit" name="create_aluno" class="btn btn-primary">Salvar</button>
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

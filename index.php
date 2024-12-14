<?php require_once('cabecalho.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .login-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-section {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-section input,
        .form-section select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="loginAction.php" method="POST">
            <div class="form-section">
                <label for="txtnome">Usuário</label>
                <input type="text" id="txtnome" name="txtnome" placeholder="Digite o Nome" required>
            </div>
            <div class="form-section">
                <label for="txtsenha">Senha</label>
                <input type="password" id="txtsenha" name="txtsenha" placeholder="Digite a Senha" required>
            </div>
            <div class="form-section">
                <label for="tipo_usuario">Tipo de Usuário</label>
                <select id="tipo_usuario" name="tipo_usuario" required>
                    <option value="admin">Admin</option>
                    <option value="professor">Professor</option>
                    <option value="aluno">Aluno</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Entrar</button>
        </form>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php require_once('rodape.php'); ?>


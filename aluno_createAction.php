<?php
session_start();
require 'conexaoBD.php';

// Criação de um novo aluno
if (isset($_POST['create_aluno'])) {
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $cpf = mysqli_real_escape_string($conexao, trim($_POST['cpf']));
    $endereco = mysqli_real_escape_string($conexao, trim($_POST['endereco']));
    $telefone = mysqli_real_escape_string($conexao, trim($_POST['telefone']));

    // SQL de inserção no banco
    $sql = "INSERT INTO alunos (nome, email, data_nascimento, cpf, endereco, telefone) 
            VALUES ('$nome', '$email', '$data_nascimento', '$cpf', '$endereco', '$telefone')";

    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Aluno criado com sucesso';
        header('Location: aluno.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Aluno não foi criado';
        header('Location: aluno.php');
        exit;
    }
}

// Atualização de um aluno existente
if (isset($_POST['update_aluno'])) {
    $aluno_id = mysqli_real_escape_string($conexao, $_POST['aluno_id']);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $data_nascimento = mysqli_real_escape_string($conexao, trim($_POST['data_nascimento']));
    $cpf = mysqli_real_escape_string($conexao, trim($_POST['cpf']));
    $endereco = mysqli_real_escape_string($conexao, trim($_POST['endereco']));
    $telefone = mysqli_real_escape_string($conexao, trim($_POST['telefone']));

    // SQL de atualização
    $sql = "UPDATE alunos SET nome = '$nome', email = '$email', data_nascimento = '$data_nascimento', 
            cpf = '$cpf', endereco = '$endereco', telefone = '$telefone' 
            WHERE id = '$aluno_id'";

    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Aluno atualizado com sucesso';
        header('Location: aluno.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Aluno não foi atualizado';
        header('Location: aluno.php');
        exit;
    }
}

// Exclusão de um aluno
if (isset($_POST['delete_aluno'])) {
    $aluno_id = mysqli_real_escape_string($conexao, $_POST['delete_aluno']);
    
    // SQL de exclusão
    $sql = "DELETE FROM alunos WHERE id = '$aluno_id'";
    mysqli_query($conexao, $sql);

    if (mysqli_affected_rows($conexao) > 0) {
        $_SESSION['mensagem'] = 'Aluno deletado com sucesso';
        header('Location: aluno.php');
        exit;
    } else {
        $_SESSION['mensagem'] = 'Aluno não foi deletado';
        header('Location: aluno.php');
        exit;
    }
}
?>

<?php
$host = "localhost";
$db = "form";
$user = "root";
$senha_user = "";

$con = mysqli_connect($host, $user, $senha_user, $db);

if (!$con) {
    die("Conexão falhou: " . mysqli_connect_error());
}

if (isset($_POST["cadastrar"])) {
    $nome = mysqli_real_escape_string($con, $_POST["name"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $senha = mysqli_real_escape_string($con, $_POST["senha"]);
    $rsenha = mysqli_real_escape_string($con, $_POST["rsenha"]);

    // Validação básica
    if ($senha != $rsenha) {
        die("As senhas não coincidem");
    }

    // Adicionando hash à senha
    $hashedSenha = password_hash($senha, PASSWORD_DEFAULT);

    // Validando formato de e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Formato de e-mail inválido");
    }

    // Usando instruções preparadas para evitar SQL injection
    $stmt = mysqli_prepare($con, "INSERT INTO Users (Nome, Email, Senha) VALUES (?, ?, ?)");
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $hashedSenha);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Cliente cadastrado com sucesso";
        } else {
            echo "Erro ao cadastrar o cliente";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Erro na preparação da declaração";
    }
}

mysqli_close($con);
?>

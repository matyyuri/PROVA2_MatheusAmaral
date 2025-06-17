<?php
session_start();
require_once 'conexao.php';

if ($_SERVER ["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt ->bindParam(':email', $email);
    $stmt -> execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario && password_verify($senha,$usuario['senha'])){
    // LOGIN BEM SUCEDIDO DEFINE VARIAVEIS DE SESSAO
    $_SESSION['usuario'] = $usuario['nome'];
    $_SESSION['perfil'] = $usuario['id_perfil'];
    $_SESSION['id_usuario'] = $usuario['id_usuario'];

    //VERIFICA SE A SENHA É TEMPORÁRIA
    if ($usuario['senha_temporaria']){
        //REDIRECIONA PARA A TROCA DE SENHA
        header("Location: alterar_senha.php");
        exit();
    }else{
        //REDIRECIONA PARA A PÁGINA PRINCIPAL
        header("Location: principal.php");
        exit();
    }
    }else{
        //LOGIN INVÁLIDO
        echo"<script>alert('E-mail ou senha incorretos');window.location.href='login.php';</script>";

    }
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
    body {
        background-color: #121212;
        color: #f0f0f0;
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    h2 {
        color: #ffffff;
        text-align: center;
    }

    form {
        background-color: #1e1e1e;
        padding: 20px;
        border-radius: 8px;
        max-width: 400px;
        margin: 40px auto;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #cccccc;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        background-color: #2c2c2c;
        border: 1px solid #444;
        border-radius: 4px;
        color: #f0f0f0;
    }

    input:focus {
        outline: none;
        border-color: #888;
    }

    button {
        margin-top: 15px;
        padding: 10px 15px;
        background-color: #444;
        border: none;
        color: white;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        width: 100%;
    }

    button:hover {
        background-color: #666;
    }

    p {
        text-align: center;
        margin-top: 20px;
    }

    a {
        color: #aaa;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>

    <p><a href="recuperar_senha.php">Esqueci minha Senha</a></p>
</body>
</html>
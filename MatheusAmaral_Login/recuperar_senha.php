<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php'; //ARQUIVO COM AS FUNÇÕES QUE GERAM SENHA E SIMULAM O ENVIO

if($_SERVER["REQUEST_METHOD"]== "POST"){
    $email=$_POST ["email"];

    //VERIFICA SE O EMAIL EXISTE NO BANCO
    $sql="SELECT * FROM usuario WHERE email =:email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if($usuario){
        //GERA UMA SENHA TEMPORÁRIA ALEATÓRIA
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash ($senha_temporaria, PASSWORD_DEFAULT);
        
        $sql="UPDATE usuario SET  senha =:senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        //simula o envio do email (grava em TXT)
        simularEnvioEmail($email, $senha_temporaria);

        echo "<script>alert('Uma senha temporária foi gerada e enviada(simulação).Verifique o arquivo emails_simulados.txt');window.location.href='login.php';</script>";
    }else{
        echo "<script>alert('E-mail não encontrado!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recuperar Senha</title>
        <link rel="stylesheet" href="styles.css"> 
    </head>
    <body>
        <h2>Recuperar Senha</h2>
        <form action="recuperar_senha.php" method="POST">
            <label for="email">Digite o seu e-mail Cadastrado</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Enviar senha Temporária</button>
        </form>
    </body>
</html>
<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão (perfil 1 = administrador)
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "Acesso negado!";
    exit; // para o script
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário
    $nome_fornecedor = $_POST['nome_fornecedor'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone_fornecedor'];
    $email = $_POST['email_fornecedor'];
    $contato = $_POST['contato_fornecedor'];

    // Monta o SQL - note os nomes dos campos devem bater com o banco
    $sql = "INSERT INTO fornecedor (nome_fornecedor, endereco, telefone, email, contato) 
            VALUES (:nome_fornecedor, :endereco, :telefone, :email, :contato)";
    
    $stmt = $pdo->prepare($sql);

    // Liga os parâmetros
    $stmt->bindParam(':nome_fornecedor', $nome_fornecedor);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':contato', $contato);

    // Executa e verifica sucesso
    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar fornecedor!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastrar Fornecedor</title>
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
        max-width: 500px;
        margin: 20px auto;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        color: #cccccc;
    }

    input[type="text"],
    input[type="tel"],
    input[type="email"] {
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
        margin-right: 10px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #666;
    }

    a {
        color: #aaa;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 30px;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="cadastro_fornecedor.php"]');
    const telefoneInput = document.getElementById('telefone_fornecedor');

    function mascaraTelefone(input) {
        let v = input.value.replace(/\D/g, '');
        if (v.length > 11) v = v.slice(0, 11);
        v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
        v = v.replace(/(\d)(\d{4})$/, '$1-$2');
        input.value = v;
    }

    function validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    form.addEventListener('submit', function(event) {
        const nome = document.getElementById('nome_fornecedor').value.trim();
        const endereco = document.getElementById('endereco').value.trim();
        const telefone = telefoneInput.value.trim();
        const email = document.getElementById('email_fornecedor').value.trim();
        const contato = document.getElementById('contato_fornecedor').value.trim();

        if (nome === '') {
            alert('Por favor, preencha o nome do fornecedor.');
            event.preventDefault();
            return;
        }
        if (endereco === '') {
            alert('Por favor, preencha o endereço.');
            event.preventDefault();
            return;
        }
        if (telefone === '') {
            alert('Por favor, preencha o telefone.');
            event.preventDefault();
            return;
        }
        let telefoneNumeros = telefone.replace(/\D/g, '');
        if (telefoneNumeros.length < 10) {
            alert('Telefone inválido. Digite o DDD e o número corretamente.');
            event.preventDefault();
            return;
        }
        if (email === '') {
            alert('Por favor, preencha o e-mail.');
            event.preventDefault();
            return;
        }
        if (!validarEmail(email)) {
            alert('Por favor, digite um e-mail válido.');
            event.preventDefault();
            return;
        }
        if (contato === '') {
            alert('Por favor, preencha o contato.');
            event.preventDefault();
            return;
        }
    });

    telefoneInput.addEventListener('input', function() {
        mascaraTelefone(this);
    });
});
</script>
</head>
<body>
<h1>Aluno: Matheus Yuri do Amaral</h1>
    <h2>Cadastrar Fornecedor</h2>
    <form action="cadastro_fornecedor.php" method="POST">
        <label for="nome_fornecedor">Nome:</label>
        <input type="text" id="nome_fornecedor" name="nome_fornecedor" required />

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required />

        <label for="telefone_fornecedor">Telefone:</label>
        <input type="tel" id="telefone_fornecedor" name="telefone_fornecedor" required />

        <label for="email_fornecedor">E-mail:</label>
        <input type="email" id="email_fornecedor" name="email_fornecedor" required />

        <label for="contato_fornecedor">Contato:</label>
        <input type="text" id="contato_fornecedor" name="contato_fornecedor" required />

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>
    <a href="principal.php">Voltar</a>
</body>
</html>

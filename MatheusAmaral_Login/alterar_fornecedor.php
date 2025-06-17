<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

$fornecedor = null;

// Se o formulário for enviado, busca o fornecedor pelo ID ou nome
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['busca_fornecedor'])) {
    $busca = trim($_POST['busca_fornecedor']);

    if ($busca !== '') {
        try {
            if (is_numeric($busca)) {
                $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
            } else {
                $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca";
                $stmt = $pdo->prepare($sql);
                $buscaLike = '%' . $busca . '%';
                $stmt->bindParam(':busca', $buscaLike, PDO::PARAM_STR);
            }

            $stmt->execute();
            $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fornecedor) {
                echo "<script>alert('Fornecedor não encontrado!');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Erro ao buscar fornecedor: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Fornecedor</title>
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
    input[type="email"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        background-color: #2c2c2c;
        border: 1px solid #444;
        border-radius: 4px;
        color: #f0f0f0;
    }

    input[type="text"]:focus,
    input[type="email"]:focus {
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
        margin-top: 20px;
    }

    a:hover {
        text-decoration: underline;
    }

    #sugestoes {
        background-color: #2c2c2c;
        border: 1px solid #555;
        border-radius: 4px;
        margin-top: 5px;
        padding: 5px;
        color: #ccc;
    }
    </style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="processa_alteracao_fornecedor.php"]');

    function mascaraTelefone(i) {
        let v = i.value.replace(/\D/g,'');
        if (v.length > 11) v = v.slice(0, 11);
        v = v.replace(/^(\d{2})(\d)/g,"($1) $2");
        v = v.replace(/(\d)(\d{4})$/,"$1-$2");
        i.value = v;
    }

    function validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validarFormularioAlteracao(event) {
        const nome = document.getElementById('nome_fornecedor').value.trim();
        const endereco = document.getElementById('endereco').value.trim();
        const telefone = document.getElementById('telefone').value.trim();
        const email = document.getElementById('email').value.trim();
        const contato = document.getElementById('contato').value.trim();

        if(nome === '') {
            alert('Por favor, preencha o nome do fornecedor.');
            event.preventDefault();
            return false;
        }
        if(endereco === '') {
            alert('Por favor, preencha o endereço.');
            event.preventDefault();
            return false;
        }
        if(telefone === '') {
            alert('Por favor, preencha o telefone.');
            event.preventDefault();
            return false;
        }
        const telNumeros = telefone.replace(/\D/g,'');
        if(telNumeros.length < 10) {
            alert('Telefone inválido. Digite o DDD e número corretamente.');
            event.preventDefault();
            return false;
        }
        if(email === '') {
            alert('Por favor, preencha o email.');
            event.preventDefault();
            return false;
        }
        if(!validarEmail(email)) {
            alert('Por favor, digite um email válido.');
            event.preventDefault();
            return false;
        }
        if(contato === '') {
            alert('Por favor, preencha o contato.');
            event.preventDefault();
            return false;
        }

        // Se passou tudo, permite o envio
        return true;
    }

    if (form) {
        form.addEventListener('submit', validarFormularioAlteracao);
    }

    // Aplica máscara ao telefone conforme o usuário digita
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function() {
            mascaraTelefone(this);
        });
    }
});
</script>

    <script src="scripts.js"></script>
</head>
<body>
<h1>Aluno: Matheus Yuri do Amaral</h1>
    <h2>Alterar Fornecedor</h2>

    <!-- Formulário de busca -->
    <form action="alterar_fornecedor.php" method="POST">
        <label for="busca_fornecedor">Digite o ID ou Nome do Fornecedor:</label>
        <input type="text" id="busca_fornecedor" name="busca_fornecedor" required onkeyup="buscarSugestoes()">
        <button type="submit">Buscar</button>
    </form>

    <?php if ($fornecedor): ?>
        <!-- Formulário de edição -->
        <form action="processa_alteracao_fornecedor.php" method="POST">
            <input type="hidden" name="id_fornecedor" value="<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>">

            <label for="nome_fornecedor">Nome:</label>
            <input type="text" id="nome_fornecedor" name="nome_fornecedor" value="<?= htmlspecialchars($fornecedor['nome_fornecedor']) ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($fornecedor['endereco']) ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($fornecedor['telefone']) ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($fornecedor['email']) ?>" required>

            <label for="contato">Contato:</label>
            <input type="text" id="contato" name="contato" value="<?= htmlspecialchars($fornecedor['contato']) ?>" required>

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>

    <br>
    <a href="principal.php">Voltar</a>
</body>
</html>

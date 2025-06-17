<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Excluir fornecedor se o ID for passado via GET e válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fornecedor = $_GET['id'];

    // Exclui o fornecedor do banco de dados
    $sql = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor excluído com sucesso!'); window.location.href='excluir_fornecedor.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao excluir fornecedor!');</script>";
    }
}

// Busca todos os fornecedores cadastrados em ordem alfabética
$sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Fornecedor</title>
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

    table {
        width: 95%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #1e1e1e;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid #333;
        color: #ccc;
    }

    th {
        background-color: #2a2a2a;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #2c2c2c;
    }

    tr:hover {
        background-color: #3a3a3a;
    }

    a {
        color: #aaa;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    a[href="principal.php"] {
        display: block;
        text-align: center;
        margin-top: 30px;
        color: #aaa;
    }

    p {
        text-align: center;
        margin-top: 20px;
        color: #bbb;
    }
</style>
</head>
<body>
<h1>Aluno: Matheus Yuri do Amaral</h1>
    <h2>Excluir Fornecedor</h2>

    <?php if (!empty($fornecedores)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Contato</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($fornecedores as $fornecedor): ?>
                <tr>
                    <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['endereco']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['contato']) ?></td>
                    <td>
                        <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Nenhum fornecedor encontrado.</p>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>
</html>

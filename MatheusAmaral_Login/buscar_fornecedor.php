<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão
// Supondo que os perfis 1 (Administrador) e 2 (Secretaria) tenham acesso
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

$fornecedores = []; // Inicializa variável para evitar erros

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    if (is_numeric($busca)) {
        // Busca por ID do fornecedor
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca ORDER BY nome_fornecedor ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        // Busca por nome do fornecedor usando LIKE
        $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome ORDER BY nome_fornecedor ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', '%' . $busca . '%', PDO::PARAM_STR);
    }
} else {
    // Se não houver busca, lista todos os fornecedores ordenados por nome
    $sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Buscar Fornecedor</title>
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

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        background-color: #2c2c2c;
        border: 1px solid #444;
        border-radius: 4px;
        color: #f0f0f0;
    }

    input[type="text"]:focus {
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
    }

    button:hover {
        background-color: #666;
    }

    a {
        color: #aaa;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
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

    p {
        text-align: center;
        margin-top: 20px;
        color: #bbb;
    }

    a[href="principal.php"] {
        display: block;
        text-align: center;
        margin-top: 30px;
        color: #aaa;
    }
</style>
</head>
<body>
<h1>Aluno: Matheus Yuri do Amaral</h1>
    <h2>Lista de Fornecedores</h2>

    <!-- Formulário para buscar fornecedores -->
    <form action="buscar_fornecedor.php" method="POST">
        <label for="busca">Digite o ID ou NOME (opcional):</label>
        <input type="text" id="busca" name="busca" />
        <button type="submit">Pesquisar</button>
    </form>

    <?php if (!empty($fornecedores)): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Contato</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($fornecedores as $fornecedor): ?>
                <tr>
                    <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['nome_fornecedor']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['endereco']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['contato']) ?></td>
                    <td>
                        <a href="alterar_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>">Alterar</a> |
                        <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum fornecedor encontrado.</p>
    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>
</html>

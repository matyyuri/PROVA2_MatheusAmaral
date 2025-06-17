<?php
session_start();
require 'conexao.php';

// Verifica se o usuário tem permissão de ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='principal.php';</script>";
    exit();
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e valida os dados do formulário
    $id = isset($_POST['id_fornecedor']) ? (int) $_POST['id_fornecedor'] : 0;
    $nome = isset($_POST['nome_fornecedor']) ? trim($_POST['nome_fornecedor']) : '';
    $endereco = isset($_POST['endereco']) ? trim($_POST['endereco']) : '';
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $contato = isset($_POST['contato']) ? trim($_POST['contato']) : '';

    if ($id <= 0 || empty($nome) || empty($email)) {
        echo "<script>alert('Dados inválidos.'); window.history.back();</script>";
        exit();
    }

    try {
        // Atualiza os dados do fornecedor
        $sql = "UPDATE fornecedor SET 
                    nome_fornecedor = :nome,
                    endereco = :endereco,
                    telefone = :telefone,
                    email = :email,
                    contato = :contato
                WHERE id_fornecedor = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contato', $contato);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Fornecedor atualizado com sucesso!'); window.location.href='alterar_fornecedor.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar fornecedor!'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Requisição inválida!'); window.location.href='alterar_fornecedor.php';</script>";
}
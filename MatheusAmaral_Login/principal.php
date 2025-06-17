<?php
session_start();
require_once 'conexao.php';

if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}
    //OBTENDO O NOME DO PERFIL DO USUARIO LOGADO
    $id_perfil = $_SESSION['perfil'];
    $sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
    $stmtPerfil = $pdo->prepare($sqlPerfil);
    $stmtPerfil ->bindParam(':id_perfil', $id_perfil);
    $stmtPerfil -> execute();
    $perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
    $nome_perfil = $perfil['nome_perfil'];

    //DEFINIÇÃO DAS PERMISSÕES POR PERFIL
    $permissoes = [
    1 => ["Cadastrar" => ["cadastro_usuario.php","cadastro_perfil.php","cadastro_cliente.php","cadastro_fornecedor.php","cadastro_produto.php","cadastro_funcionario.php"],
    "Buscar" => ["buscar_usuario.php","buscar_perfil.php","buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php","buscar_funcionario.php"],
    "Alterar" => ["alterar_usuario.php","alterar_perfil.php","alterar_cliente.php","alterar_fornecedor.php","alterar_produto.php","alterar_funcionario.php"],
    "Excluir" => ["excluir_usuario.php","excluir_perfil.php","excluir_cliente.php","excluir_fornecedor.php","excluir_produto.php","excluir_funcionario.php"]],

    2 => ["Cadastrar" => ["cadastro_cliente.php"],
    "Buscar" => ["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
    "Alterar" => ["alterar_cliente.php","alterar_fornecedor.php"]],

    3 => ["Cadastrar" => ["cadastro_fornecedor.php","cadastro_produto.php"],
    "Buscar" => ["buscar_cliente.php","buscar_fornecedor.php","buscar_produto.php"],
    "Alterar" => ["alterar_fornecedor.php","alterar_produto.php"],
    "Excluir" => ["excluir_produto.php"]],

    4 => ["Cadastrar" => ["cadastro_cliente.php"],
    "Buscar" => ["buscar_produto.php"],
    "Alterar" => ["alterar_fornecedor.php","alterar_produto.php"],
    "Excluir" => ["alterar_cliente.php"]],
    ];

    //OBTENDO AS OPÇÕES DISPONIVEIS PARA O PERFIL LOGADO
    $opcoes_menu = $permissoes[$id_perfil];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal</title>
    <style>
    body {
        background-color: #121212;
        color: #f0f0f0;
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #1e1e1e;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        margin-bottom: 30px;
    }

    .saudacao h2 {
        margin: 0;
        color: #ffffff;
    }

    .logout button {
        background-color: #444;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
        font-size: 1rem;
    }

    .logout button:hover {
        background-color: #666;
    }

    nav {
        background-color: #1e1e1e;
        border-radius: 8px;
        max-width: 600px;
        margin: 0 auto;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    .menu {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        gap: 40px;
    }

    .menu > li {
        position: relative;
    }

    .menu > li > a {
        color: #cccccc;
        text-decoration: none;
        padding: 15px 10px;
        display: inline-block;
        font-weight: bold;
        cursor: pointer;
    }

    .menu > li:hover > a {
        color: #ffffff;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: #2c2c2c;
        border-radius: 6px;
        top: 100%;
        left: 0;
        min-width: 180px;
        box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        z-index: 10;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu li {
        border-bottom: 1px solid #444;
    }

    .dropdown-menu li:last-child {
        border-bottom: none;
    }

    .dropdown-menu a {
        color: #cccccc;
        padding: 10px 15px;
        display: block;
        text-decoration: none;
        font-weight: normal;
        transition: background-color 0.3s;
    }

    .dropdown-menu a:hover {
        background-color: #444;
        color: #ffffff;
    }
</style>
    <script src="scripts.js"></script>
</head>
<body>
    <header>
        <div class="saudacao">
            <h2>Bem Vindo, <?php echo $_SESSION["usuario"];?>! Perfil: <?php echo$nome_perfil; ?></h2>
        </div>

        <div class="logout">
            <form action="logout.php" method="POST">
                <button type="submit">Logout</button>

        </div>
    </header>

<nav>
    <ul class="menu">
        <?php foreach($opcoes_menu as $categoria =>$arquivos): ?>
            <li class="dropdown">
                <a href="#"><?= $categoria ?></a>
                <ul class="dropdown-menu">
                    <?php foreach($arquivos as $arquivo): ?>
                        <li>
                            <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_"," ",basename($arquivo,".php")))?></a>
                        </li>
                        <?php endforeach;?>
                </ul>
            </li>
        <?php endforeach;?>
    </ul>




</nav>





    
</body>
</html>
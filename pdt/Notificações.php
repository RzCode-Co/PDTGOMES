<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Notificações</title>
</head>
<style>
    body {
        background-color: gray;
        color: yellow;
    }
    
    #conteudo {
        margin: 20px;
    }
    
    #cabecalho {
        background-color: black;
        color: white;
        padding: 10px;
        display: flex;
        justify-content: space-between;
    }
    
    #usuario-info {
        display: flex;
        align-items: center;
    }
    
    #icone-notificacoes {
        /* Adicione estilos para o ícone de notificações, como tamanho, margem, etc. */
    }
    
    #menu-lateral {
        background-color: black;
    }
    
    #menu-lateral ul {
        list-style-type: none;
        padding: 0;
    }
    
    #menu-lateral ul li {
        margin: 0;
    }
    
    #menu-lateral ul li a {
        display: block;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
    }
    
    #menu-lateral ul li a:hover {
        background-color: gray;
    }

    .paginacao {
        margin-top: 20px;
        text-align: center;
    }
    
    .paginacao a {
        padding: 5px 10px;
        background-color: black;
        color: white;
        text-decoration: none;
        margin: 5px;
    }
    
    .paginacao a:hover {
        background-color: gray;
    }
</style>
<body>
    <div id="cabecalho">
        <div id="usuario-info">
            <img src="<?php echo $fotoUsuario; ?>" alt="Foto do Usuário">
            <p><?php echo $nomeUsuario; ?></p>
            <p><?php echo $cargoUsuario; ?></p>
        </div>
        <div id="icone-notificacoes">
            <img src="caminho-para-o-icone.png" alt="Ícone de Notificações">
        </div>
    </div>
    <div id="menu-lateral">
        <ul>
            <li><a href="inicio.php">Inicio</a></li>
            <li><a href="Venda.html">Venda</a></li>
            <li><a href="Financeiro.php">Financeiro</a></li>
            <li><a href="Debitos.html">Debitos</a></li>
            <li><a href="Notificações.php">Notificações</a></li>
            <li><a href="estoque.html">Estoque</a></li>
            <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
        </ul>
    </div>
<?php
require_once "config.php"; 

$notificacoes_por_pagina = 10; // Número de notificações por página
$pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $notificacoes_por_pagina;

$sql = "SELECT * FROM notificacoes ORDER BY data DESC LIMIT $notificacoes_por_pagina OFFSET $offset";
$result = $conn->query($sql);

// Inicialize um array para armazenar as notificações
$notificacoes = array();

if ($result->num_rows > 0) {
    echo "<h2>Notificações</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['mensagem']} ({$row['data']})</li>";
    }
    echo "</ul>";
    
    // Cálculo da paginação
    $sql_total = "SELECT COUNT(*) as total FROM notificacoes";
    $result_total = $conn->query($sql_total);
    $row_total = $result_total->fetch_assoc();
    $total_notificacoes = $row_total['total'];
    $total_paginas = ceil($total_notificacoes / $notificacoes_por_pagina);

    // Exibe os links da paginação
    echo "<div class='paginacao'>";
    for ($i = 1; $i <= $total_paginas; $i++) {
        echo "<a href='Notificações.php?pagina=$i'>$i</a>";
    }
    echo "</div>";
}

// Botão para apagar notificações
echo "<form method='post'>";
echo "<button type='submit' name='apagar_notificacoes'>Apagar Todas as Notificações</button>";
echo "</form>";

if (isset($_POST['apagar_notificacoes'])) {
    // Código para apagar todas as notificações
    $sql = "DELETE FROM notificacoes";
    if ($conn->query($sql) === TRUE) {
        echo "Todas as notificações foram apagadas com sucesso.";
    } else {
        echo "Erro ao apagar notificações: " . $conn->error;
    }
}
?>
</body>
</html>

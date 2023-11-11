<?php
// Inicie a sessão
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se o usuário não estiver logado, redirecione para a página de login
    header("Location: ../HTML/index.html");
    exit();
}

// Você agora pode acessar as informações do usuário a partir de $_SESSION
$idUsuario = $_SESSION['id'];
$nomeUsuario = $_SESSION['nome'];
$cargoUsuario = $_SESSION['cargo'];
$arquivo = $_SESSION['arquivo'];

require_once "config.php"; // arquivo de config do bd

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

$nome_produto = isset($_POST["nome"]) ? strtoupper(removeAcentos($_POST["nome"])) : "";
$referencia = isset($_POST["referencia"]) ? strtoupper(removeAcentos($_POST["referencia"])) : "";
$marca = isset($_POST["marca"]) ? strtoupper(removeAcentos($_POST["marca"])) : "";
$aplicacao = isset($_POST["aplicacao"]) ? strtoupper(removeAcentos($_POST["aplicacao"])) : "";
$ano = isset($_POST["ano"]) ? $_POST["ano"] : "";

$aviso = "";

$consulta = []; // Definindo uma matriz vazia

$sql = "SELECT * FROM estoque WHERE 1"; // Inicializa a consulta com "1" para garantir que sempre haja uma condição

if (!empty($nome_produto)) {
    $sql .= " AND UPPER(REPLACE(nome, ' ', '')) = UPPER(REPLACE('$nome_produto', ' ', ''))";
}

if (!empty($referencia)) {
    $sql .= " AND UPPER(REPLACE(referencia, ' ', '')) = UPPER(REPLACE('$referencia', ' ', ''))";
}

if (!empty($marca)) {
    $sql .= " AND UPPER(REPLACE(marca, ' ', '')) = UPPER(REPLACE('$marca', ' ', ''))";
}

if (!empty($aplicacao)) {
    $sql .= " AND UPPER(REPLACE(aplicacao, ' ', '')) = UPPER(REPLACE('$aplicacao', ' ', ''))";
}

if (!empty($ano)) {
    $sql .= " AND ano = '$ano'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consulta[] = $row;
    }
} else {
    $aviso = "Produto não encontrado.";
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Financeiro</title>

    <link rel="stylesheet" href="../CSS/estoque.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
</head>

<body>
    <nav class="menu_lateral">

        <!-- Barra MENU -->
        <div class="btn_expandir">
            <img src="../CSS/img/Três barras.svg" alt="menu" id="btn_exp">
        </div>

        <!--  itens MENU LATERAL-->
        <ul class="ul_menu_lateral">

            <li class="item_menu">
                <a href="../PHP/Inicio.php">
                    <img class="icon" src="../CSS/img/Logo Circular verde.svg" alt="logo">
                    <span class="txt_link">Home</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Venda.php">
                    <img class="icon" src="../CSS/img/VENDAS.svg" alt="icone compras">
                    <span class="txt_link">Vendas</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/estoque.php">
                    <img class="icon" src="../CSS/img/Compras.svg" alt="icone compras">
                    <span class="txt_link">Estoque</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Financeiro.php">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Financeiro</span>
                </a>
            </li>

            <?php if ($cargoUsuario != 'vendedor') { ?>
                <li class="item_menu">
                    <a href="../PHP/Debitos.php">
                        <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                        <span class="txt_link">Débitos</span>
                    </a>
                </li>
            <?php } ?>

            <li class="item_menu">
                <a href="../PHP/Criação OS.php">
                    <img class="icon" src="../CSS/img/OS.svg" alt="icone OS">
                    <span class="txt_link">O.S</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/pagina_cadastro.php">
                        <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                        <span class="txt_link">Cadastro</span>
                    </a>
                </li>

            <?php if ($cargoUsuario != 'vendedor') { ?>
                <li class="item_menu">
                    <a href="../PHP/Notificações.php">
                        <img class="icon" src="../CSS/img/Sino.svg" alt="logo">
                        <span class="txt_link">Notificações</span>
                    </a>
                </li>
            <?php } ?>

        </ul>
        <!-- importando o JS para o Menu Lateral-->
        <script src="../JS/menu.js"></script>

    </nav>

    <!-- Menu horizonatl -->
    <nav class="menu_horizontal">
        <ul>
            <li id="logo_menu_horizontal"><a href="../PHP/Inicio.php"><img src="../CSS/img/Logo Horizontal.png"
                        alt="logo da empresa"></a>
            </li>

            <li id="direita">

                <!-- Perfil -->
                <div class="image_container">
                    <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';?>
                </div>

                <div><a href="logout.php">Sair</a></div>

                <script src="../JS/login_preview.js"></script>
            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <h3><?php echo $cargoUsuario; ?></h3>
                    <p><?php echo $nomeUsuario; ?></p>
                </div>
            </li>

            <?php if ($cargoUsuario != 'vendedor') { ?>
                    <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>
                <?php } ?>

        </ul>

    </nav>

    <div id="resultado_busca">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/estoque.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
            <h1>Consulta Item</h1>
        </div>
        <div class="centralização">
            <table>

                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Preço Varejo</th>
                    <th>Preço Atacado</th>
                    <th>Ano</th>
                    <th>Marca</th>
                    <th>Referência</th>
                    <th>Aplicação</th>
                    <th>Quantidade</th>
                </tr>

                <?php
                foreach ($consulta as $pesquisa) {
                    echo "<tr>";
                    echo '<td><img id="img_width" width="100%" src="' . $pesquisa["imagem"] . '"></td>';
                    echo "<td>" . $pesquisa["nome"] . "</td>";
                    echo "<td>" . $pesquisa["valor_varejo"] . "</td>";
                    echo "<td>" . $pesquisa["valor_atacado"] . "</td>";
                    echo "<td>" . $pesquisa["ano"] . "</td>";
                    echo "<td>" . $pesquisa["marca"] . "</td>";
                    echo "<td>" . $pesquisa["referencia"] . "</td>";
                    echo "<td>" . $pesquisa["aplicacao"] . "</td>";
                    echo "<td>" . $pesquisa["quantidade"] . "</td>";
                    echo "</tr>";
                }
                ?>

            </table>
        </div>
    </div>
</body>

</html>
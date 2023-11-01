<?php
require_once "config.php"; // arquivo de config do bd

$nome_produto = $_POST["nome"];
$referencia = $_POST["referencia"];
$marca = $_POST["marca"];
$aplicacao = $_POST["aplicacao"];
$ano = $_POST["ano"];

$sql = "SELECT * FROM estoque WHERE nome = '$nome_produto' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consulta[] = $row;
    }
} else {
    echo "Produto não encontrado.";
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

            <li class="item_menu ativo">
                <a href="../PHP/Inicio.php">
                    <img class="icon" src="../CSS/img/Logo Circular verde.svg" alt="logo">
                    <span class="txt_link">Home</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../HTML/Venda.html">
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
                <a href="../HTML/Financeiro.html">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Vendas</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Debitos.php">
                    <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                    <span class="txt_link">Débitos</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Criação OS.php">
                    <img class="icon" src="../CSS/img/OS.svg" alt="icone OS">
                    <span class="txt_link">O.S</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="#">
                    <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                    <span class="txt_link">Perfil</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Notificações.php">
                    <img class="icon" src="../CSS/img/Sino.svg" alt="logo">
                    <span class="txt_link">Notificações</span>
                </a>
            </li>

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
                    <img src="../CSS/img/editar.png" alt="insira foto de perfil" id="img_photo">
                </div>

                <!-- Escolhendo Imagem -->
                <input type="file" id="file_image" name="file_image" accept="image/*">

                <script src="../JS/login_preview.js"></script>
            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <h3>Cargo</h3>
                    <p>Nome e Sobrenome</p>
                </div>
            </li>

            <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg"
                        alt="Notificações"></a></li>

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
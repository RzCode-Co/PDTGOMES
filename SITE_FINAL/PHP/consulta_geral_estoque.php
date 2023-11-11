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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Estoque</title>
    <link rel="stylesheet" href="../CSS/estoque.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
</head>

<body>

    <!-- Menu lateral -->
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
                <div class="btn_sair"><a href="logout.php">Sair &#215</a></div>
            </li>

            <li id="direita">

                <div class="image_container">
                    <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">'; ?>
                </div>

            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <h3>
                        <?php echo $cargoUsuario; ?>
                    </h3>
                    <p>
                        <?php echo $nomeUsuario; ?>
                    </p>
                </div>
            </li>

            <?php if ($cargoUsuario != 'vendedor') { ?>
                <li id="direita"><a class="sino" href="../PHP/Notificações.php"><img
                            src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>
            <?php } ?>

        </ul>

    </nav>

    <div id="resultado_busca_geral">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/estoque.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
            <h1>Consulta Geral de Itens</h1>
        </div>

        <table>
            <?php
            require_once "config.php"; // Inclua seu arquivo de configuração do banco de dados aqui
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["nome"])) {
                    $nome_produto = strtoupper($_POST["nome"]);
                    $referencia = strtoupper($_POST["referencia"]);
                    $marca = strtoupper($_POST["marca"]);
                    $aplicacao = strtoupper($_POST["aplicacao"]);
                    $ano = strtoupper($_POST["ano"]);

                    $sql = "SELECT * FROM estoque WHERE nome = '$nome_produto' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $consulta[] = $row;
                        }
                    } else {
                        echo "Produto não encontrado.";
                    }
                }
            }

            // Consulta SQL para selecionar todos os produtos da tabela "estoque"
            $sql = "SELECT * FROM estoque";

            $result = $conn->query($sql);

            // Inicialize um array para armazenar os resultados da consulta
            $consulta = [];

            if ($result->num_rows > 0) {
                // Armazene os resultados da consulta no array $consulta
                while ($row = $result->fetch_assoc()) {
                    $consulta[] = $row;
                }
            }
            ?>

            <table>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço Varejo</th>
                    <th>Preço Atacado</th>
                    <th>Ano</th>
                    <th>Marca</th>
                    <th>Referência</th>
                    <th>Aplicação</th>
                </tr>
                <?php
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $itemsPerPage = 10; // Número de itens por página
                $startIndex = ($page - 1) * $itemsPerPage;
                $endIndex = $startIndex + $itemsPerPage;

                // Exibir os itens da página atual
                for ($i = $startIndex; $i < $endIndex && $i < count($consulta); $i++) {
                    $item = $consulta[$i];
                    echo "<tr>";
                    echo '<td><img  id="img_width" width="100%" src="' . $item["imagem"] . '"></td>';
                    echo "<td>" . $item["nome"] . "</td>";
                    echo "<td>" . $item["quantidade"] . "</td>";
                    echo "<td>" . $item["valor_varejo"] . "</td>";
                    echo "<td>" . $item["valor_atacado"] . "</td>";
                    echo "<td>" . $item["ano"] . "</td>";
                    echo "<td>" . $item["marca"] . "</td>";
                    echo "<td>" . $item["referencia"] . "</td>";
                    echo "<td>" . $item["aplicacao"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>

            <!-- Adicione um link para a paginação -->
            <div id="pagination">
                <?php
                $totalItems = count($consulta); // Total de itens na consulta
                $totalPages = ceil($totalItems / $itemsPerPage);

                // Limita o número de páginas a serem exibidas na paginação
                $maxVisiblePages = 5;

                $startPage = max(1, $page - floor($maxVisiblePages / 2));
                $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);

                if ($page > 1) {
                    echo '<a href="consulta_geral_estoque.php?page=' . ($page - 1) . '">&laquo;</a> ';
                }

                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $page) {
                        echo '<strong>' . $i . '</strong> ';
                    } else {
                        echo '<a href="consulta_geral_estoque.php?page=' . $i . '">' . $i . '</a> ';
                    }
                }

                if ($page < $totalPages) {
                    echo '<a href="consulta_geral_estoque.php?page=' . ($page + 1) . '">&raquo;</a> ';
                }
                ?>
            </div>

    </div>
    </div>
    <main>
</body>

<script>
    function mostrarAdicionarItem() {
        document.getElementById("adicionar-item").style.display = "block";
        document.getElementById("remover-item").style.display = "none";
        document.getElementById("consultar-item").style.display = "none";
        document.getElementById("resultado_busca_geral").style.display = "none";
    }
    function mostrarRemoverItem() {
        document.getElementById("adicionar-item").style.display = "none";
        document.getElementById("remover-item").style.display = "block";
        document.getElementById("consultar-item").style.display = "none";
        document.getElementById("resultado_busca_geral").style.display = "none";
    }
    function mostrarConsultarItem() {
        document.getElementById("adicionar-item").style.display = "none";
        document.getElementById("remover-item").style.display = "none";
        document.getElementById("consultar-item").style.display = "block";
        document.getElementById("resultado_busca_geral").style.display = "none";
    }
    function mostrarConsultarTodosItens() {
        document.getElementById("adicionar-item").style.display = "none";
        document.getElementById("remover-item").style.display = "none";
        document.getElementById("consultar-item").style.display = "none";
        document.getElementById("resultado_busca_geral").style.display = "block";
    }
</script>

</html>
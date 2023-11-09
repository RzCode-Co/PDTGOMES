<?php
require_once "config.php"; // Arquivo de configuração do banco de dados
// Consulta SQL para buscar todas as vendas na tabela "vendas"
$sql = "SELECT * FROM vendas";

$result = $conn->query($sql);

// Array para armazenar o histórico de vendas
$historico = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Financeiro</title>
    <link rel="stylesheet" href="../CSS/financeiro.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

</style>

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
                <a href="../PHP/Financeiro.php">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Financeiro</span>
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
                    <a href="../HTML/pagina_cadastro.html">
                        <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                        <span class="txt_link">Cadastro</span>
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

    <div id="historico-de-vendas">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/Financeiro.php"><img src="../CSS/img/voltar.svg"
                    alt="voltar página"></a>
            <h1>Histórico de Vendas</h1>
        </div>
        <table>
            <tr>
                <th>Funcionário</th>
                <th>Comprador</th>
                <th>Peça</th>
                <th>Forma de Pagamento</th>
                <th>Valor</th>
                <th>Quantidade</th>
            </tr>

            <?php
            $itemsPerPage = 10;

            // Obtenha a página atual a partir dos parâmetros da URL
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

            $totalItems = count($historico);
            $totalPages = ceil($totalItems / $itemsPerPage);

            // Calcule o índice de início e fim para os itens da página atual
            $startIndex = ($page - 1) * $itemsPerPage;
            $endIndex = $startIndex + $itemsPerPage;

            if ($endIndex > $totalItems) {
                $endIndex = $totalItems;
            }

            for ($i = $startIndex; $i < $endIndex; $i++) {
                $venda = $historico[$i];
                echo '<tr>';
                echo '<td>' . $venda['funcionario_vendedor'] . '</td>';
                echo '<td>' . $venda['nome_comprador'] . '</td>';
                echo '<td>' . $venda['nome_peca'] . '</td>';
                echo '<td>' . $venda['forma_pagamento'] . '</td>';
                echo '<td>' . $venda['valor_venda'] . '</td>';
                echo '<td>' . $venda['quantidade'] . '</td>';
                echo '</tr>';
            }
            ?>
        </table>

        <!-- Adicione os links de paginação abaixo da tabela -->
        <div id="pagination">
            <?php
            if ($totalPages > 1) {
                $currentPage = $page;

                if ($currentPage > 1) {
                    echo '<a href="financeiro_historico.php?page=1">&laquo;&laquo;</a>';
                    echo '<a href="financeiro_historico.php?page=' . ($currentPage - 1) . '">&laquo;</a>';
                }

                // Mostrar até 5 links de página
                for ($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++) {
                    if ($i == $currentPage) {
                        echo '<strong>' . $i . '</strong>';
                    } else {
                        echo '<a href="financeiro_historico.php?page=' . $i . '">' . $i . '</a>';
                    }
                }

                if ($currentPage < $totalPages) {
                    echo '<a href="financeiro_historico.php?page=' . ($currentPage + 1) . '">&raquo;</a>';
                    echo '<a href="financeiro_historico.php?page=' . $totalPages . '">&raquo;&raquo;</a>';
                }
            }
            ?>
        </div>
</body>

</html>
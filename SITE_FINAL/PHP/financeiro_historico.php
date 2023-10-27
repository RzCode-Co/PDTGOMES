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
                <a href="../HTML/pagina_incial.html">
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
                <a href="../PHP/Graficos.php">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Gráficos</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../HTMl/Financeiro.html">
                    <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                    <span class="txt_link">Históricos</span>
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
            <li id="logo_menu_horizontal"><a href="../HTML/pagina_incial.html"><img src="../CSS/img/Logo Horizontal.png"
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

            <li id="direita"><a href="#"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>

        </ul>

    </nav>

    <div id="historico_vendas">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../HTML/financeiro.html"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
            <h1>Histórico de Vendas</h1>
        </div>
        <div class="centralização">
            <table>
                <?php
                // Defina o número de itens por página
                $itemsPerPage = 10;

                // Obtenha a página atual a partir dos parâmetros da URL
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                // Calcule o índice de início e fim para os itens da página atual
                $startIndex = ($page - 1) * $itemsPerPage;
                $endIndex = $startIndex + $itemsPerPage;

                // Crie a tabela para exibir os itens da página atual (histórico de vendas)
                echo '<table>';
                echo '<tr>
                        <th>Funcionário</th>
                        <th>Comprador</th>
                        <th>Peça</th>
                        <th>Forma de Pagamento</th>
                        <th>Valor</th>
                        <th>Quantidade</th>
                    </tr>';

                $displayedCount = 0; // Contador para controlar a exibição dos itens
                
                foreach ($historico as $venda) {
                    if ($displayedCount >= $startIndex && $displayedCount < $endIndex) {
                        echo '<tr>';
                        echo '<td>' . $venda['funcionario_vendedor'] . '</td>';
                        echo '<td>' . $venda['nome_comprador'] . '</td>';
                        echo '<td>' . $venda['nome_peca'] . '</td>';
                        echo '<td>' . $venda['forma_pagamento'] . '</td>';
                        echo '<td>' . $venda['valor_venda'] . '</td>';
                        echo '<td>' . $venda['quantidade'] . '</td>';
                        echo '</tr>';
                    }
                    $displayedCount++;
                }

                echo '</table>';

                echo '</div>';

                // Adicione os links de paginação
                echo '<div id="pagination">';
                $totalItems = count($historico);
                $totalPages = ceil($totalItems / $itemsPerPage);

                $displayedPages = 5; // Número de páginas a serem exibidas na páginação
                
                if ($totalPages > 1) {
                    $currentPage = $page;
                    $firstPage = max(1, $currentPage - floor($displayedPages / 2));
                    $lastPage = min($totalPages, $firstPage + $displayedPages - 1);

                    for ($i = $firstPage; $i <= $lastPage; $i++) {
                        echo '<a href="financeiro_historico.php?page=' . $i . '">' . $i . '</a> ';

                    }

                    if ($lastPage < $totalPages) {
                        echo '<a href="financeiro_historico.php?page=' . ($lastPage + 1) . '">...</a> ';
                    }

                    if ($currentPage < $totalPages - floor($displayedPages / 2)) {
                        echo '<a href="financeiro_historico.php?page=' . $totalPages . '">' . $totalPages . '</a> ';
                    }
                }

                echo '</div>';
                ?>
            </table>
        </div>
</body>

</html>
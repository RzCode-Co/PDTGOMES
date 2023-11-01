<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Consulta SQL para buscar as vendas com forma de pagamento "Parcelado"
$sql = "SELECT nome_comprador, valor_venda, numero_parcelas, data_venda FROM vendas WHERE forma_pagamento = 'Parcelado'";

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../CSS/financeiro.css">
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
                <a href="../PHP/Graficos.php">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Gráficos</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/financeiro_historico.php">
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

            <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>

        </ul>

    </nav>

    <div id="contas-a-receber">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/financeiro_contas.php"><img src="../CSS/img/voltar.svg"
                    alt="voltar página"></a>
            <h1>Contas a receber</h1>
        </div>

        <div class="centralização">
            <?php
            // Defina o número de itens por página
            $itemsPerPage = 10;

            // Obtenha a página atual a partir dos parâmetros da URL
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

            $totalItems = count($historico);
            $totalPages = ceil($totalItems / $itemsPerPage);

            // Verifique se a página solicitada é válida
            if ($page < 1) {
                $page = 1;
            } elseif ($page > $totalPages) {
                $page = $totalPages;
            }

            // Calcule o índice de início e fim para os itens da página atual
            $startIndex = ($page - 1) * $itemsPerPage;
            $endIndex = min($startIndex + $itemsPerPage, $totalItems);

            // Verifique se há itens a serem exibir
            if (!empty($historico)) {
                echo '<table>';
                echo '<tr>
                        <th>Nome do comprador</th>
                        <th>Valor da venda</th>
                        <th>Número de Parcelas</th>
                        <th>Data de Venda</th>
                    </tr>';

                for ($i = $startIndex; $i < $endIndex; $i++) {
                    echo '<tr>';
                    echo '<td>' . $historico[$i]['nome_comprador'] . '</td>';
                    echo '<td>' . $historico[$i]['valor_venda'] . '</td>';
                    echo '<td>' . $historico[$i]['numero_parcelas'] . 'x</td>';
                    echo '<td>' . $historico[$i]['data_venda'] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>Não há itens para exibir.</p>';
            }
            ?>
        </div>

        <div id="pagination">
            <?php
            if ($totalPages > 1) {
                $currentPage = $page;

                echo '<ul class="pagination">';
                if ($currentPage > 1) {
                    echo '<a href="financeiro_contas.php?page=1">&laquo;&laquo;</a>';
                    echo '<a href="financeiro_contas.php?page=' . ($currentPage - 1) . '">&laquo;</a>';
                }

                // Mostrar até 5 links de página
                for ($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++) {
                    if ($i == $currentPage) {
                        echo '<strong>' . $i . '</strong>';
                    } else {
                        echo '<a href="financeiro_contas.php?page=' . $i . '">' . $i . '</a>';
                    }
                }

                if ($currentPage < $totalPages) {
                    echo '<a href="financeiro_contas.php?page=' . ($currentPage + 1) . '">&raquo;</a>';
                    echo '<a href="financeiro_contas.php?page=' . $totalPages . '">&raquo;&raquo;</a>';
                }

                echo '</ul>';
            }
            ?>
        </div>
    </div>
</body>

</html>
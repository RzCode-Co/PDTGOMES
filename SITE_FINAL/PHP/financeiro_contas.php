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

require_once "config.php"; // Arquivo de configuração do banco de dados

// Consulta SQL para buscar as vendas com forma de pagamento "Parcelado"
$sqlVendas = "SELECT id, nome_comprador, valor_venda, forma_pagamento, numero_parcelas, data_venda FROM vendas WHERE forma_pagamento = 'Boleto'";
$resultVendas = $conn->query($sqlVendas);

// Consulta SQL para buscar as informações da tabela "ordem_servico_completa" com forma de pagamento "Parcelado"
$sqlOrdemServico = "SELECT ID, cliente_nome, preco_total_geral, forma_pagamento, numero_parcelas, data_abertura FROM ordem_servico_completa WHERE forma_pagamento = 'Boleto'";
$resultOrdemServico = $conn->query($sqlOrdemServico);

// Combine os resultados das vendas e da ordem de serviço
$historico = array();

while ($row = $resultVendas->fetch_assoc()) {
    $historico[] = $row;
}

while ($row = $resultOrdemServico->fetch_assoc()) {
    $historico[] = $row;
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
                <a href="../PHP/Venda.php">
                    <img class ="icon" src="../CSS/img/VENDAS.svg" alt="icone compras">
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
                <li id="direita"><a class="sino" href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg"
                            alt="Notificações"></a></li>
            <?php } ?>

        </ul>

    </nav>

    <div id="contas-a-receber">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/Financeiro.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
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
                        <th>id</th>
                        <th>Nome do comprador</th>
                        <th>Valor da venda</th>
                        <th>Forma de pagamento</th>
                        <th>Número de Parcelas</th>
                        <th>Data de Venda</th>
                        <th>Excluir</th>
                    </tr>';

                    for ($i = $startIndex; $i < $endIndex; $i++) {
                        echo '<tr>';
                        if (isset($historico[$i]['nome_comprador'])) {
                            echo '<td>' . $historico[$i]['id'] . '</td>';
                            echo '<td>' . $historico[$i]['nome_comprador'] . '</td>';
                            echo '<td>' . $historico[$i]['valor_venda'] . '</td>';
                            echo '<td>' . $historico[$i]['forma_pagamento'] . '</td>';
                            echo '<td>' . $historico[$i]['numero_parcelas'] . 'x</td>';
                            echo '<td>' . $historico[$i]['data_venda'] . '</td>';
                            echo '<td>';
                            echo '<form method="post" action="excluir_venda_conta.php">'; // Substitua "excluir.php" pelo nome do seu script de exclusão
                            echo '<input type="hidden" name="id" value="' . $historico[$i]['id'] . '">';
                            echo '<button type="submit">Excluir</button>';
                            echo '</form>';
                            echo '</td>';
                        } else {
                            // Estas colunas são da tabela "ordem_servico_completa"
                            echo '<td>' . $historico[$i]['ID'] . '</td>';
                            echo '<td>' . $historico[$i]['cliente_nome'] . '</td>';
                            echo '<td>' . $historico[$i]['preco_total_geral'] . '</td>';
                            echo '<td>' . $historico[$i]['forma_pagamento'] . '</td>';
                            echo '<td>' . $historico[$i]['numero_parcelas'] . '</td>';
                            echo '<td>' . $historico[$i]['data_abertura'] . '</td>';
                            echo '<td>';
                            echo '<form method="post" action="excluir_ordem_servico_conta.php">'; // Substitua "excluir.php" pelo nome do seu script de exclusão
                            echo '<input type="hidden" name="id" value="' . $historico[$i]['ID'] . '">';
                            echo '<button type="submit">Excluir</button>';
                            echo '</form>';
                            echo '</td>';
                        }
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
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial</title>

    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <link rel="stylesheet" href="../CSS/consultar_clientes.css">

</head>

<html>

<body>
    <main>
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
                    <a href="../PHP/Financeiro.php">
                        <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                        <span class="txt_link">Finaneiro</span>
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
                <li id="logo_menu_horizontal"><a href="#"><img src="../CSS/img/Logo Horizontal.png"
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

        <div class="centralizacao">

            <div class="titulo_icone">
                <a id="icone_voltar" href="../HTML/pagina_cadastro.html"><img src="../CSS/img/voltar.svg"
                        alt="voltar página"></a>
                <h1>Consulta Cliente</h1>
            </div>


            <div id="pesquisa_nome">
                <form method="post" action="">
                    <label for="nomeCliente">Pesquisar por nome:</label>
                    <input type="text" id="nomeCliente" name="nomeCliente">
                    <button type="submit">Pesquisar</button>
                </form>
            </div>

        </div>

        <div class="tabela-clientes">
            <table>
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Total Gasto</th>
                        <th>CPF/CNPJ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once "config.php";

                    $sql = "SELECT nome, endereco, arquivo, cpf_cnpj, CPF, CNPJ FROM usuarios WHERE cargo = 'cliente'";
                    if (isset($_POST['nomeCliente'])) {
                        $nomeCliente = removeAcentos(mb_strtoupper($_POST['nomeCliente'], 'UTF-8'));
                        $sql .= " AND UPPER(nome) LIKE '%$nomeCliente%'";
                    }

                    $result = $conn->query($sql);


                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    $itemsPerPage = 10; // Número de itens por página
                    $startIndex = ($page - 1) * $itemsPerPage;
                    $endIndex = $startIndex + $itemsPerPage;

                    $data = [];

                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }

                    $totalItems = count($data); // Total de itens na consulta
                    $totalPages = ceil($totalItems / $itemsPerPage);

                    // Verifica se a página solicitada é válida
                    if ($page < 1 || $page > $totalPages) {
                        $page = 1; // Página padrão se a solicitada não for válida
                    }

                    $startIndex = ($page - 1) * $itemsPerPage;
                    $endIndex = min($startIndex + $itemsPerPage, $totalItems);

                    for ($i = $startIndex; $i < $endIndex; $i++) {
                        $item = $data[$i];

                        echo "<tr>";
                        echo "<td><img src='{$item['arquivo']}' alt='{$item['nome']}' width='50'></td>";
                        echo "<td>{$item['nome']}</td>";
                        echo "<td>{$item['endereco']}</td>";
                        echo "<td>R$ " . number_format(obterTotalGasto($conn, $item['cpf_cnpj'], $item['CPF'], $item['CNPJ']), 2, ',', '.') . "</td>";

                        // Exibe CPF ou CNPJ com base no que está cadastrado
                        if ($item['cpf_cnpj'] === 'CPF') {
                            echo "<td>{$item['CPF']}</td>";
                        } elseif ($item['cpf_cnpj'] === 'CNPJ') {
                            echo "<td>{$item['CNPJ']}</td>";
                        } else {
                            echo "<td>Nenhum cliente cadastrado.</td>";
                        }

                        echo "</tr>";
                    }

                    function obterTotalGasto($conn, $cpf_cnpj, $cpf, $cnpj)
                    {
                        // Use prepared statement para evitar injeção de SQL
                        $stmt = $conn->prepare("SELECT SUM(valor_venda) AS total_gasto FROM vendas WHERE (cpf_cnpj = ? AND cpf_cnpj = 'CPF' AND cpf = ?) OR (cpf_cnpj = ? AND cpf_cnpj = 'CNPJ' AND cnpj = ?)");
                        $stmt->bind_param("ssss", $cpf_cnpj, $cpf, $cpf_cnpj, $cnpj);

                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $totalGasto = $row['total_gasto'];
                        $stmt->close();

                        return $totalGasto;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
        echo '<div id="pagination">';
        if ($page > 1) {
            echo '<a href="sua_pagina.php?page=' . ($page - 1) . '">&laquo;</a> ';
        }

        for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++) {
            if ($i == $page) {
                echo '<strong>' . $i . '</strong> ';
            } else {
                echo '<a href="sua_pagina.php?page=' . $i . '">' . $i . '</a> ';
            }
        }

        if ($page < $totalPages) {
            echo '<a href="sua_pagina.php?page=' . ($page + 1) . '">&raquo;</a> ';
        }
        echo '</div>';
        ?>


    </main>
</body>

</html>

</html>
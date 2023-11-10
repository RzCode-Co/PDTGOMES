<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resultado Inicio</title>


    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <link rel="stylesheet" href="../CSS/resultado_inicio.css">
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

    <div id="centralizar-container">
        <div id="container-resultado">
            <?php
            require_once "config.php";
            // Inicialize as variáveis para armazenar os valores dos campos
            $nome = $referencia = $marca = $aplicacao = $ano = "";
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $nome = isset($_GET['nome']) ? $_GET['nome'] : "";
                $referencia = isset($_GET['referencia']) ? $_GET['referencia'] : "";
                $marca = isset($_GET['marca']) ? $_GET['marca'] : "";
                $aplicacao = isset($_GET['aplicacao']) ? $_GET['aplicacao'] : "";
                $ano = isset($_GET['ano']) ? $_GET['ano'] : "";
            }
            // Consulta SQL sem LIMIT
            $sql = "SELECT * FROM estoque WHERE 1=1";
            if (!empty($nome)) {
                $nome = mysqli_real_escape_string($conn, $nome);
                $sql .= " AND nome LIKE '%$nome%'";
            }
            if (!empty($referencia)) {
                $referencia = mysqli_real_escape_string($conn, $referencia);
                $sql .= " AND referencia LIKE '%$referencia%'";
            }
            if (!empty($marca)) {
                $marca = mysqli_real_escape_string($conn, $marca);
                $sql .= " AND marca LIKE '%$marca%'";
            }
            if (!empty($aplicacao)) {
                $aplicacao = mysqli_real_escape_string($conn, $aplicacao);
                $sql .= " AND aplicacao LIKE '%$aplicacao%'";
            }
            if (!empty($ano)) {
                $sql .= " AND ano = $ano";
            }
            $result = $conn->query($sql);
            $totalResultados = $result->num_rows;
            // Adicione a funcionalidade de paginação
            $itensPorPagina = 10;
            $numPaginas = ceil($totalResultados / $itensPorPagina);
            $paginaAtual = isset($_GET['page']) ? $_GET['page'] : 1;
            // Calcula o OFFSET com base na página atual
            $offset = ($paginaAtual - 1) * $itensPorPagina;
            // Consulta SQL com LIMIT e OFFSET
            $sql .= " LIMIT $itensPorPagina OFFSET $offset";
            $result = $conn->query($sql);
            // Exiba os resultados
            if ($result->num_rows > 0) {
                echo "<div class='container-tabela'>";
                echo '<div class="titulo_icone">
                            <a id="icone_voltar" href="../PHP/inicio.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
                            <h2>Resultados da Pesquisa:</h2>
                             </div>';
                echo "<table>";
                echo "<tr> <th>Foto</th><th>Nome</th><th>Referência</th><th>Marca</th><th>Aplicação</th><th>Ano</th><th>Quantidade</th><th>Valor de Varejo</th><th>Valor de Atacado</th>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo '<td><img id="img_width" src="' . $row["imagem"] . '"></td>';
                    echo "<td>" . $row["nome"] . "</td>";
                    echo "<td>" . $row["referencia"] . "</td>";
                    echo "<td>" . $row["marca"] . "</td>";
                    echo "<td>" . $row["aplicacao"] . "</td>";
                    echo "<td>" . $row["ano"] . "</td>";
                    echo "<td>" . $row["quantidade"] . "</td>";
                    echo "<td>" . $row["valor_varejo"] . "</td>";
                    echo "<td>" . $row["valor_atacado"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                // Exiba a paginação
                echo "<div class='paginacao'>";
                if ($numPaginas > 1) {
                    if ($paginaAtual > 1) {
                        echo "<a href='?page=" . ($paginaAtual - 1) . "' class='pagina-anterior'>&laquo;</a>";
                    }
                    $quantidadeLinks = 5;
                    $inicio = max(1, $paginaAtual - floor($quantidadeLinks / 2));
                    $fim = min($numPaginas, $paginaAtual + floor($quantidadeLinks / 2));
                    for ($i = $inicio; $i <= $fim; $i++) {
                        if ($paginaAtual == $i) {
                            echo "<span class='pagina-atual'>$i</span>";
                        } else {
                            echo "<a href='?page=$i&nome=$nome&referencia=$referencia&marca=$marca&aplicacao=$aplicacao&ano=$ano' class='pagina'>$i</a>";
                        }
                    }
                    if ($paginaAtual < $numPaginas) {
                        echo "<a href='?page=" . ($paginaAtual + 1) . "' class='proxima-pagina'>&raquo;</a>";
                    }
                } else {
                    echo "<span class='pagina-atual'>1</span>";
                }
                echo "</div>";
            } else {
                echo "<p>Este item não se encontra no estoque.</p>";
            }
            echo "</div>";

            ?>
        </div>
    </div>
    <div id="centralizar-container">
        <div id="container-resultado">
            <?php
            require_once "config.php";
            // Inicialize as variáveis para armazenar os valores dos campos
            $nome = $referencia = $marca = $aplicacao = $ano = "";

            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $nome = isset($_GET['nome']) ? $_GET['nome'] : "";
                $referencia = isset($_GET['referencia']) ? $_GET['referencia'] : "";
                $marca = isset($_GET['marca']) ? $_GET['marca'] : "";
                $aplicacao = isset($_GET['aplicacao']) ? $_GET['aplicacao'] : "";
                $ano = isset($_GET['ano']) ? $_GET['ano'] : "";
            }

            // Consulta SQL para buscar produtos similares
            $sql_similares = "SELECT * FROM banco_de_dados_pdt WHERE 
            COL2 LIKE CONCAT('%', SUBSTRING_INDEX('$nome', ' ', 2), '%') AND 
            COL2 != '$nome'";

            $result_similares = $conn->query($sql_similares);
            $totalResultadosSimilares = $result_similares->num_rows;

            // Adicione a funcionalidade de paginação para produtos similares
            $itensPorPaginaSimilares = 10;
            $numPaginasSimilares = ceil($totalResultadosSimilares / $itensPorPaginaSimilares);
            $paginaAtualSimilares = isset($_GET['page_similares']) ? $_GET['page_similares'] : 1;

            // Calcula o OFFSET com base na página atual
            $offsetSimilares = ($paginaAtualSimilares - 1) * $itensPorPaginaSimilares;

            // Consulta SQL com LIMIT e OFFSET para produtos similares
            $sql_similares .= " LIMIT $itensPorPaginaSimilares OFFSET $offsetSimilares";
            $result_similares = $conn->query($sql_similares);

            // Exiba produtos similares
            if ($result_similares->num_rows > 0) {
            echo "<div class='container-tabela'>";
            echo '<div class="titulo_icone">
                        <h2>Produtos Similares:</h2>
                        </div>';
            echo "<table>";
            echo "<tr> <th>Código</th><th>Nome</th><th>Tipo</th><th>Quantidade</th><th>Valor de Custo</th><th>Valor a vista</th><th>Valor a prazo</th>";
            while ($row_similar = $result_similares->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_similar["COL1"] . "</td>";
                echo "<td>" . $row_similar["COL2"] . "</td>";
                echo "<td>" . $row_similar["COL3"] . "</td>";
                echo "<td>" . $row_similar["COL4"] . "</td>";
                echo "<td>" . $row_similar["COL5"] . "</td>";
                echo "<td>" . $row_similar["COL6"] . "</td>";
                echo "<td>" . $row_similar["COL7"] . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Exiba a paginação para produtos similares
            echo "<div class='paginacao'>";
            if ($numPaginasSimilares > 1) {
                if ($paginaAtualSimilares > 1) {
                    echo "<a href='?page_similares=" . ($paginaAtualSimilares - 1) . "' class='pagina-anterior'>&laquo;</a>";
                }
                $quantidadeLinksSimilares = 5;
                $inicioSimilares = max(1, $paginaAtualSimilares - floor($quantidadeLinksSimilares / 2));
                $fimSimilares = min($numPaginasSimilares, $paginaAtualSimilares + floor($quantidadeLinksSimilares / 2));
                for ($i = $inicioSimilares; $i <= $fimSimilares; $i++) {
                    if ($paginaAtualSimilares == $i) {
                        echo "<span class='pagina-atual'>$i</span>";
                    } else {
                        echo "<a href='?page_similares=$i&nome=$nome&referencia=$referencia&marca=$marca&aplicacao=$aplicacao&ano=$ano' class='pagina'>$i</a>";
                    }
                }
                if ($paginaAtualSimilares < $numPaginasSimilares) {
                    echo "<a href='?page_similares=" . ($paginaAtualSimilares + 1) . "' class='proxima-pagina'>&raquo;</a>";
                }
            } else {
                echo "<span class='pagina-atual'>1</span>";
            }
            echo "</div>";
            }
            else {
            echo "<p>Nenhum produto similar encontrado.</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>
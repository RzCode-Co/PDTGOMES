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

    <div id="centralizar-container">
        <div id="container-resultado">
            <?php
            require_once "config.php";

            function removerAcentos($str)
            {
                $str = strtoupper($str); // Converter para maiúsculas
                $str = preg_replace('/[ÁÀÂÃÄ]/u', 'A', $str);
                $str = preg_replace('/[ÉÈÊË]/u', 'E', $str);
                $str = preg_replace('/[ÍÌÎÏ]/u', 'I', $str);
                $str = preg_replace('/[ÓÒÔÕÖ]/u', 'O', $str);
                $str = preg_replace('/[ÚÙÛÜ]/u', 'U', $str);
                $str = preg_replace('/[Ç]/u', 'C', $str);
                return $str;
            }

            // Inicialize as variáveis para armazenar os valores dos campos
            $nome = $referencia = $marca = $aplicacao = $ano = "";
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $nome = isset($_GET['nome']) ? removerAcentos($_GET['nome']) : "";
                $referencia = isset($_GET['referencia']) ? removerAcentos($_GET['referencia']) : "";
                $marca = isset($_GET['marca']) ? removerAcentos($_GET['marca']) : "";
                $aplicacao = isset($_GET['aplicacao']) ? removerAcentos($_GET['aplicacao']) : "";
                $ano = isset($_GET['ano']) ? $_GET['ano'] : "";
            }
            // Consulta SQL sem LIMIT
            $sql = "SELECT * FROM estoque WHERE 1=1";
            if (!empty($nome)) {
                $nome = mysqli_real_escape_string($conn, $nome);
                $sql .= " AND nome LIKE '%" . $nome . "%'";
            }
            if (!empty($referencia)) {
                $referencia = mysqli_real_escape_string($conn, $referencia);
                $sql .= " AND referencia LIKE '%" . $referencia . "%'";
            }
            if (!empty($marca)) {
                $marca = mysqli_real_escape_string($conn, $marca);
                $sql .= " AND marca LIKE '%" . $marca . "%'";
            }
            if (!empty($aplicacao)) {
                $aplicacao = mysqli_real_escape_string($conn, $aplicacao);
                $sql .= " AND aplicacao LIKE '%" . $aplicacao . "%'";
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
                        echo "<a href='?page=" . ($paginaAtual - 1) . "&page_similares=$paginaAtual' class='pagina-anterior'>&laquo;</a>";
                    }
                    $quantidadeLinks = 5;
                    $inicio = max(1, $paginaAtual - floor($quantidadeLinks / 2));
                    $fim = min($numPaginas, $paginaAtual + floor($quantidadeLinks / 2));
                    for ($i = $inicio; $i <= $fim; $i++) {
                        if ($paginaAtual == $i) {
                            echo "<span class='pagina-atual'>$i</span>";
                        } else {
                            echo "<a href='?page=$i&nome=$nome&referencia=$referencia&marca=$marca&aplicacao=$aplicacao&ano=$ano&page_similares=$paginaAtual' class='pagina'>$i</a>";
                        }
                    }
                    if ($paginaAtual < $numPaginas) {
                        echo "<a href='?page=" . ($paginaAtual + 1) . "&page_similares=$paginaAtual' class='proxima-pagina'>&raquo;</a>";
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
        <div id="container-similares">
            <?php
            // Inicialize as variáveis para armazenar os valores dos campos
            $nome = $referencia = $marca = $aplicacao = $ano = "";

            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $nome = isset($_GET['nome']) ? removerAcentos($_GET['nome']) : "";
                $referencia = isset($_GET['referencia']) ? removerAcentos($_GET['referencia']) : "";
                $marca = isset($_GET['marca']) ? removerAcentos($_GET['marca']) : "";
                $aplicacao = isset($_GET['aplicacao']) ? removerAcentos($_GET['aplicacao']) : "";
                $ano = isset($_GET['ano']) ? $_GET['ano'] : "";
            }

            // Consulta SQL para buscar produtos similares
            $sql_similares = "SELECT * FROM banco_de_dados_pdt WHERE 
        COL2 LIKE CONCAT('%', SUBSTRING_INDEX('" . mysqli_real_escape_string($conn, $nome) . "', ' ', 2), '%') AND 
        COL2 != '" . mysqli_real_escape_string($conn, $nome) . "'";

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
                        echo "<a href='?page=" . $paginaAtualSimilares . "&page_similares=" . ($paginaAtualSimilares - 1) . "' class='pagina-anterior'>&laquo;</a>";
                    }
                    $quantidadeLinksSimilares = 5;
                    $inicioSimilares = max(1, $paginaAtualSimilares - floor($quantidadeLinksSimilares / 2));
                    $fimSimilares = min($numPaginasSimilares, $paginaAtualSimilares + floor($quantidadeLinksSimilares / 2));
                    for ($i = $inicioSimilares; $i <= $fimSimilares; $i++) {
                        if ($paginaAtualSimilares == $i) {
                            echo "<span class='pagina-atual'>$i</span>";
                        } else {
                            echo "<a href='?page=$paginaAtual&nome=$nome&referencia=$referencia&marca=$marca&aplicacao=$aplicacao&ano=$ano&page_similares=$i' class='pagina'>$i</a>";
                        }
                    }
                    if ($paginaAtualSimilares < $numPaginasSimilares) {
                        echo "<a href='?page=" . $paginaAtualSimilares . "&page_similares=" . ($paginaAtualSimilares + 1) . "' class='proxima-pagina'>&raquo;</a>";
                    }
                } else {
                    echo "<span class='pagina-atual'>1</span>";
                }
                echo "</div>";
            } else {
                echo "<p>Nenhum produto similar encontrado.</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>
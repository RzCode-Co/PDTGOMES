<?php
require_once "config.php";

$sql = "SELECT * FROM notificacoes ORDER BY data DESC";
$result = $conn->query($sql);

// Inicialize um array para armazenar as notificações
$notificacoes = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Armazene cada notificação no array
        $notificacoes[] = $row;
    }
}

if (isset($_POST['apagar_notificacoes'])) {
    // Código para apagar todas as notificações
    $sql = "DELETE FROM notificacoes";
    if ($conn->query($sql) === TRUE) {

    } else {
        echo "Erro ao apagar notificações: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Notificações</title>
    <link rel="stylesheet" href="../CSS/notificações.css">
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

    <!-- Notificações -->
    <div class="notificações">

        <div class="container_inline">
            <h2>Notificações</h2>


            <form method="post">
                <button id="apagar_baixo" type="submit" name="apagar_notificacoes">Apagar Todas as Notificações</button>
            </form>

        </div>

        <?php
        require_once "config.php";

        $notificacoes_por_pagina = 10; // Número de notificações por página
        $pagina_atual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $offset = ($pagina_atual - 1) * $notificacoes_por_pagina;

        $sql = "SELECT * FROM notificacoes ORDER BY data DESC LIMIT $notificacoes_por_pagina OFFSET $offset";
        $result = $conn->query($sql);

        // Inicialize um array para armazenar as notificações
        $notificacoes = array();

        if ($result->num_rows > 0) {
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['mensagem']} ({$row['data']})</li>";
            }
            echo "</ul>";

            // Cálculo da paginação
            $sql_total = "SELECT COUNT(*) as total FROM notificacoes";
            $result_total = $conn->query($sql_total);
            $row_total = $result_total->fetch_assoc();
            $total_notificacoes = $row_total['total'];
            $total_paginas = ceil($total_notificacoes / $notificacoes_por_pagina);

            // Define o número máximo de links a serem exibidos na paginação
            $max_links_paginacao = 5;

            // Calcula o número inicial e final de links a serem exibidos
            $pagina_inicial = max(1, $pagina_atual - floor($max_links_paginacao / 2));
            $pagina_final = min($total_paginas, $pagina_inicial + $max_links_paginacao - 1);

            // Exibe os links da paginação
            echo "<div class='paginacao'>";
            if ($pagina_atual > 1) {
                echo "<a href='Notificações.php?pagina=" . ($pagina_atual - 1) . "'>&laquo;</a>";
            }

            for ($i = $pagina_inicial; $i <= $pagina_final; $i++) {
                if ($i == $pagina_atual) {
                    echo "<strong>$i</strong>";
                } else {
                    echo "<a href='Notificações.php?pagina=$i'>$i</a>";
                }
            }

            if ($pagina_atual < $total_paginas) {
                echo "<a href='Notificações.php?pagina=" . ($pagina_atual + 1) . "'>&raquo;</a>";
            }

            echo "</div>";
        }

        // Botão para apagar notificações
        echo "<form method='post'>";
        echo "</form>";

        if (isset($_POST['apagar_notificacoes'])) {
            // Código para apagar todas as notificações
            $sql = "DELETE FROM notificacoes";
            if ($conn->query($sql) === TRUE) {
                echo "Todas as notificações foram apagadas com sucesso.";
            } else {
                echo "Erro ao apagar notificações: " . $conn->error;
            }
        }
        ?>
    </div>

</body>

</html>
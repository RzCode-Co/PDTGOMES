
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
    <!-- Menu lateral -->
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
                <a href="Venda.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                    <img class="icon" src="../CSS/img/VENDAS.svg" alt="icone compras">
                    <span class="txt_link">Vendas</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="estoque.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                    <img class="icon" src="../CSS/img/Compras.svg" alt="icone compras">
                    <span class="txt_link">Estoque</span>
                </a>
            </li>


            <li class="item_menu">
                <a href="Graficos.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Gráficos</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="Financeiro.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                    <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                    <span class="txt_link">Carteira</span>
                </a>
            </li>

            <li class="item_menu">
                    <a href="Criação OS.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
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
                <a href="Notificações.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
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
                    <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';?>
                </div>
                <script src="../JS/login_preview.js"></script>
            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <?php echo '<p>' . $cargo . '</p>';?>
                    <?php echo '<p>' . $nome . '</p>';?>
                </div>
            </li>

            <li id="direita"><a href="Notificações.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>

        </ul>

    </nav>

    <button onclick="redirecionarParaFinanceiroHistorico()">Mostrar Histórico de Vendas</button>
    <button onclick="redirecionarParaFinanceiroContas()">Mostrar Contas a Receber</button>
    <button onclick="redirecionarParaGraficos()">Mostrar Gráficos de Saldos e Débitos</button>

    <script>
        function redirecionarParaFinanceiroHistorico() {
            window.location.href = "financeiro_historico.php";
        }
        function redirecionarParaFinanceiroContas() {
            window.location.href ="contas_receber.php";
        }

        function redirecionarParaGraficos() {
            window.location.href = "Graficos.php";
        }
    </script>
</body>
</html>
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
    <title>Tela Inicial</title>
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

            <div class="image_container">
                    <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';?>
                </div>

                <div><a href="logout.php">Sair</a></div>

                <script src="../JS/login_preview.js"></script>
            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <h3><?php echo $cargoUsuario; ?></h3>
                    <p><?php echo $nomeUsuario; ?></p>
                </div>
            </li>

            <?php if ($cargoUsuario != 'vendedor') { ?>
                    <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>
                <?php } ?>

        </ul>

    </nav>
    <section class="busca_container">
        <div class="card">

            <!-- texto busque no estoque -->
            <div class="pesquisa">
                <h1>Busque no estoque</h1>
                <div class="border_titulo"></div>
            </div>

            <form class="form_home" action="resultado_inicio.php" method="get">

                <div class="input_busca">
                    <input type="text" name="nome" placeholder="Nome do Item"
                        value="<?php echo isset($nome) ? $nome : ''; ?>">
                </div>

                <div class="input_busca">
                    <input type="text" name="referencia" placeholder="Referência"
                        value="<?php echo isset($referencia) ? $referencia : ''; ?>">
                </div>

                <div class="input_busca">
                    <input type="text" name="marca" placeholder="Marca"
                        value="<?php echo isset($marca) ? $marca : ''; ?>">
                </div>

                <div class="input_busca">
                    <input type="text" name="aplicacao" placeholder="Aplicação"
                        value="<?php echo isset($aplicacao) ? $aplicacao : ''; ?>">
                </div>

                <div class="input_busca">
                    <input type="number" name="ano" placeholder="Ano" value="<?php echo isset($ano) ? $ano : ''; ?>">
                </div>

                <button id="btn-pesquisar" disabled>Pesquisar</button>
            </form>
        </div>
    </section>

    <script>
        // Função para verificar se pelo menos um campo está preenchido
        function verificarPreenchimento() {
            var campos = document.querySelectorAll('input[type="text"], input[type="number"]');
            var botaoPesquisar = document.getElementById('btn-pesquisar');
            var preenchido = false;

            for (var i = 0; i < campos.length; i++) {
                if (campos[i].value.trim() !== '') {
                    preenchido = true;
                    break;
                }
            }

            if (preenchido) {
                botaoPesquisar.removeAttribute('disabled');
            } else {
                botaoPesquisar.setAttribute('disabled', 'disabled');
            }
        }

        // Adicione um evento de entrada para os campos
        var campos = document.querySelectorAll('input[type="text"], input[type="number"]');
        for (var i = 0; i < campos.length; i++) {
            campos[i].addEventListener('input', verificarPreenchimento);
        }
    </script>
    </div>
</body>

</html>
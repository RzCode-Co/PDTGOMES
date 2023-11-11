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
// Consulta SQL para buscar o valor total das vendas do vendedor com base no CPF
$sql = "SELECT SUM(valor_venda) AS total_vendas FROM vendas WHERE funcionario_vendedor = '$idUsuario'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $venda = $result->fetch_assoc();
    $totalVendas = $venda['total_vendas'];
    $umPorcento = $totalVendas * 0.01;
}
$dataAtual = date("d-m-Y");
$dataAtual = date("d-m-Y", strtotime("+ 1 days"));
// Data de 7 dias antes da data atual
$dataSeteDiasAtras = date("d-m-Y", strtotime("-7 days"));
if ($dataSeteDiasAtras === NULL) {
    $dataSeteDiasAtras = NULL;
}
// Consulta SQL para buscar os dias únicos da coluna data_venda na tabela valores dentro do intervalo de datas
$sql_dias_disponiveis = "SELECT DISTINCT DATE(data_venda) AS dia FROM vendas WHERE DATE(data_venda) BETWEEN '$dataSeteDiasAtras' AND '$dataAtual'";
$result_dias_disponiveis = $conn->query($sql_dias_disponiveis);
// Array para armazenar os dias disponíveis
$dias_disponiveis = array();

if ($result_dias_disponiveis->num_rows > 0) {
    while ($row_dias = $result_dias_disponiveis->fetch_assoc()) {
        $dias_disponiveis[] = $row_dias['dia'];
    }
}
// Defina a localização para o idioma português
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

// Consulta SQL para buscar os meses e anos únicos da coluna data_venda na tabela valores
$sql_meses_disponiveis = "SELECT DISTINCT YEAR(data_venda) AS ano, MONTH(data_venda) AS mes FROM valores";
$result_meses_disponiveis = $conn->query($sql_meses_disponiveis);

// Arrays para armazenar os meses e anos disponíveis
$meses_disponiveis = array();
$anos_disponiveis = array();

if ($result_meses_disponiveis->num_rows > 0) {
    while ($row_meses = $result_meses_disponiveis->fetch_assoc()) {
        $ano = $row_meses['ano'];
        $mes = $row_meses['mes'];

        // Obtenha o nome do mês em português
        $nome_mes = strftime('%B', strtotime("{$ano}-{$mes}-01"));

        // Adicione o mês e o ano ao array apenas se ainda não estiverem lá
        if (!in_array($mes, $meses_disponiveis)) {
            $meses_disponiveis[] = array('valor' => $mes, 'nome' => $nome_mes);
        }
        if (!in_array($ano, $anos_disponiveis)) {
            $anos_disponiveis[] = $ano;
        }
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
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <link rel="stylesheet" href="../CSS/financeiro.css">
</head>

<body>
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
    
    <div id="container_financeiro">
        <?php if ($cargoUsuario != 'vendedor') { ?>
            <div class="container-buttons">
                <button onclick="redirecionarParaFinanceiroHistorico()" class="botao">Histórico de Vendas</button>
                <button onclick="redirecionarParaFinanceiroContas()" class="botao">Contas a Receber</button>
                <button onclick="redirecionarParaGraficos()" class="botao">Gráficos</button>
            </div>
        <?php } ?>

        <div class="container-consultar">
            <?php if ($cargoUsuario == 'vendedor') { ?>
                <div id="comissao_vendedor">
                    <!-- AQUI É O FINANCEIRO, TUDO O QUE ESTÁ NA TELA "FINANCEIRO", ESTILIZAR AQUI !-->
                    <form method="post" action="Financeiro_vendedor.php" id="form-saldos-e-debitos">
                        <div class="botao-financeiro-consultar">
                            <label id="consultar-saldos-e-debitos">Consultar Saldos e Débitos:
                                <select name="saldos" id="saldos" onchange="mostrarIntervaloDeTempo()" required>
                                    <option value="" selected disabled>Escolha uma opção</option>
                                    <option value="Dias">Dias</option>
                                    <option value="Semana">Semana</option>
                                    <option value="Mes">Mês</option>
                                    <option value="Ano">Ano</option>
                                </select>
                            </label>
                        </div>
                        <!-- Div para escolher intervalo de tempo -->
                        <div id="intervalo-de-tempo" class="botao-geral" style="display: none;">
                            <label for="intervalo-saldos">Escolha o intervalo de tempo:</label>
                            <select name="intervalo-saldos" id="intervalo-saldos">
                                <?php
                                echo "<option value=''selected disabled>Escolha uma opção</option>";
                                foreach ($dias_disponiveis as $dia) {
                                    echo "<option value='$dia'>$dia</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Div para escolher o mês (para a opção "Mês") -->
                        <div id="mes-selecionado" class="botao-geral" style="display: none;">
                            <label for="mes">Escolha o mês:</label>
                            <select name="mes" id="mes">
                                <?php
                                echo "<option value=''selected disabled>Escolha uma opção</option>";
                                foreach ($meses_disponiveis as $mes) {
                                    $valor_mes = $mes['valor'];
                                    $nome_mes = $mes['nome'];
                                    echo "<option value='$valor_mes'>$nome_mes</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div id="ano-selecionado" class="botao-geral" style="display: none;">
                            <label for="ano">Escolha o ano:</label>
                            <select name="ano" id="ano">
                                <?php
                                echo "<option value=''selected disabled>Escolha uma opção</option>";
                                foreach ($anos_disponiveis as $ano) {
                                    echo "<option value='$ano'>$ano</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" value="Consultar" class="botao">
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        function mostrarIntervaloDeTempo() {
            var selectSaldos = document.getElementById("saldos");
            var intervaloDeTempo = document.getElementById("intervalo-de-tempo");
            var mesSelecionado = document.getElementById("mes-selecionado");
            var anoSelecionado = document.getElementById("ano-selecionado");

            if (selectSaldos.value === "Dias") {
                intervaloDeTempo.style.display = "block";
                mesSelecionado.style.display = "none";
                anoSelecionado.style.display = "none";
            } else if (selectSaldos.value === "Semana") {
                intervaloDeTempo.style.display = "none";
                mesSelecionado.style.display = "none";
                anoSelecionado.style.display = "none";
            } else if (selectSaldos.value === "Mes") {
                intervaloDeTempo.style.display = "none";
                mesSelecionado.style.display = "block";
                anoSelecionado.style.display = "none";
            } else if (selectSaldos.value === "Ano") {
                intervaloDeTempo.style.display = "none";
                mesSelecionado.style.display = "none";
                anoSelecionado.style.display = "block";
            }
        }
        function redirecionarParaFinanceiroHistorico() {
            window.location.href = "financeiro_historico.php";
        }
        function redirecionarParaFinanceiroContas() {
            window.location.href = "financeiro_contas.php";
        }
        function redirecionarParaGraficos() {
            window.location.href = "Graficos.php";
        }
    </script>
</body>

</html>
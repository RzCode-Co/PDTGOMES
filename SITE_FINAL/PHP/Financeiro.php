<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para recuperar o cargo e CPF do usuário com o ID especificado
    $sql = "SELECT nome, cargo, CPF, arquivo FROM usuarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Dados do usuário
        $cargo = $user['cargo']; // Defina a variável $cargo
        $arquivo = $user['arquivo']; // Defina a variável $arquivo
        $nome = $user['nome']; // Defina a variável $nome

        if ($cargo == 'vendedor') {
            $CPF = $user['CPF'];

            // Consulta SQL para buscar o valor total das vendas do vendedor com base no CPF
            $sql = "SELECT SUM(valor_venda) AS total_vendas FROM vendas WHERE funcionario_vendedor = '$CPF'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $venda = $result->fetch_assoc();
                $totalVendas = $venda['total_vendas'];
                $umPorcento = $totalVendas * 0.01;
            }
        }
    }
}
$dataAtual = date("Y-m-d");
$dataAtual = date("Y-m-d", strtotime("+ 1 days"));
// Data de 7 dias antes da data atual
$dataSeteDiasAtras = date("Y-m-d", strtotime("-7 days"));
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
    <link rel="stylesheet" href="../CSS/financeiro.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
</head>
<body>

    <!-- Menu horizonatl -->
    <nav class="menu_horizontal">
        <ul>
            <li id="logo_menu_horizontal"><a href="../HTML/pagina_incial.html"><img src="../CSS/img/Logo Horizontal.png"alt="logo da empresa"></a></li>

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
    <?php if ($cargo == 'vendedor') { ?>
        <div id="comissao_vendedor">
            <form method="post" action="Financeiro_vendedor.php" id="form-saldos-e-debitos">
                <div class="botao-geral">
                    <label id="consultar-saldos-e-debitos">Consultar Saldos e Débitos por:
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
                <input type="submit" value="Consultar">
            </form>
        </div>
    <?php } ?>

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
        window.location.href ="contas_receber.php";
    }
    function redirecionarParaGraficos() {
        window.location.href = "Graficos.php";
    }
</script>
</body>
</html>
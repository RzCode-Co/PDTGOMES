<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Função para obter o nome do mês em português
function obterNomeMes($ano, $mes) {
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    return strftime('%B', strtotime("{$ano}-{$mes}-01"));
}

// Inicialização de variáveis
$dataAtual = date("Y-m-d");
$dataSeteDiasAtras = date("Y-m-d", strtotime("-7 days"));
$totalValoresGastos = 0;
$dataSelecionada = "";
$valores = array();
$dias_disponiveis = array();
$meses_disponiveis = array();
$anos_disponiveis = array();

// Define a consulta SQL com um valor padrão
$sql = "";

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intervalo = $_POST["saldos"];

    // Verifica a opção selecionada e define as datas de início e fim conforme necessário
    switch ($intervalo) {
        case "Dias":
            if (isset($_POST['data_consulta'])) {
                $dataConsulta = $_POST['data_consulta']; // A data específica selecionada pelo usuário
                $dataSelecionada = $dataConsulta;
                // Consulta SQL para buscar os valores da data selecionada
                $sql = "SELECT * FROM valores WHERE DATE(data_venda) = '$dataConsulta'";
            }
            break;
        case "Semana":
            // Consulta SQL para buscar os valores dentro do intervalo de 7 dias (semana)
            $sql = "SELECT * FROM valores WHERE DATE(data_venda) BETWEEN '$dataSeteDiasAtras' AND '$dataAtual'";
            break;
        case "Mes":
            if (isset($_POST['mes'])) {
                $mesSelecionado = $_POST['mes']; // O mês selecionado pelo usuário (1 a 12)
                $dataSelecionada = date("Y-m-d", strtotime(date("Y-$mesSelecionado-01")));
                // Consulta SQL para buscar os valores do mês selecionado
                $sql = "SELECT * FROM valores WHERE MONTH(data_venda) = '$mesSelecionado'";
            }
            break;
        case "Ano":
            if (isset($_POST['ano'])) {
                $anoSelecionado = $_POST['ano']; // O ano selecionado pelo usuário
                $dataSelecionada = date("Y-m-d", strtotime("$anoSelecionado-01-01"));
                // Consulta SQL para buscar os valores do ano selecionado
                $sql = "SELECT * FROM valores WHERE YEAR(data_venda) = '$anoSelecionado'";
            }
            break;
        default:
            break;
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $valores[] = $row;

            $totalValoresGastos += $row["valor_venda"];
        }
    }
}
$sql_dias_disponiveis = "SELECT DISTINCT DATE(data_venda) AS dia FROM valores WHERE DATE(data_venda) BETWEEN '$dataSeteDiasAtras' AND '$dataAtual'";
$result_dias_disponiveis = $conn->query($sql_dias_disponiveis);
// Verifique se a consulta foi bem-sucedida
if ($result_dias_disponiveis) {
    if ($result_dias_disponiveis->num_rows > 0) {
        while ($row_dias = $result_dias_disponiveis->fetch_assoc()) {
            $dias_disponiveis[] = $row_dias['dia'];
        }
    }
}
// Consulta SQL para obter meses e anos únicos disponíveis
$sql_meses_disponiveis = "SELECT DISTINCT YEAR(data_venda) AS ano, MONTH(data_venda) AS mes FROM valores";
$result_meses_disponiveis = $conn->query($sql_meses_disponiveis);

if ($result_meses_disponiveis->num_rows > 0) {
    while ($row_meses = $result_meses_disponiveis->fetch_assoc()) {
        $ano = $row_meses['ano'];
        $mes = $row_meses['mes'];
        
        $nome_mes = obterNomeMes($ano, $mes);
        
        if (!in_array($mes, $meses_disponiveis)) {
            $meses_disponiveis[] = array('valor' => $mes, 'nome' => $nome_mes);
        }
        
        if (!in_array($ano, $anos_disponiveis)) {
            $anos_disponiveis[] = $ano;
        }
    }
}
// Obtém a data atual
$dataAtual = date("Y-m-d");
// Calcula a data do início da semana atual (segunda-feira)
$primeiroDiaDaSemana = date("Y-m-d", strtotime('last monday', strtotime($dataAtual)));
// Calcula a data do fim da semana atual (domingo)
$ultimoDiaDaSemana = date("Y-m-d", strtotime('next sunday', strtotime($dataAtual)));
// Consulta SQL para buscar os valores dentro da semana atual
$sql = "SELECT * FROM valores WHERE DATE(data_venda) BETWEEN '$primeiroDiaDaSemana' AND '$ultimoDiaDaSemana'";
// Consulta SQL para obter as semanas disponíveis
$sql_semanas_disponiveis = "SELECT DISTINCT WEEK(data_venda) AS semana FROM valores";
$result_semanas_disponiveis = $conn->query($sql_semanas_disponiveis);

if ($result_semanas_disponiveis->num_rows > 0) {
    while ($row_semanas = $result_semanas_disponiveis->fetch_assoc()) {
        $semana = $row_semanas['semana'];
        $semanas_disponiveis[] = $semana;
    }
}

$sql = "SELECT * FROM vendas";

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
</head>
    <style>
        body {
            background-color: gray; /* Define o fundo cinza */
            color: yellow; /* Define a cor do texto como amarelo */
        }

        #conteudo {
            margin: 20px; /* Adiciona margem para separar o conteúdo do cabeçalho e do menu lateral */
        }

        /* Estilização básica para o cabeçalho */
        #cabecalho {
            background-color: black; /* Cor de fundo do cabeçalho (pode ajustar conforme desejado) */
            color: white; /* Cor do texto no cabeçalho (pode ajustar conforme desejado) */
            padding: 10px; /* Espaçamento interno no cabeçalho */
            display: flex; /* Para alinhar os elementos do cabeçalho na horizontal */
            justify-content: space-between; /* Distribui os elementos horizontalmente */
        }

        #usuario-info {
            display: flex; /* Alinha os elementos do usuário na horizontal */
            align-items: center; /* Centraliza verticalmente os elementos do usuário */
        }

        #icone-notificacoes {
            /* Adicione estilos para o ícone de notificações, como tamanho, margem, etc. */
        }

        /* Estilização para o menu lateral */
        #menu-lateral {
            background-color: black; /* Cor de fundo do menu (pode ajustar conforme desejado) */
        }

        #menu-lateral ul {
            list-style-type: none; /* Remove marcadores de lista */
            padding: 0; /* Remove o preenchimento padrão da lista */
        }

        #menu-lateral ul li {
            margin: 0; /* Remove a margem padrão dos itens da lista */
        }

        #menu-lateral ul li a {
            display: block; /* Transforma os links em blocos para preencher o espaço disponível */
            padding: 10px 20px; /* Espaçamento interno nos links */
            color: white; /* Cor do texto dos links */
            text-decoration: none; /* Remove sublinhado dos links */
        }

        #menu-lateral ul li a:hover {
            background-color: gray; /* Cor de fundo quando o mouse passa por cima */
        }
    </style>
    <body>
    <div id="cabecalho">
        <div id="usuario-info">
            <img src="<?php echo $fotoUsuario; ?>" alt="Foto do Usuário">
            <p><?php echo $nomeUsuario; ?></p>
            <p><?php echo $cargoUsuario; ?></p>
        </div>
        <!-- Ícone de notificações -->
        <div id="icone-notificacoes">
            <img src="caminho-para-o-icone.png" alt="Ícone de Notificações">
        </div>
    </div>
    <!-- Seu menu lateral -->
    <div id="menu-lateral">
        <ul>
            <li><a href="inicio.html">Inicio</a></li>
            <li><a href="Venda.html">Venda</a></li>
            <li><a href="Financeiro.php">Financeiro</a></li>
            <li><a href="Debitos.html">Debitos</a></li>
            <li><a href="Notificações.php">Notificações</a></li>
            <li><a href="estoque.html">Estoque</a></li>
            <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
        </ul>
    </div>

    <div id="historico-de-vendas"style="display: none;">
            <h1>Historico de vendas</h1>
            <table>
                <tr>
                    <th>Funcionário</th>
                    <th>Comprador</th>
                    <th>Peça</th>
                    <th>Forma de Pagamento</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                </tr>
                <?php
                foreach ($historico as $venda) {
                    echo "<tr>";
                    echo "<td>" . $venda["funcionario_vendedor"] . "</td>";
                    echo "<td>" . $venda["nome_comprador"] . "</td>";
                    echo "<td>" . $venda["nome_peca"] . "</td>";
                    echo "<td>" . $venda["forma_pagamento"] . "</td>";
                    echo "<td>" . $venda["valor_venda"] . "</td>";
                    echo "<td>" . $venda["quantidade"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
    </div>

    <div id="contas-a-receber"style="display: none;">
            <h1>Contas a Receber</h1>
            <table>
                <tr>
                    <th>Comprador</th>
                    <th>Número de Parcelas</th>
                    <th>Data de Venda</th>
                </tr>
                <?php
                foreach ($historico as $conta) {
                    if ($conta["numero_parcelas"] > 0) { // Verifica se o numero_parcelas é maior que 0
                        echo "<tr>";
                        echo "<td>" . $conta["nome_comprador"] . "</td>";
                        echo "<td>" . $conta["numero_parcelas"] . "x</td>"; // Exibe o número de parcelas com um "x" após o número
                        echo "<td>" . $conta["data_venda"] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>

    <button onclick="mostrarHistorico()">Mostrar Histórico</button>
    <button onclick="mostrarContas()">Mostrar Contas a Receber</button>
    <a href="Processar_saldos.php" class="botao-redirecionamento">Mostrar Saldos e Débitos</a>
    <form method="post" action="Consulta_saldos.php">
        <label>Consultar Saldos e Débitos por:
            <select name="saldos" id="saldos" onchange="mostrarIntervaloDeTempo()">
                <option value="" selected disabled>Escolha uma opção</option>
                <option value="Dias">Dias</option>
                <option value="Semana">Semana</option>
                <option value="Mes">Mês</option>
                <option value="Ano">Ano</option>
            </select>
        </label>

        <!-- Div para escolher intervalo de tempo -->
        <div id="intervalo-de-tempo" style="display: none;">
            <label for="intervalo-saldos">Escolha o intervalo de tempo:</label>
            <select name="intervalo-saldos" id="intervalo-saldos">
                <?php
                if (empty($dias_disponiveis)) {
                    echo "<option value='' selected disabled>Nenhum dado disponível para o período selecionado</option>";
                } else {
                    echo "<option value='' selected disabled>Escolha uma opção</option>";
                    foreach ($dias_disponiveis as $dia) {
                        // Aqui, cada $dia já deve estar no formato 'Y-m-d'
                        echo "<option value='$dia'>$dia</option>";
                    }
                }
                ?>
            </select>
        </div>

        <!-- Div para escolher o mês (para a opção "Mês") -->
        <div id="mes-selecionado" style="display: none;">
            <label for="mes">Escolha o mês:</label>
            <select name="mes" id="mes">
                <?php
                echo "<option value='' selected disabled>Escolha uma opção</option>";
                foreach ($meses_disponiveis as $mes) {
                    $valor_mes = $mes['valor'];
                    $nome_mes = $mes['nome'];
                    echo "<option value='$valor_mes'>$nome_mes</option>";
                }
                ?>
            </select>
        </div>
        <div id="ano-selecionado" style="display: none;">
            <label for="ano">Escolha o ano:</label>
            <select name="ano" id="ano">
                <?php
                echo "<option value='' selected disabled>Escolha uma opção</option>";
                foreach ($anos_disponiveis as $ano) {
                    echo "<option value='$ano'>$ano</option>";
                }
                ?>
            </select>
        </div>
        <input type="submit" value="Consultar">
    </form>
    <div id="tabela-gastos">
        <table>
            <tr>
                <th>Índice</th>
                <th>Valor da Venda</th>
                <th>Valor do Serviço</th>
                <th>Preço Total Geral</th>
                <th>Valor do Débito</th>
                <th>Data da Venda</th>
            </tr>
            <?php
            $indice = 1;
            foreach ($valores as $venda) {
                echo "<tr>";
                echo "<td>" . $indice . "</td>";
                echo "<td>" . $venda["valor_venda"] . "</td>";
                echo "<td>" . $venda["valor_servico"] . "</td>";
                echo "<td>" . $venda["preco_total_geral"] . "</td>";
                echo "<td>" . $venda["valor_debito"] . "</td>";
                echo "<td>" . $venda["data_venda"] . "</td>";
                echo "</tr>";
                $indice++;
            }
            ?>
        </table>
    </div>
</body>
<script>
    function mostrarHistorico() {
        var tabelaGastos = document.getElementById("tabela-gastos");
        tabelaGastos.style.display = "none";
        document.getElementById("historico-de-vendas").style.display = "block";
        document.getElementById("contas-a-receber").style.display = "none";
    }

    function mostrarContas() {
        var tabelaGastos = document.getElementById("tabela-gastos");
        tabelaGastos.style.display = "none";
        document.getElementById("historico-de-vendas").style.display = "none";
        document.getElementById("contas-a-receber").style.display = "block";
    }

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
</script>
</html>

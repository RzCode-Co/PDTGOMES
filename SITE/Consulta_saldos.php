<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Função para redirecionar para a página de origem
function redirectToReferer() {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Verifica se a opção de intervalo foi selecionada
if (!isset($_GET['intervalo'])) {
    redirectToReferer();
}

// Recupera a opção selecionada pelo usuário
$intervalo = $_GET['intervalo'];

// Data atual
$dataAtual = date("Y-m-d");

// Data de 7 dias antes da data atual
$dataSeteDiasAtras = date("Y-m-d", strtotime("-7 days"));

// Verifica a opção selecionada e define as datas de início e fim conforme necessário
switch ($intervalo) {
    case "Dias":
        if (isset($_GET['data_consulta'])) {
            $dataConsulta = $_GET['data_consulta']; // A data específica selecionada pelo usuário
            // Consulta SQL para buscar os valores da data selecionada
            $sql = "SELECT * FROM valores WHERE DATE(data_venda) = '$dataConsulta'";
        } else {
            redirectToReferer();
        }
        break;
    case "Semana":
        // Consulta SQL para buscar os valores dentro do intervalo de 7 dias (semana)
        $sql = "SELECT * FROM valores WHERE DATE(data_venda) BETWEEN '$dataSeteDiasAtras' AND '$dataAtual'";
        break;
    case "Mes":
        if (isset($_GET['mes'])) {
            $mesSelecionado = $_GET['mes']; // O mês selecionado pelo usuário (1 a 12)
            // Consulta SQL para buscar os valores do mês selecionado
            $sql = "SELECT * FROM valores WHERE MONTH(data_venda) = '$mesSelecionado'";
        } else {
            redirectToReferer();
        }
        break;
    case "Ano":
        if (isset($_GET['ano'])) {
            $anoSelecionado = $_GET['ano']; // O ano selecionado pelo usuário
            // Consulta SQL para buscar os valores do ano selecionado
            $sql = "SELECT * FROM valores WHERE YEAR(data_venda) = '$anoSelecionado'";
        } else {
            redirectToReferer();
        }
        break;
    default:
        redirectToReferer();
        break;
}

$result = $conn->query($sql);

// Array para armazenar os valores
$valores = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $valores[] = $row;
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
        <form action="consulta_saldos_tempo.php" method="post">
            <label>Consultar Saldos e Débitos por:
                <select name="saldos" id="saldos" onchange="mostrarIntervaloDeTempo()">
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
                    foreach ($dias_disponiveis as $dia) {
                        echo "<option value='$dia'>$dia</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Div para escolher o mês (para a opção "Mês") -->
            <div id="mes-selecionado" style="display: none;">
                <label for="mes">Escolha o mês:</label>
                <select name="mes" id="mes">
                    <?php
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
                    foreach ($anos_disponiveis as $ano) {
                        echo "<option value='$ano'>$ano</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Consultar</button>
        </form>
    </body>
    <script>
        function mostrarHistorico() {
            document.getElementById("historico-de-vendas").style.display = "block";
            document.getElementById("contas-a-receber").style.display = "none";
        }

        function mostrarContas() {
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
<?php
require_once "config.php"; // Arquivo de configuração do banco de dados
// Consulta SQL para buscar todas as vendas na tabela "vendas"
$sql = "SELECT * FROM vendas";

$result = $conn->query($sql);

// Array para armazenar o histórico de vendas
$historico = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}
// Data atual
$dataAtual = date("Y-m-d");
$dataAtual = date("Y-m-d", strtotime("+ 1 days"));
// Data de 7 dias antes da data atual
$dataSeteDiasAtras = date("Y-m-d", strtotime("-7 days"));
if ($dataSeteDiasAtras === NULL){
    $dataSeteDiasAtras = NULL;
}
// Consulta SQL para buscar os dias únicos da coluna data_venda na tabela valores dentro do intervalo de datas
$sql_dias_disponiveis = "SELECT DISTINCT DATE(data_venda) AS dia FROM valores WHERE DATE(data_venda) BETWEEN '$dataSeteDiasAtras' AND '$dataAtual'";
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
// Consulta SQL para calcular os valores de total_ganho, total_gasto e total_lucro (conforme mostrado no exemplo anterior)
$sql_calcula_valores = "SELECT 
    SUM(valor_venda) AS soma_valor_venda,
    SUM(valor_servico) AS soma_valor_servico,
    SUM(preco_total_geral) AS soma_preco_total_geral,
    SUM(valor_debito) AS soma_valor_debito
FROM valores";

$result_calcula_valores = $conn->query($sql_calcula_valores);

if ($result_calcula_valores) {
    if ($result_calcula_valores->num_rows > 0) {
        $row = $result_calcula_valores->fetch_assoc();

        // Soma dos valores
        $total_ganho = $row["soma_valor_venda"] + $row["soma_valor_servico"] + $row["soma_preco_total_geral"];
        $total_gasto = $row["soma_valor_debito"];

        // Cálculo do lucro
        $total_lucro = $total_ganho - $total_gasto;

        // Consulta SQL para inserir ou atualizar os valores na tabela saldos
        $sql_insere_atualiza_saldos = "INSERT INTO saldos (id, total_ganho, total_lucro, total_gasto) 
            VALUES (1, '$total_ganho', '$total_lucro', '$total_gasto') 
            ON DUPLICATE KEY UPDATE 
            total_ganho = VALUES(total_ganho), 
            total_lucro = VALUES(total_lucro), 
            total_gasto = VALUES(total_gasto)";
    } else {
        echo "Nenhum resultado encontrado na consulta para calcular valores.";
    }
} else {
    echo "Erro na consulta SQL para calcular valores: " . $conn->error;
}

// Consulta SQL para obter os valores da tabela saldos
$sql_saldos = "SELECT * FROM saldos WHERE id = 1";
$result_saldos = $conn->query($sql_saldos);

// Inicializar um array para armazenar os valores
$valores_saldos = array();

if ($result_saldos) {
    if ($result_saldos->num_rows > 0) {
        $saldos_row = $result_saldos->fetch_assoc();
        $valores_saldos['total_ganho'] = $saldos_row['total_ganho'];
        $valores_saldos['total_gasto'] = $saldos_row['total_gasto'];
        $valores_saldos['total_lucro'] = $saldos_row['total_lucro'];
    }
}
// Inicialize as variáveis $totalVenda e $totalDebito com 0
$preco_total_geral = 0;
$totalDebito = 0;

// Função para obter o nome do mês em português
function obterNomeMes($ano, $mes) {
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    return strftime('%B', strtotime("{$ano}-{$mes}-01"));
}

// Obter a data atual
$dataAtual = date("Y-m-d");
// Definir o fuso horário para evitar problemas de diferenças de data
date_default_timezone_set('America/Sao_Paulo'); // Substitua 'America/Sao_Paulo' pelo fuso horário desejado
// Calcular a data do domingo da semana atual
$dataInicioSemana = date('Y-m-d', strtotime("last Sunday", strtotime($dataAtual)));
// Calcular a data do sábado da semana atual
$dataFimSemana = date('Y-m-d', strtotime("next Saturday", strtotime($dataInicioSemana)));
$totalValoresGastos = 0;
$dataSelecionada = "";
$vendas = array();

// Define a consulta SQL com um valor padrão
$sql = "";

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intervalo = $_POST["saldos"];

    // Defina consultas SQL diferentes com base no intervalo selecionado.
    if ($intervalo === "Dias") {
        $dataConsulta = $_POST['intervalo-saldos'];
        $sql = "SELECT * FROM valores WHERE DATE(data_venda) = '$dataConsulta'";
        $dataSelecionada = $dataConsulta;
    } elseif ($intervalo === "Semana") {
        $sql = "SELECT * FROM valores WHERE DATE(data_venda) BETWEEN '$dataInicioSemana' AND '$dataFimSemana'";
    } elseif ($intervalo === "Mes") {
        $mesSelecionado = $_POST['mes'];
        $sql = "SELECT * FROM valores WHERE MONTH(data_venda) = '$mesSelecionado'";
        $dataSelecionada = obterNomeMes(date("Y"), $mesSelecionado);
    } elseif ($intervalo === "Ano") {
        $anoSelecionado = $_POST['ano'];
        $sql = "SELECT * FROM valores WHERE YEAR(data_venda) = '$anoSelecionado'";
        $dataSelecionada = $anoSelecionado;
    }
    
    // Execute a consulta SQL correspondente e obtenha os resultados
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Processar e armazenar os resultados no array $historico
        while ($row = $result->fetch_assoc()) {
            $vendas[] = $row;

            // Calcule os totais de vendas e débitos
            $preco_total_geral += $row["preco_total_geral"];
            $totalDebito += $row["valor_debito"];
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
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="Venda.html">Venda</a></li>
                <li><a href="Financeiro.php">Financeiro</a></li>
                <li><a href="Debitos.php">Debitos</a></li>
                <li><a href="Notificações.php">Notificações</a></li>
                <li><a href="Estoque.php">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>

        <div id="historico-de-vendas"style="display: none;">
            <h1>Historico de vendas</h1>
            <table>
            <?php
                // Defina o número de itens por página
                $itemsPerPage = 10;

                // Obtenha a página atual a partir dos parâmetros da URL
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                // Calcule o índice de início e fim para os itens da página atual
                $startIndex = ($page - 1) * $itemsPerPage;
                $endIndex = $startIndex + $itemsPerPage;

                // Crie a tabela para exibir os itens da página atual (histórico de vendas)
                echo '<table>';
                echo '<tr>
                        <th>Funcionário</th>
                        <th>Comprador</th>
                        <th>Peça</th>
                        <th>Forma de Pagamento</th>
                        <th>Valor</th>
                        <th>Quantidade</th>
                    </tr>';

                $displayedCount = 0; // Contador para controlar a exibição dos itens

                foreach ($historico as $venda) {
                    if ($displayedCount >= $startIndex && $displayedCount < $endIndex) {
                        echo '<tr>';
                        echo '<td>' . $venda['funcionario_vendedor'] . '</td>';
                        echo '<td>' . $venda['nome_comprador'] . '</td>';
                        echo '<td>' . $venda['nome_peca'] . '</td>';
                        echo '<td>' . $venda['forma_pagamento'] . '</td>';
                        echo '<td>' . $venda['valor_venda'] . '</td>';
                        echo '<td>' . $venda['quantidade'] . '</td>';
                        echo '</tr>';
                    }
                    $displayedCount++;
                }

                echo '</table>';

                // Adicione os links de paginação
                echo '<div id="pagination">';
                $totalItems = count($historico);
                $totalPages = ceil($totalItems / $itemsPerPage);

                $displayedPages = 5; // Número de páginas a serem exibidas na páginação

                if ($totalPages > 1) {
                    $currentPage = $page;
                    $firstPage = max(1, $currentPage - floor($displayedPages / 2));
                    $lastPage = min($totalPages, $firstPage + $displayedPages - 1);

                    for ($i = $firstPage; $i <= $lastPage; $i++) {
                        echo '<a href="financeiro.php?page=' . $i . '">' . $i . '</a> ';
                        
                    }

                    if ($lastPage < $totalPages) {
                        echo '<a href="financeiro.php?page=' . ($lastPage + 1) . '">...</a> ';
                    }

                    if ($currentPage < $totalPages - floor($displayedPages / 2)) {
                        echo '<a href="financeiro.php?page=' . $totalPages . '">' . $totalPages . '</a> ';
                    }
                }

                echo '</div>';
            ?>
            </table>
        </div>
        <div id="contas-a-receber">
            <h1>Contas a Receber</h1>
            <table>
                <?php
                    // Defina o número de itens por página
                    $itemsPerPage = 10;

                    // Obtenha a página atual a partir dos parâmetros da URL
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Calcule o índice de início e fim para os itens da página atual
                    $startIndex = ($page - 1) * $itemsPerPage;
                    $endIndex = $startIndex + $itemsPerPage;

                    // Crie a tabela para exibir os itens da página atual (contas a receber)
                    echo '<table>';
                    echo '<tr>
                            <th>Comprador</th>
                            <th>Número de Parcelas</th>
                            <th>Data de Venda</th>
                        </tr>';

                    foreach ($historico as $conta) {
                        if ($conta["numero_parcelas"] > 0) {
                            echo '<tr>';
                            echo '<td>' . $conta['nome_comprador'] . '</td>';
                            echo '<td>' . $conta['numero_parcelas'] . 'x</td>';
                            echo '<td>' . $conta['data_venda'] . '</td>';
                            echo '</tr>';
                        }
                    }

                    echo '</table>';

                    // Adicione os links de paginação
                    echo '<div id="pagination">';
                    $totalItems = count($historico);
                    $totalPages = ceil($totalItems / $itemsPerPage);

                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a href="contas_receber.php?page=' . $i . '">' . $i . '</a> ';
                    }

                    echo '</div>';
                    ?>
            </table>
        </div>

        <button onclick="mostrarHistoricoDeVendas()">Mostrar Histórico de Vendas</button>
        <button onclick="mostrarContasAReceber()">Mostrar Contas a Receber</button>
        <button onclick="mostrarSaldos()">Mostrar Saldos e Débitos</button>
        <button onclick="mostrarGastos()">Mostrar gastos</button>
        <form method="post" action="Financeiro.php">
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
                    echo "<option value=''selected disabled>Escolha uma opção</option>";
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
                    echo "<option value=''selected disabled>Escolha uma opção</option>";
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
                    echo "<option value=''selected disabled>Escolha uma opção</option>";
                    foreach ($anos_disponiveis as $ano) {
                        echo "<option value='$ano'>$ano</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Consultar">
        </form>
                <!-- Tabela para mostrar os valores -->
        <div id="valores" style="display: none;">
            <h1>Valores Financeiros</h1>
            <table>
                <tr>
                    <th>Total Ganho</th>
                    <th>Total Gasto</th>
                    <th>Total Lucro</th>
                </tr>
                <tr>
                    <td><?php echo $valores_saldos['total_ganho']; ?></td>
                    <td><?php echo $valores_saldos['total_gasto']; ?></td>
                    <td><?php echo $valores_saldos['total_lucro']; ?></td>
                </tr>
            </table>
        </div>
        <div id="tabelaGastos" style="display: none;">
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
            foreach ($vendas as $venda) {
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
        <div id="grafico-saldos" style="display: none;">
            <canvas id="grafico" width="200" height="60"></canvas>
                <script>
                    // Acesse o contexto do canvas
                    var ctx = document.getElementById("grafico").getContext("2d");

                    // Variáveis do PHP para o gráfico
                    var totalGanho = <?php echo $total_ganho; ?>;
                    var totalGasto = <?php echo $total_gasto; ?>;
                    var totalLucro = <?php echo $total_lucro; ?>;

                    // Crie um gráfico em barra
                    var grafico = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ["Total Ganho", "Total Gasto", "Total Lucro"],
                            datasets: [{
                                label: 'Valores',
                                data: [totalGanho, totalGasto, totalLucro],
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                ],
                                borderColor: [
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
        </div>
        <div id="grafico-vendas" style="display: block;">
            <canvas id="graficoVendas" width="400" height="200"></canvas>
                <script>
                    var ctx = document.getElementById('graficoVendas').getContext('2d');

                    // Use os totais calculados no PHP para criar o gráfico
                    var preco_total_geral = <?php echo $preco_total_geral ?? 0; ?>; // Defina um valor padrão se a variável não estiver definida
                    var totalDebito = <?php echo $totalDebito ?? 0; ?>; // Defina um valor padrão se a variável não estiver definida
                    var lucro = preco_total_geral - totalDebito;

                    var data = {
                        labels: ['Ganhos', 'Gastos', 'Lucro'],
                        datasets: [{
                            data: [preco_total_geral, totalDebito, lucro],
                            backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)'],
                            borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                            borderWidth: 1
                        }]
                    };

                    var options = {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    };

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: data,
                        options: options
                    });
                </script>
            </div>
    </body>
    <script>
        function mostrarGastos() {
            document.getElementById("historico-de-vendas").style.display = "none";
            document.getElementById("contas-a-receber").style.display = "none";
            document.getElementById("tabelaGastos").style.display = "block";
            document.getElementById("grafico-saldos").style.display = "none";
            document.getElementById("grafico-vendas").style.display = "block";
            document.getElementById("valores").style.display = "none";
        }
        function mostrarSaldos() {
            document.getElementById("historico-de-vendas").style.display = "none";
            document.getElementById("contas-a-receber").style.display = "none";
            document.getElementById("tabelaGastos").style.display = "none";
            document.getElementById("grafico-saldos").style.display = "block";
            document.getElementById("grafico-vendas").style.display = "none";
            document.getElementById("valores").style.display = "block";
        }
        function mostrarHistoricoDeVendas() {
            document.getElementById("historico-de-vendas").style.display = "block";
            document.getElementById("contas-a-receber").style.display = "none";
            document.getElementById("tabelaGastos").style.display = "none";
            document.getElementById("grafico-saldos").style.display = "none";
            document.getElementById("grafico-vendas").style.display = "none";
            document.getElementById("valores").style.display = "none";
            document.location.href = "financeiro_historico.php";
        }

        function mostrarContasAReceber() {
            document.getElementById("historico-de-vendas").style.display = "block";
            document.getElementById("contas-a-receber").style.display = "none";
            document.getElementById("tabelaGastos").style.display = "none";
            document.getElementById("grafico-saldos").style.display = "none";
            document.getElementById("grafico-vendas").style.display = "none";
            document.getElementById("valores").style.display = "none";
            document.location.href = "contas_receber.php";
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
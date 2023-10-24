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
                <li><a href="../PHP/inicio.php">Inicio</a></li>
                <li><a href="Venda.html">Venda</a></li>
                <li><a href="Financeiro.html">Financeiro</a></li>
                <li><a href="../PHP/Debitos.php">Debitos</a></li>
                <li><a href="../PHP/Notificações.php">Notificações</a></li>
                <li><a href="../PHP/Estoque.php">Estoque</a></li>
                <li><a href="../PHP/Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>

        <div id="historico-de-vendas">
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
                        echo '<a href="financeiro_historico.php?page=' . $i . '">' . $i . '</a> ';
                        
                    }

                    if ($lastPage < $totalPages) {
                        echo '<a href="financeiro_historico.php?page=' . ($lastPage + 1) . '">...</a> ';
                    }

                    if ($currentPage < $totalPages - floor($displayedPages / 2)) {
                        echo '<a href="financeiro_historico.php?page=' . $totalPages . '">' . $totalPages . '</a> ';
                    }
                }

                echo '</div>';
            ?>
            </table>
        </div>
        <div id="contas-a-receber"style="display: none;">
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
                        echo '<a href="financeiro_historico.php?page=' . $i . '">' . $i . '</a> ';
                    }

                    echo '</div>';
                    ?>
            </table>
        </div>

        <button onclick="mostrarHistoricoDeVendas()">Mostrar Histórico de Vendas</button>
        <button onclick="mostrarContasAReceber()">Mostrar Contas a Receber</button>
    </body>
    <script>
        function mostrarHistoricoDeVendas() {
            document.getElementById("historico-de-vendas").style.display = "block";
            document.getElementById("contas-a-receber").style.display = "none";
        }

        function mostrarContasAReceber() {
            document.getElementById("historico-de-vendas").style.display = "none";
            document.getElementById("contas-a-receber").style.display = "block";
        }
    </script>
</html>
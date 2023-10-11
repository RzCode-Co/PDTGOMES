<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

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
        
        if ($conn->query($sql_insere_atualiza_saldos) === TRUE) {
            echo "Valores de saldo inseridos/atualizados com sucesso.";
        } else {
            echo "Erro ao inserir/atualizar valores de saldo: " . $conn->error;
        }
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
                    <option value=''selected disabled>Escolha uma opção</option>
                    <option value="Dias">Dias</option>
                    <option value="Semana">Semana</option>
                    <option value="Mes">Mês</option>
                    <option value="Ano">Ano</option>
                </select>
            </label>
            <!-- Div para escolher intervalo de tempo -->
            <div id="intervalo-de-tempo" style="display: none;">
                <label for="intervalo-saldos">Escolha a data:</label>
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
            <!-- Div para escolher o ano (para a opção "Ano") -->
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
        <div id="valores">
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
    </body>
    <script>
        function mostrarHistorico() {
            document.getElementById("historico-de-vendas").style.display = "block";
            document.getElementById("contas-a-receber").style.display = "none";
            document.getElementById("valores").style.display = "none"; // Oculta a div de valores
        }

        function mostrarContas() {
            document.getElementById("historico-de-vendas").style.display = "none";
            document.getElementById("contas-a-receber").style.display = "block";
            document.getElementById("valores").style.display = "none"; // Oculta a div de valores
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

<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

$os = null; // Inicialize a variável $os como nula

if (isset($_GET['ordem_servico_id'])) {
    // Se o ID da ordem de serviço estiver definido na URL, realize a pesquisa pela ID
    $ordem_servico_id = (int) $_GET['ordem_servico_id']; // Certifique-se de que é um número inteiro

    // Consulta SQL para recuperar os detalhes de uma ordem de serviço específica
    $sqlDetalhesOS = "SELECT * FROM ordem_servico_completa WHERE ordem_servico_id = $ordem_servico_id";
    $resultDetalhesOS = $conn->query($sqlDetalhesOS);

    if ($resultDetalhesOS->num_rows > 0) {
        // Exibir os detalhes da ordem de serviço encontrada
        $os = $resultDetalhesOS->fetch_assoc();
    }
}

// Defina o número máximo de registros por página
$registrosPorPagina = 5;

// Recupere o número da página atual a partir da consulta GET
if (isset($_GET['pagina'])) {
    $paginaAtual = $_GET['pagina'];
} else {
    $paginaAtual = 1;
}

// Calcule o deslocamento a partir da página atual
$deslocamento = ($paginaAtual - 1) * $registrosPorPagina;

// Consulta SQL para recuperar as ordens de serviço em andamento com base no deslocamento
$sqlEmAndamento = "SELECT * FROM ordem_servico_completa WHERE status = 'Em andamento' LIMIT $registrosPorPagina OFFSET $deslocamento";
$resultEmAndamento = $conn->query($sqlEmAndamento);

if ($resultEmAndamento->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $resultEmAndamento->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
}

// Calcule o número total de registros para as ordens de serviço em andamento
$sqlTotalRegistrosEmAndamento = "SELECT COUNT(*) AS total FROM ordem_servico_completa WHERE status = 'Em andamento'";
$resultTotalRegistrosEmAndamento = $conn->query($sqlTotalRegistrosEmAndamento);
$totalRegistrosEmAndamento = $resultTotalRegistrosEmAndamento->fetch_assoc()['total'];

// Calcule o número total de páginas com base no total de registros das ordens de serviço em andamento
$totalPaginas = ceil($totalRegistrosEmAndamento / $registrosPorPagina);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ordens em Andamento</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        .paginacao {
            margin-top: 20px;
            text-align: center;
        }

        .paginacao a {
            padding: 5px 10px;
            background-color: black;
            color: white;
            text-decoration: none;
            margin: 5px;
        }

        .paginacao a:hover {
            background-color: gray;
        }
    </style>
</head>
<body>
<h2>Ordens em Andamento</h2>
<form method="GET">
    <label>Pesquisar por ID da Ordem de Serviço:</label>
    <input type="number" name="ordem_servico_id">
    <input type="submit" value="Pesquisar">
</form>

<?php
if (!empty($os)) {
    // Se a ordem de serviço da pesquisa por ID for encontrada, exiba os detalhes dela
    echo "<h3>Ordem de Serviço ID: {$os['ordem_servico_id']}</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><td>{$os['ordem_servico_id']}</td></tr>";
    echo "<tr><th>Cliente</th><td>{$os['cliente_nome']}</td></tr>";
    echo "<tr><th>Veículo</th><td>{$os['veiculo_nome']}</td></tr>";
    echo "<tr><th>Placa do Veículo</th><td>{$os['veiculo_placa']}</td></tr>";
    echo "<tr><th>Data de Abertura</th><td>{$os['data_abertura']}</td></tr>";
    echo "<tr><th>Status</th><td>{$os['status']}</td></tr>";
    echo "<tr><th>Ações</th><td>";

    // Botão para Concluída
    echo "<div style='display: inline-block;'>";
    echo "<form method='POST' action='atualizar_status.php'>";
    echo "<input type='hidden' name='ordem_servico_id' value='{$os['ordem_servico_id']}'>";
    echo "<input type='hidden' name='novo_status' value='Concluída'>";
    echo "<input type='submit' value='Concluída'>";
    echo "</form>";
    echo "</div>";

    echo "</td></tr>";
    echo "</table>";

    // Quebra de linha para exibir em colunas separadas
    echo "<br>";

    // Exiba a lista de serviços prestados
    echo "<h3>Serviços Prestados</h3>";
    echo "<table>";
    echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";

    // Aqui você deve percorrer o array de serviços específico para esta ordem de serviço
    $ordem_servico_id = $os['ordem_servico_id'];
    $sql_servicos = "SELECT * FROM servicos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
    $result_servicos = $conn->query($sql_servicos);

    if ($result_servicos->num_rows > 0) {
        while ($servico = $result_servicos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$servico['servico_nome']}</td>";
            echo "<td>{$servico['tecnico_responsavel']}</td>";
            echo "<td>{$servico['valor_servico']}</td>";
            echo "</tr>";
        }
    }

    echo "</table>";

    // Exibir a lista de produtos
    $ordem_servico_id = $os['ordem_servico_id'];
    $sql_produtos = "SELECT * FROM produtos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
    $result_produtos = $conn->query($sql_produtos);

    if ($result_produtos->num_rows > 0) {
        // Exibir a lista de produtos
        echo "<h3>Produtos Vendidos</h3>";
        echo "<table>";
        echo "<tr><th>Código do Produto</th><th>Produto</th><th>Referência</th><th>Tipo</th><th>Quantidade</th><th>Preço Unitário</th></tr>";

        while ($produto = $result_produtos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$produto['codigo_produto']}</td>";
            echo "<td>{$produto['produto']}</td>";
            echo "<td>{$produto['referencia']}</td>";
            echo "<td>{$produto['tipo']}</td>";
            echo "<td>{$produto['quantidade']}</td>";
            echo "<td>{$produto['preco_produto']}</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    $sql = "SELECT pagamento_previo, forma_pagamento FROM ordem_servico WHERE id = $ordem_servico_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pagamentoPrevio = $row['pagamento_previo'];
        $formaPagamento = $row['forma_pagamento'];

        if ($pagamentoPrevio === '1') {
            echo "Produtos já foram pagos previamente no caixa.<br> Forma de pagamento: $formaPagamento";
        } else {
            echo "Produtos devem ser pagos no caixa.";
        }
    } else {
        echo "Produto não encontrado no banco de dados.";
    }

    // Exiba as observações
    echo "<h3>Observações do Vendedor</h3>";
    $observacoes_vendedor = $os['observacoes_vendedor'];
    if (!empty($observacoes_vendedor)) {
        echo "<p>{$observacoes_vendedor}</p>";
    } else {
        echo "<p>Nenhuma observação foi inserida.</p>";
    }

    // Exibir o valor total
    echo "<p>Valor Total: {$os['preco_total_geral']}</p>";
} else if (!empty($os_details)) {
    // Se não foi encontrada uma ordem de serviço específica, mas há ordens em andamento, liste-as
    foreach ($os_details as $os) {
        echo "<h3>Ordem de Serviço ID: {$os['ordem_servico_id']}</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><td>{$os['ordem_servico_id']}</td></tr>";
        echo "<tr><th>Cliente</th><td>{$os['cliente_nome']}</td></tr>";
        echo "<tr><th>Veículo</th><td>{$os['veiculo_nome']}</td></tr>";
        echo "<tr><th>Placa do Veículo</th><td>{$os['veiculo_placa']}</td></tr>";
        echo "<tr><th>Data de Abertura</th><td>{$os['data_abertura']}</td></tr>";
        echo "<tr><th>Status</th><td>{$os['status']}</td></tr>";
        echo "<tr><th>Ações</th><td>";

        // Botão para Concluída
        echo "<div style='display: inline-block;'>";
        echo "<form method='POST' action='atualizar_status.php'>";
        echo "<input type='hidden' name='ordem_servico_id' value='{$os['ordem_servico_id']}'>";
        echo "<input type='hidden' name='novo_status' value='Concluída'>";
        echo "<input type='submit' value='Concluída'>";
        echo "</form>";
        echo "</div>";

        echo "</td></tr>";
        echo "</table>";

        // Quebra de linha para exibir em colunas separadas
        echo "<br>";

        // Exiba a lista de serviços prestados
        echo "<h3>Serviços Prestados</h3>";
        echo "<table>";
        echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";

        $ordem_servico_id = $os['ordem_servico_id'];
        $sql_servicos = "SELECT * FROM servicos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
        $result_servicos = $conn->query($sql_servicos);

        if ($result_servicos->num_rows > 0) {
            while ($servico = $result_servicos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$servico['servico_nome']}</td>";
                echo "<td>{$servico['tecnico_responsavel']}</td>";
                echo "<td>{$servico['valor_servico']}</td>";
                echo "</tr>";
            }
        }

        echo "</table>";

        // Exibir a lista de produtos
        $ordem_servico_id = $os['ordem_servico_id'];
        $sql_produtos = "SELECT * FROM produtos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
        $result_produtos = $conn->query($sql_produtos);

        if ($result_produtos->num_rows > 0) {
            // Exibir a lista de produtos
            echo "<h3>Produtos Vendidos</h3>";
            echo "<table>";
            echo "<tr><th>Código do Produto</th><th>Produto</th><th>Referência</th><th>Tipo</th><th>Quantidade</th><th>Preço Unitário</th></tr>";

            while ($produto = $result_produtos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$produto['codigo_produto']}</td>";
                echo "<td>{$produto['produto']}</td>";
                echo "<td>{$produto['referencia']}</td>";
                echo "<td>{$produto['tipo']}</td>";
                echo "<td>{$produto['quantidade']}</td>";
                echo "<td>{$produto['preco_produto']}</td>";
                echo "</tr>";
            }

            echo "</table>";
        }

        $sql = "SELECT pagamento_previo, forma_pagamento FROM ordem_servico WHERE id = $ordem_servico_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pagamentoPrevio = $row['pagamento_previo'];
            $formaPagamento = $row['forma_pagamento'];

            if ($pagamentoPrevio === '1') {
                echo "Produtos já foram pagos previamente no caixa.<br> Forma de pagamento: $formaPagamento";
            } else {
                echo "Produtos devem ser pagos no caixa.";
            }
        } else {
            echo "Produto não encontrado no banco de dados.";
        }

        // Exiba as observações
        echo "<h3>Observações do Vendedor</h3>";
        $observacoes_vendedor = $os['observacoes_vendedor'];
        if (!empty($observacoes_vendedor)) {
            echo "<p>{$observacoes_vendedor}</p>";
        } else {
            echo "<p>Nenhuma observação foi inserida.</p>";
        }

        // Exibir o valor total
        echo "<p>Valor Total: {$os['preco_total_geral']}</p>";
    }

    // Quebra de linha para exibir em colunas separadas
    echo "<br>";
} else {
    echo "<p>Nenhuma Ordem de Serviço em Andamento encontrada.</p>";
}
$conn->close();
?>

<div class="paginacao">
    <?php
    // Exibir links de paginação apenas se houver mais de uma página
    if ($totalPaginas > 1) {
        // Link para a página anterior
        if ($paginaAtual > 1) {
            echo "<a href='?pagina=" . ($paginaAtual - 1) . "' class='pagina-anterior'>&laquo;</a>";
        }

        // Links para as páginas intermediárias
        $quantidadeLinks = 5; // Quantidade de links visíveis
        $inicio = max(1, $paginaAtual - floor($quantidadeLinks / 2));
        $fim = min($totalPaginas, $paginaAtual + floor($quantidadeLinks / 2));

        for ($i = $inicio; $i <= $fim; $i++) {
            if ($paginaAtual == $i) {
                echo "<span class='pagina-atual'>$i</span>";
            } else {
                echo "<a href='?pagina=$i' class='pagina'>$i</a>";
            }
        }

        // Link para a próxima página
        if ($paginaAtual < $totalPaginas) {
            echo "<a href='?pagina=" . ($paginaAtual + 1) . "' class='proxima-pagina'>&raquo;</a>";
        }
    } else {
        // Caso haja apenas uma página, mostre o link de página 1
        echo "<span class='pagina-atual'>1</span>";
    }
    ?>
</div>

<p><a href="Criação OS.php">Voltar para a Lista de Ordens de Serviço</a></p>
<script>
    function validarPesquisa() {
        var campoPesquisa = document.querySelector("input[name='ordem_servico_id']");

        if (campoPesquisa.value === "") {
            alert("Preencha o campo de pesquisa antes de realizar a busca.");
            return false; // Impede o envio do formulário
        }

        // Se o campo estiver preenchido, permite o envio do formulário
        return true;
    }
</script>
</body>
</html>
<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Verifique se o parâmetro 'ordem_servico_id' está definido na URL
if (isset($_GET['ordem_servico_id'])) {
    $ordem_servico_id = $_GET['ordem_servico_id'];

    // Consulta SQL para recuperar a ordem de serviço com o ID especificado
    $sql = "SELECT * FROM ordem_servico_completa WHERE ordem_servico_id = $ordem_servico_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $os_details = $result->fetch_assoc(); // Apenas uma ordem de serviço deve ser recuperada
    } else {
        echo "<p>Nenhuma Ordem de Serviço encontrada com o ID especificado.</p>";
    }
} else {
    echo "<p>ID da Ordem de Serviço não especificado na URL.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Ordem de Serviço</title>
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
    </style>
</head>
<body>
    <h2>Detalhes da Ordem de Serviço</h2>

    <?php
    if (!empty($os_details)) {
        echo "<h3>Ordem de Serviço ID: {$os_details['ordem_servico_id']}</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><td>{$os_details['ordem_servico_id']}</td></tr>";
        echo "<tr><th>Cliente</th><td>{$os_details['cliente_nome']}</td></tr>";
        echo "<tr><th>Veículo</th><td>{$os_details['veiculo_nome']}</td></tr>";
        echo "<tr><th>Placa do Veículo</th><td>{$os_details['veiculo_placa']}</td></tr>";
        echo "<tr><th>Data de Abertura</th><td>{$os_details['data_abertura']}</td></tr>";
        echo "<tr><th>Status</th><td>{$os_details['status']}</td></tr>";
        echo "</table>";

        // Quebra de linha para exibir em colunas separadas
        echo "<br>";

        // Exibir a lista de produtos
        $ordem_servico_id = $os_details['ordem_servico_id']; // Alterado de $os para $os_details
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
        } else {
            echo "<p>Nenhum produto encontrado para esta ordem de serviço.</p>";
        }

        // Informações adicionais sobre pagamento e observações
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
            echo "Informações de pagamento não encontradas no banco de dados.";
        }

        // Exiba as observações
        echo "<h3>Observações do Vendedor</h3>";
        $observacoes_vendedor = $os_details['observacoes_vendedor'];
        if (!empty($observacoes_vendedor)) {
            echo "<p>{$observacoes_vendedor}</p>";
        } else {
            echo "<p>Nenhuma observação foi inserida.</p>";
        }

        // Exiba a lista de serviços prestados
        echo "<h3>Serviços Prestados</h3>";
        echo "<table>";
        echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";

        // Consulta SQL para recuperar os serviços prestados para esta ordem de serviço
        $sqlServicos = "SELECT * FROM servicos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
        $resultServicos = $conn->query($sqlServicos);

        while ($servico = $resultServicos->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$servico['servico_nome']}</td>";
            echo "<td>{$servico['tecnico_responsavel']}</td>";
            echo "<td>{$servico['valor_servico']}</td>";
            echo "</tr>";
        }

        echo "</table>";

        // Exibir o valor total
        echo "<p>Valor Total: {$os_details['preco_total_geral']}</p>";

        // Quebra de linha para exibir em colunas separadas
        echo "<br>";    
    
    } else {
        echo "<p>Nenhuma Ordem de Serviço encontrada com o ID especificado.</p>";
    }
    ?>

    <p><a href="Criação OS.php">Voltar para a Lista de Ordens de Serviço</a></p>
</body>
</html>

<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Prepare a consulta SQL com a cláusula WHERE para selecionar apenas as OS com status 'Concluída'
$sql = "SELECT * FROM ordem_servico_completa WHERE status = 'Em Andamento'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
} else {
    echo "<p>Nenhuma Ordem de Serviço encontrada.</p>";
}

// Fechar a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ordens de serviço</title>
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
    <h2>Ordens em andamento</h2>

    <?php
    if (!empty($os_details)) {
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
    
            // Botão para Em Andamento
            echo "<div style='display: inline-block;'>";
            echo "<form method='POST' action='atualizar_status.php'>";
            echo "<input type='hidden' name='ordem_servico_id' value='{$os['ordem_servico_id']}'>";
            echo "<input type='hidden' name='novo_status' value='Concluída'>";
            echo "<input type='submit' value='Concluída'>";
            echo "</form>";
            echo "</div>";
    
            echo "</td></tr>";
            echo "</table>";

            // Exibir a lista de produtos
            if (!empty($os['produtos'])) {
                echo "<h3>Produtos Vendidos</h3>";
                echo "<table>";
                echo "<tr><th>Código do Produto</th><th>Produto</th><th>Referência</th><th>Tipo</th><th>Quantidade</th><th>Preço Unitário</th><th>Subtotal</th></tr>";

                foreach ($os['produtos'] as $produto) {
                    echo "<tr>";
                    echo "<td>{$produto['codigo_produto']}</td>";
                    echo "<td>{$produto['produto']}</td>";
                    echo "<td>{$produto['referencia']}</td>";
                    echo "<td>{$produto['tipo']}</td>";
                    echo "<td>{$produto['quantidade']}</td>";
                    echo "<td>{$produto['preco_produto']}</td>";
                    echo "<td>{$produto['subtotal_produto']}</td>";
                    echo "</tr>";
                }

                echo "</table>";
            }

            // Exiba a lista de serviços prestados
            echo "<h3>Serviços Prestados</h3>";
            echo "<table>";
            echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";

            foreach ($os_details as $servico) {
                echo "<tr>";
                echo "<td>{$servico['servico_nome']}</td>";
                echo "<td>{$servico['tecnico_responsavel']}</td>";
                echo "<td>{$servico['preco_total_servico']}</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Exiba as observações
            echo "<h3>Observações do Vendedor</h3>";
            echo "<p>{$os['observacoes_vendedor']}</p>";
        }
    
            // Quebra de linha para exibir em colunas separadas
            echo "<br>";
        
    } else {
        echo "<p>Nenhuma Ordem de Serviço encontrada.</p>";
    }
    ?>

    <p><a href="Criação OS.php">Voltar para a Lista de Ordens de Serviço</a></p>
</body>
</html>

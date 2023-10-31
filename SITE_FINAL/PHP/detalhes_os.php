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
        echo "<tr><th>Ações</th><td>";

        // Botão para Em Andamento
        echo "<div style='display: inline-block;'>";
        echo "<form method='POST' action='atualizar_status.php'>";
        echo "<input type='hidden' name='ordem_servico_id' value='{$os_details['ordem_servico_id']}'>";
        echo "<input type='hidden' name='novo_status' value='Em Andamento'>";
        echo "<input type='submit' value='Em Andamento'>";
        echo "</form>";
        echo "</div>";

        // Botão para Concluída
        echo "<div style='display: inline-block;'>";
        echo "<form method='POST' action='atualizar_status.php'>";
        echo "<input type='hidden' name='ordem_servico_id' value='{$os_details['ordem_servico_id']}'>";
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

        // Exiba as observações
        echo "<h3>Observações do Vendedor</h3>";
        echo "<p>{$os_details['observacoes_vendedor']}</p>";
    } else {
        echo "<p>Nenhuma Ordem de Serviço encontrada com o ID especificado.</p>";
    }
    ?>

    <p><a href="Criação OS.php">Voltar para a Lista de Ordens de Serviço</a></p>
</body>
</html>

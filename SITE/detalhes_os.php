<?php
$id_os = $_GET['id'];

$os_details = getOSDetailsFromDatabase($id_os);

function getOSDetailsFromDatabase($id) {
    return [
        'id' => $id,
        'cliente' => 'Cliente Exemplo',
        'veiculo_nome' => 'Veículo Exemplo',
        'veiculo_placa' => 'ABC123', 
        'data_abertura' => '2023-09-05',
        'status' => 'Em Andamento',
        'produtos' => [
            ['codigo_produto' => '001', 'produto' => 'Produto 1', 'referencia' => 'Ref 001', 'tipo' => 'Tipo 1', 'quantidade' => 2, 'preco' => 50.00],
            ['codigo_produto' => '002', 'produto' => 'Produto 2', 'referencia' => 'Ref 002', 'tipo' => 'Tipo 2', 'quantidade' => 3, 'preco' => 75.00],
        ],
        'servicos' => [
            ['servico_nome' => 'Serviço A', 'tecnico_responsavel' => 'Técnico 1', 'valor_servico' => 100.00],
            ['servico_nome' => 'Serviço B', 'tecnico_responsavel' => 'Técnico 2', 'valor_servico' => 150.00],
        ],
        'observacoes_vendedor' => 'Observações do Vendedor...',
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
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

    echo "<h3>Atualizar Status</h3>";
    echo "<form method='post' action='atualizar_status.php'>";
    echo "<input type='hidden' name='os_id' value='{$os_details['id']'>";
    echo "<label><input type='radio' name='novo_status' value='Em Andamento'> Em Andamento</label>";
    echo "<label><input type='radio' name='novo_status' value='Finalizada'> Finalizada</label>";
    echo "<label><input type='radio' name='novo_status' value='Cancelada'> Cancelada</label>";
    echo "<input type='submit' value='Atualizar'>";

    <?php
    if ($os_details) {
        echo "<table>";
        echo "<tr><th>ID</th><td>{$os_details['id']}</td></tr>";
        echo "<tr><th>Cliente</th><td>{$os_details['cliente']}</td></tr>";
        echo "<tr><th>Veículo</th><td>{$os_details['veiculo_nome']}</td></tr>";
        echo "<tr><th>Placa do Veículo</th><td>{$os_details['veiculo_placa']}</td></tr>";
        echo "<tr><th>Data de Abertura</th><td>{$os_details['data_abertura']}</td></tr>";
        echo "<tr><th>Status</th><td>{$os_details['status']}</td></tr>";
        echo "</table>";

        // Exiba a lista de produtos vendidos
        echo "<h3>Produtos Vendidos</h3>";
        echo "<table>";
        echo "<tr><th>Código do Produto</th><th>Produto</th><th>Referência</th><th>Tipo</th><th>Quantidade</th><th>Preço</th></tr>";

        foreach ($os_details['produtos'] as $produto) {
            echo "<tr>";
            echo "<td>{$produto['codigo_produto']}</td>";
            echo "<td>{$produto['produto']}</td>";
            echo "<td>{$produto['referencia']}</td>";
            echo "<td>{$produto['tipo']}</td>";
            echo "<td>{$produto['quantidade']}</td>";
            echo "<td>{$produto['preco']}</td>";
            echo "</tr>";
        }

        echo "</table>";

        // Exiba a lista de serviços prestados
        echo "<h3>Serviços Prestados</h3>";
        echo "<table>";
        echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";

        foreach ($os_details['servicos'] as $servico) {
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
        echo "<p>Ordem de Serviço não encontrada.</p>";
    }
    ?>

    <p><a href="Criação OS.html">Voltar para a Lista de Ordens de Serviço</a></p>
</body>
</html>

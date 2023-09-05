<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = $_POST["cliente"];
    $veiculo_nome = $_POST["veiculo_nome"];
    $veiculo_placa = $_POST["veiculo_placa"];
    $data_abertura = $_POST["data_abertura"];
    $descricao = $_POST["descricao"];
    $preco_total_produtos = 0;
    $preco_total_servicos = 0;
    $observacoes_vendedor = $_POST["observacoes_vendedor"];

    // Processar os produtos vendidos
    $produtos = array();
    for ($i = 0; $i < count($_POST["codigo_produto"]); $i++) {
        $codigo_produto = $_POST["codigo_produto"][$i];
        $produto = $_POST["produto"][$i];
        $referencia = $_POST["referencia"][$i];
        $tipo = $_POST["tipo"][$i];
        $quantidade = $_POST["quantidade"][$i];
        $preco_produto = $_POST["preco"][$i];
        
        // Calcular o subtotal deste produto
        $subtotal_produto = $quantidade * $preco_produto;
        $preco_total_produtos += $subtotal_produto;

        // Armazenar os detalhes do produto
        $produtos[] = array(
            'codigo_produto' => $codigo_produto,
            'produto' => $produto,
            'referencia' => $referencia,
            'tipo' => $tipo,
            'quantidade' => $quantidade,
            'preco_produto' => $preco_produto,
            'subtotal_produto' => $subtotal_produto
        );
    }

    // Processar os serviços prestados
    $servicos = array();
    for ($i = 0; $i < count($_POST["servico_nome"]); $i++) {
        $servico_nome = $_POST["servico_nome"][$i];
        $tecnico_responsavel = $_POST["tecnico_responsavel"][$i];
        $valor_servico = $_POST["valor_servico"][$i];
        
        // Calcular o preço total dos serviços
        $preco_total_servicos += $valor_servico;

        // Armazenar os detalhes do serviço
        $servicos[] = array(
            'servico_nome' => $servico_nome,
            'tecnico_responsavel' => $tecnico_responsavel,
            'valor_servico' => $valor_servico
        );
    }

    // Calcular o preço total geral (produtos + serviços)
    $preco_total_geral = $preco_total_produtos + $preco_total_servicos;

    // Exibir o resumo da ordem de serviço
    echo "<h2>Resumo da Ordem de Serviço</h2>";
    echo "Cliente: $cliente<br>";
    echo "Nome do Veículo: $veiculo_nome<br>";
    echo "Placa do Veículo: $veiculo_placa<br>";
    echo "Data de Abertura: $data_abertura<br>";
    echo "Descrição: $descricao<br>";
    echo "Preço Total dos Produtos: $preco_total_produtos<br>";
    echo "Preço Total dos Serviços: $preco_total_servicos<br>";
    echo "Preço Total Geral: $preco_total_geral<br>";
    echo "Observações do Vendedor: $observacoes_vendedor<br>";

    echo "<h2>Produtos Vendidos</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Código do Produto</th><th>Produto</th><th>Referência</th><th>Tipo</th><th>Quantidade</th><th>Preço Unitário</th><th>Subtotal</th></tr>";
    
    foreach ($produtos as $produto) {
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

    echo "<h2>Serviços Prestados</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";
    
    foreach ($servicos as $servico) {
        echo "<tr>";
        echo "<td>{$servico['servico_nome']}</td>";
        echo "<td>{$servico['tecnico_responsavel']}</td>";
        echo "<td>{$servico['valor_servico']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}
?>

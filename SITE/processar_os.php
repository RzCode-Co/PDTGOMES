<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_nome = $_POST["cliente_nome"];
    $veiculo_nome = $_POST["veiculo_nome"];
    $veiculo_placa = $_POST["veiculo_placa"];
    $data_abertura = $_POST["data_abertura"];
    $observacoes_vendedor = $_POST["observacoes_vendedor"];

    // Inicialize os totais para produtos e serviços
    $preco_total_produtos = 0;
    $preco_total_servicos = 0;

    // Processar os produtos vendidos
    $produtos = [];
    for ($i = 0; $i < count($_POST["codigo_produto"]); $i++) {
        $codigo_produto = $_POST["codigo_produto"][$i];
        $produto_nome = $_POST["produto"][$i];
        $referencia = $_POST["referencia"][$i];
        $tipo = $_POST["tipo"][$i];
        $quantidade = $_POST["quantidade"][$i];
        $preco_produto = $_POST["preco"][$i];

        // Consulta SQL para obter dados do estoque para o produto atual
        $sql = "SELECT quantidade, valor_varejo, valor_atacado FROM estoque WHERE id = '$codigo_produto' AND nome = '$produto_nome' AND referencia = '$referencia'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $quantidade_disponivel = $row["quantidade"];
            $valor_varejo = $row["valor_varejo"];
            $valor_atacado = $row["valor_atacado"];

            // Verificar se há quantidade disponível no estoque
            if ($quantidade_disponivel >= $quantidade) {
                // Calcular o subtotal deste produto
                $subtotal_produto = $quantidade * $preco_produto;
                $preco_total_produtos += $subtotal_produto;

                // Armazenar os detalhes do produto
                $produtos[] = [
                    'codigo_produto' => $codigo_produto,
                    'produto' => $produto_nome,
                    'referencia' => $referencia,
                    'tipo' => $tipo,
                    'quantidade' => $quantidade,
                    'preco_produto' => $preco_produto,
                    'subtotal_produto' => $subtotal_produto
                ];

                // Atualizar a quantidade disponível no estoque
                $quantidade_disponivel -= $quantidade;

                // Atualizar a quantidade disponível no estoque
                $sql = "UPDATE estoque SET quantidade = $quantidade_disponivel WHERE id = '$codigo_produto' AND nome = '$produto_nome' AND referencia = '$referencia'";
                $conn->query($sql);
            } else {
                echo "Quantidade insuficiente em estoque para o produto $codigo_produto - $produto_nome - $referencia.";
                exit; // Sai do script em caso de quantidade insuficiente
            }
        } else {
            echo "Produto não encontrado no estoque: $codigo_produto - $produto_nome - $referencia.";
            exit; // Sai do script em caso de produto não encontrado
        }
    }

    // Processar os serviços prestados
    $servicos = [];
    for ($i = 0; $i < count($_POST["servico_nome"]); $i++) {
        $servico_nome = $_POST["servico_nome"][$i];
        $tecnico_responsavel = $_POST["tecnico_responsavel"][$i];
        $valor_servico = $_POST["valor_servico"][$i];

        // Calcular o preço total dos serviços
        $preco_total_servicos += $valor_servico;

        // Armazenar os detalhes do serviço
        $servicos[] = [
            'servico_nome' => $servico_nome,
            'tecnico_responsavel' => $tecnico_responsavel,
            'valor_servico' => $valor_servico
        ];
    }

    // Calcular o preço total geral (produtos + serviços)
    $preco_total_geral = $preco_total_produtos + $preco_total_servicos;

    // Inserir os dados na tabela ordem_servico
    $sql = "INSERT INTO ordem_servico (cliente_nome, veiculo_nome, veiculo_placa, data_abertura, preco_total_produtos, preco_total_servicos, preco_total_geral, observacoes_vendedor) VALUES ('$cliente_nome', '$veiculo_nome', '$veiculo_placa', '$data_abertura', $preco_total_produtos, $preco_total_servicos, $preco_total_geral, '$observacoes_vendedor')";

    if ($conn->query($sql) === TRUE) {
        // Obter o ID da ordem de serviço inserida
        $ordem_servico_id = $conn->insert_id;

        // Inserir os produtos da ordem de serviço na tabela produtos_ordem_servico
        foreach ($produtos as $produto) {

            $sql = "INSERT INTO produtos_ordem_servico (ordem_servico_id, codigo_produto, produto, referencia, tipo, quantidade, preco_produto) VALUES ('$ordem_servico_id', '$codigo_produto', '$produto_nome', '$referencia', '$tipo', '$quantidade', '$preco_produto')";
            $conn->query($sql);
        }

        // Inserir os serviços da ordem de serviço na tabela servicos_ordem_servico
        foreach ($servicos as $servico) {
            $servico_nome = $servico['servico_nome'];
            $tecnico_responsavel = $servico['tecnico_responsavel'];
            $valor_servico = $servico['valor_servico'];

            $sql = "INSERT INTO servicos_ordem_servico (ordem_servico_id, servico_nome, tecnico_responsavel, valor_servico) VALUES ('$ordem_servico_id', '$servico_nome', '$tecnico_responsavel', $valor_servico)";
            $conn->query($sql);
        }

        echo "Ordem de serviço registrada com sucesso!";
    } else {
        echo "Erro ao inserir a ordem de serviço: " . $conn->error;
    }
    $sql = "INSERT INTO ordem_servico_completa (ordem_servico_id, codigo_produto, cliente_nome, veiculo_nome, veiculo_placa, data_abertura, produto, referencia, tipo, quantidade, preco_total_produto, servico_nome, tecnico_responsavel, preco_total_servico, preco_total_geral, observacoes_vendedor) VALUES ('$ordem_servico_id', '$codigo_produto', '$cliente_nome', '$veiculo_nome', '$veiculo_placa', '$data_abertura', '$produto_nome', '$referencia', '$tipo', '$quantidade', '$preco_total_produtos', '$servico_nome', '$tecnico_responsavel', '$preco_total_servicos', '$preco_total_geral', '$observacoes_vendedor')";

    if ($conn->query($sql) === TRUE) {
        echo "Dados inseridos na tabela ordem_servico_completa com sucesso!";
    } else {
        echo "Erro ao inserir dados na tabela ordem_servico_completa: " . $conn->error;
    }

    $conn->close();
}
?>

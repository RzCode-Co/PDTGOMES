<?php
// Conexão com o banco de dados
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_comprador = $_POST["nome_comprador"];
    $nome_peca = $_POST["nome_peca"];
    $marca = $_POST["marca"];
    $ano = $_POST["ano"];
    $referencia = $_POST["referencia"];
    $aplicacao = $_POST["aplicacao"];
    $quantidade = $_POST["quantidade"];
    $cpf_cnpj = $_POST["cpf_cnpj"];
    
    // Verifique se a escolha do usuário (CPF ou CNPJ) é válida
    if ($_POST["cpf_cnpj"] == "CPF") {
        $CPF = $_POST["CPF"];
        $CNPJ = null; // Defina CNPJ como nulo
    } elseif ($_POST["cpf_cnpj"] == "CNPJ") {
        $CNPJ = $_POST["CNPJ"];
        $CPF = null; // Defina CPF como nulo
    } else {
        // Trate o caso em que nenhum dos campos foi escolhido
        echo "Por favor, escolha um tipo de documento (CPF ou CNPJ).";
        exit; // Saia do script
    }
    
    $forma_pagamento = $_POST["forma_pagamento"];
    $valor_venda = $_POST["valor_venda"];
    $funcionario_vendedor = $_POST["funcionario_vendedor"];
    $garantia_produto = $_POST["garantia_produto"];

    // Consulta SQL para verificar se o produto existe com base no nome e valor de venda
    $verificar_produto_sql = "SELECT nome, marca, ano, referencia, aplicacao, quantidade FROM estoque WHERE nome = '$nome_peca' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
    
    $result = $conn->query($verificar_produto_sql);

    if ($result->num_rows > 0) {
        // O produto existe e atende aos critérios de valor de venda

        $row = $result->fetch_assoc();
        $estoque_disponivel = $row["quantidade"];
        
        // Verificar se a quantidade é suficiente
        if ($quantidade > 0 && $quantidade <= $estoque_disponivel) {
            // Consulta SQL para inserir a venda na tabela "vendas"
            $sql = "INSERT INTO vendas (nome_comprador, nome_peca, marca, ano, referencia, aplicacao, quantidade, cpf_cnpj, CPF, CNPJ, forma_pagamento, valor_venda, funcionario_vendedor, garantia_produto) VALUES ('$nome_comprador', '$nome_peca', '$marca', '$ano', '$referencia', '$aplicacao', '$quantidade', '$cpf_cnpj', '$CPF', '$CNPJ', '$forma_pagamento', '$valor_venda', '$funcionario_vendedor', '$garantia_produto')";
            
            // Executar a consulta de inserção
            if ($conn->query($sql) === TRUE) {
                // Consulta SQL para atualizar a quantidade no estoque
                $update_sql = "UPDATE estoque SET quantidade = quantidade - '$quantidade' WHERE nome = '$nome_peca' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
                
                // Executar a consulta de atualização
                if ($conn->query($update_sql) === TRUE) {
                    echo "Venda registrada com sucesso! Quantidade no estoque atualizada.";
                } else {
                    echo "Erro ao atualizar a quantidade no estoque: " . $conn->error;
                }
            } else {
                echo "Erro ao registrar a venda: " . $conn->error;
            }
        } else {
            // A quantidade é insuficiente, redirecione para a página venda.html com uma mensagem de erro
            echo "<script>
                    alert('Quantidade para venda Insuficiente!');
                    window.location.href = 'venda.html';
                  </script>";
        }
    } else {
        // O produto não existe ou não atende aos critérios de valor de venda
        echo "Produto não encontrado ou valor de venda não atende aos critérios.";
    }
}

$conn->close();
?>

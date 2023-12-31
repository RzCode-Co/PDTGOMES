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
        echo '<script>
            alert("Escolha CPF ou CNPJ.");
            window.location.href = "Venda.html";
        </script>';
        exit; // Saia do script
    }

    $forma_pagamento = $_POST["forma_pagamento"];
    $numero_parcelas = ($forma_pagamento === "Parcelado") ? $_POST["numero_parcelas"] : null;
    $valor_venda = strtoupper($_POST["valor_venda"]);
    $funcionario_vendedor = strtoupper($_POST["funcionario_vendedor"]);
    $garantia_produto = strtoupper($_POST["garantia_produto"]);

    // Consulta SQL para verificar se o produto existe com base no nome e outros critérios
    $verificar_produto_sql = "SELECT id, quantidade FROM estoque WHERE nome = ? AND referencia = ? AND marca = ? AND aplicacao = ? AND ano = ?";
    
    $stmt = $conn->prepare($verificar_produto_sql);
    $stmt->bind_param("sssss", $nome_peca, $referencia, $marca, $aplicacao, $ano);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // O produto existe e atende aos critérios

        $row = $result->fetch_assoc();
        $estoque_disponivel = $row["quantidade"];

        // Verificar se a quantidade é suficiente
        if ($quantidade > 0) {
            // Consulta SQL para inserir a venda na tabela "vendas"
            $sql = "INSERT INTO vendas (nome_comprador, nome_peca, marca, ano, referencia, aplicacao, quantidade, cpf_cnpj, CPF, CNPJ, forma_pagamento, numero_parcelas, valor_venda, funcionario_vendedor, garantia_produto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssdssdss", $nome_comprador, $nome_peca, $marca, $ano, $referencia, $aplicacao, $quantidade, $cpf_cnpj, $CPF, $CNPJ, $forma_pagamento, $numero_parcelas, $valor_venda, $funcionario_vendedor, $garantia_produto);
            if ($stmt->execute()) {
                // Obtém o ID da venda recém-inserida
                $venda_id = $stmt->insert_id;
    
                // Consulta SQL para atualizar a quantidade no estoque
                $update_sql = "UPDATE estoque SET quantidade = quantidade - ? WHERE id = ?";
                
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("ss", $quantidade, $row["id"]);
                if ($stmt->execute()) {
                    $dataVenda = null;
                    // Insira a notificação no banco de dados de notificações
                    $mensagem_notificacao = "$funcionario_vendedor realizou uma venda de um(a) $nome_peca no valor de $valor_venda em $dataVenda";
                    $sql_notificacao = "INSERT INTO notificacoes (mensagem, data) VALUES (?, NOW())";
                    
                    $stmt = $conn->prepare($sql_notificacao);
                    $stmt->bind_param("s", $mensagem_notificacao);
                    if ($stmt->execute()) {
                        $valor_servico = null;
                        $preco_total_geral = null;
                        $valor_debito = null;
                        // Consulta SQL para inserir valores na tabela "valores"
                        $sql_valores = "INSERT INTO valores (id_op, data_venda, valor_venda, valor_servico, preco_total_geral, valor_debito) VALUES (?, ?, ?, ?, ?, ?)";
                        
                        $stmt = $conn->prepare($sql_valores);
                        $stmt->bind_param("ssssss", $venda_id, $dataVenda, $valor_venda, $valor_servico, $preco_total_geral, $valor_debito);
                        if ($stmt->execute()) {
                            echo '<script>
                                    alert("Venda registrada com sucesso! Quantidade no estoque atualizada.");
                                    window.location.href = "Venda.html";
                                  </script>';
                        } else {
                            echo "Erro ao atualizar valores de venda: " . $stmt->error;
                        }
                    } else {
                        echo "Erro ao criar notificação de venda: " . $stmt->error;
                    }
                } else {
                    echo "Erro ao atualizar a quantidade no estoque: " . $stmt->error;
                }
            } else {
                echo "Erro ao registrar a venda: " . $stmt->error;
            }
        } else {
            // A quantidade é insuficiente, exiba um alerta e redirecione para a página venda.html com uma mensagem de erro
            echo '<script>
                    alert("Quantidade para venda Insuficiente!");
                    window.location.href = "Venda.html";
                  </script>';
        }
    } else {
        // O produto não existe ou não atende aos critérios
        echo "Produto não encontrado ou valor de venda não atende aos critérios.";
    }
}

$conn->close();
?>

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
    '<script>
        alert("Escolha CPF ou CNPJ.");
        window.location.href = "Venda.html";
    </script>';
        exit; // Saia do script
    }
    
    $forma_pagamento = $_POST["forma_pagamento"];
    if ($forma_pagamento !== "Parcelado") {
        $numero_parcelas = null;
    } else {
        $numero_parcelas = $_POST["numero_parcelas"];
    }
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
            $sql = "INSERT INTO vendas (nome_comprador, nome_peca, marca, ano, referencia, aplicacao, quantidade, cpf_cnpj, CPF, CNPJ, forma_pagamento, numero_parcelas, valor_venda, funcionario_vendedor, garantia_produto) VALUES ('$nome_comprador', '$nome_peca', '$marca', '$ano', '$referencia', '$aplicacao', '$quantidade', '$cpf_cnpj', '$CPF', '$CNPJ', '$forma_pagamento', '$numero_parcelas', '$valor_venda', '$funcionario_vendedor', '$garantia_produto')";
            
            // Executar a consulta de inserção
            if ($conn->query($sql) === TRUE) {
                // Consulta SQL para atualizar a quantidade no estoque
                $update_sql = "UPDATE estoque SET quantidade = quantidade - '$quantidade' WHERE nome = '$nome_peca' AND marca = '$marca' AND ano = '$ano' AND referencia = '$referencia' AND aplicacao = '$aplicacao'";
                
                // Executar a consulta de atualização
                if ($conn->query($update_sql) === TRUE) {
                    echo '<script>
                            alert("Venda registrada com sucesso! Quantidade no estoque atualizada.");
                            window.location.href = "Venda.html";
                          </script>';
                          
                          $dataVenda = date("Y-m-d");
                          
                          // Insira a notificação no banco de dados de notificações
                          $sql = "INSERT INTO notificacoes (mensagem, data) VALUES ('$funcionario_vendedor realizou uma venda de um(a) $nome_peca no valor de $valor_venda em $dataVenda', NOW())";
                          
                          if ($conn->query($sql) === TRUE) {
                              echo "Notificação de venda criada com sucesso.";
                          } else {
                              echo "Erro ao criar notificação de venda: " . $conn->error;
                          }
                          

                } else {
                    echo '<script>
                            alert("Erro ao atualizar a quantidade no estoque: ' . $conn->error . '");
                            window.location.href = "Venda.html";
                          </script>';
                }
            } else {
                echo '<script>
                        alert("Erro ao registrar a venda: ' . $conn->error . '");
                        window.location.href = "Venda.html";
                      </script>';
            }
        } else {
            // A quantidade é insuficiente, exiba um alerta e redirecione para a página venda.html com uma mensagem de erro
            echo '<script>
                    alert("Quantidade para venda Insuficiente!");
                    window.location.href = "Venda.html";
                  </script>';
        }
    } else {
        // O produto não existe ou não atende aos critérios de valor de venda
        echo "Produto não encontrado ou valor de venda não atende aos critérios.";
    }
}


$conn->close();
?>

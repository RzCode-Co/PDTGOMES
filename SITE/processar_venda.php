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
    $valor_venda = $_POST["valor_venda"];
    $funcionario_vendedor = $_POST["funcionario_vendedor"];
    $garantia_produto = $_POST["garantia_produto"];

    // Consulta SQL para verificar se o produto existe com base no nome e outros critérios
    $verificar_produto_sql = "SELECT id, quantidade FROM estoque WHERE nome = '$nome_peca' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";

    $result = $conn->query($verificar_produto_sql);

    if ($result->num_rows > 0) {
        // O produto existe e atende aos critérios

        $row = $result->fetch_assoc();
        $estoque_disponivel = $row["quantidade"];

        // Verificar se a quantidade é suficiente
        if ($quantidade > 0 && $quantidade <= $estoque_disponivel) {
            // Consulta SQL para inserir a venda na tabela "vendas"
            $sql = "INSERT INTO vendas (nome_comprador, nome_peca, marca, ano, referencia, aplicacao, quantidade, cpf_cnpj, CPF, CNPJ, forma_pagamento, numero_parcelas, valor_venda, funcionario_vendedor, garantia_produto) VALUES ('$nome_comprador', '$nome_peca', '$marca', '$ano', '$referencia', '$aplicacao', '$quantidade', '$cpf_cnpj', '$CPF', '$CNPJ', '$forma_pagamento', '$numero_parcelas', '$valor_venda', '$funcionario_vendedor', '$garantia_produto')";
                // Obtém o ID da venda recém-inserida
                $venda_id = $conn->insert_id;

                // Consulta SQL para atualizar a quantidade no estoque
                $update_sql = "UPDATE estoque SET quantidade = quantidade - '$quantidade' WHERE id = '" . $row["id"] . "'";

                    $dataVenda = date("d-m-Y");
                    // Insira a notificação no banco de dados de notificações
                    $sql_notificacao = "INSERT INTO notificacoes (mensagem, data) VALUES ('$funcionario_vendedor realizou uma venda de um(a) $nome_peca no valor de $valor_venda em $dataVenda', NOW())";

                    // Consulta SQL para inserir valores na tabela "valores"
                    $sql_valores = "INSERT INTO valores (id_op, data_venda, valor_venda, valor_servico, preco_total_geral, valor_debito) VALUES ('$venda_id', '$dataVenda', '$valor_venda', NULL, NULL, NULL)";
            }
        }
}

$conn->close();
?>

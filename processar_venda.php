<?php
// Conexão com o banco de dados
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_comprador = $_POST["nome_comprador"];
    $cpf_cnpj = $_POST["cpf_cnpj"];
    $forma_pagamento = $_POST["forma_pagamento"];
    $valor_varejo = $_POST["valor_varejo"];
    $valor_atacado = $_POST["valor_atacado"];
    $funcionario_vendedor = $_POST["funcionario_vendedor"];
    $garantia_produto = $_POST["garantia_produto"];

    $sql = "INSERT INTO vendas (nome_comprador, cpf_cnpj, forma_pagamento, valor_varejo, valor_atacado, funcionario_vendedor) VALUES ('$nome_comprador', '$cpf_cnpj', '$forma_pagamento', $valor_varejo, $valor_atacado, '$funcionario_vendedor')";

    if ($conn->query($sql) === TRUE) {
        echo "Venda registrada com sucesso!";
    } else {
        echo "Erro ao registrar a venda: " . $conn->error;
    }
}

$conn->close();
?>

<?php
// Conexão com o banco de dados
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_comprador = $_POST["nome_comprador"];
    $cpf_cnpj = $_POST["cpf_cnpj"];
    $forma_pagamento = $_POST["forma_pagamento"];
    $valor_venda = $_POST["valor_venda"];
    $funcionario_vendedor = $_POST["funcionario_vendedor"];

    $sql = "INSERT INTO vendas (nome_comprador, cpf_cnpj, forma_pagamento, valor_venda, funcionario_vendedor) VALUES ('$nome_comprador', '$cpf_cnpj', '$forma_pagamento', $valor_venda, '$funcionario_vendedor')";

    if ($conn->query($sql) === TRUE) {
        echo "Venda registrada com sucesso!";
    } else {
        echo "Erro ao registrar a venda: " . $conn->error;
    }
}

$conn->close();
?>

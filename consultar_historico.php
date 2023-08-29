<?php
require_once "config.php"; // arquivo de config do bd

$sql = "SELECT funcionario_vendedor, nome_comprador, nome_peca, forma_pagamento, valor_venda FROM vendas";
$result = $conn->query($sql);

$historico = array(); // Array para armazenar o histórico de vendas

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

$conn->close();
?>

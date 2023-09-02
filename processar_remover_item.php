<?php
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    $nome = $_POST["nome"];
    $quantidade = $_POST["quantidade"];
    $valor_varejo = $_POST["valor_varejo"];
    $valor_atacado = $_POST["valor_atacado"];

    // Verifica se o item existe no estoque
    $verifica_sql = "SELECT * FROM estoque WHERE nome = '$nome' AND valor_varejo = '$valor_varejo' AND valor_atacado = '$valor_atacado'";
    $verifica_result = $conn->query($verifica_sql);

    if ($verifica_result->num_rows > 0) {
        // Atualiza a quantidade existente
        $update_sql = "UPDATE estoque SET quantidade = quantidade - $quantidade WHERE nome = '$nome' AND valor_varejo = '$valor_varejo' AND valor_atacado = '$valor_atacado'";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: gerenciar_estoque.php?msg=Quantidade removida do estoque com sucesso!");
        } else {
            header("Location: gerenciar_estoque.php?msg=Erro ao atualizar a quantidade: " . $conn->error);
        }
    } else {
        header("Location: gerenciar_estoque.php?msg=Item não encontrado no estoque.");
    }
}

$conn->close();
?>

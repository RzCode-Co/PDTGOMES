<?php
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    $nome = $_POST["nome"];
    $quantidade = $_POST["quantidade"];

    // Verifica se o item existe no estoque
    $verifica_sql = "SELECT * FROM estoque WHERE nome = '$nome'";
    $verifica_result = $conn->query($verifica_sql);

    if ($verifica_result->num_rows > 0) {
        // Atualiza a quantidade existente
        $update_sql = "UPDATE estoque SET quantidade = quantidade - $quantidade WHERE nome = '$nome'";
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

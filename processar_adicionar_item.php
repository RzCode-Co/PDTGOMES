<?php
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    $nome = $_POST["nome"];
    $quantidade = $_POST["quantidade"];
    $valor_varejo = $_POST["valor_varejo"];
    $valor_atacado = $_POST["valor_atacado"];
    $local = $_POST["local"];

    // Verifica se o item jÃ¡ existe no estoque
    $verifica_sql = "SELECT * FROM estoque WHERE nome = '$nome'";
    $verifica_result = $conn->query($verifica_sql);

    if ($verifica_result->num_rows > 0) {
        // Atualiza a quantidade existente
        $update_sql = "UPDATE estoque SET quantidade = quantidade + $quantidade WHERE nome = '$nome'";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: gerenciar_estoque.php?msg=Quantidade adicionada ao estoque com sucesso!");
        } else {
            header("Location: gerenciar_estoque.php?msg=Erro ao atualizar a quantidade: " . $conn->error);
        }
    } else {
        // Insere um novo registro no estoque
        $inserir_sql = "INSERT INTO estoque (nome, quantidade, valor_varejo, valor_atacado, localizacao) VALUES ('$nome', '$quantidade', '$valor_varejo', '$valor_atacado', '$local')";
        if ($conn->query($inserir_sql) === TRUE) {
            header("Location: gerenciar_estoque.php?msg=Item adicionado ao estoque com sucesso!");
        } else {
            header("Location: gerenciar_estoque.php?msg=Erro ao adicionar o item: " . $conn->error);
        }
    }
}

$conn->close();
?>
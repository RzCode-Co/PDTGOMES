<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"]) && isset($_POST["table"])) {
    $id = $_POST["id"];
    $table = $_POST["table"];
    
    // Debug: Exibir o ID e a tabela para verificação
    echo "ID: " . $id . "<br>";
    echo "Tabela: " . $table . "<br>";
    
    $sql = "";

    if ($table === "vendas") {
        $sql = "DELETE FROM vendas WHERE id = $id";
    } elseif ($table === "ordem_servico_completa") {
        $sql = "DELETE FROM ordem_servico_completa WHERE ID = $id";
    }

    // Debug: Exibir a consulta SQL para verificação
    echo "SQL: " . $sql . "<br>";

    if (!empty($sql)) {
        if ($conn->query($sql) === TRUE) {
            echo "sucesso";
        } else {
            // Debug: Exibir erros do banco de dados
            echo "Erro MySQL: " . $conn->error;
            echo "erro";
        }
    } else {
        echo "erro";
    }

    $conn->close();
} else {
    echo "erro";
}
?>

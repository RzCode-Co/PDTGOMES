<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Consulta SQL para excluir a linha com base no ID
    $sql = "DELETE FROM ordem_servico_completa WHERE ID = $id";
        // Consulta SQL para excluir a linha com base no ID
    $sql = "DELETE FROM ordem_servico WHERE id = $id";
    // Consulta SQL para excluir a linha com base no ID
    $sql = "DELETE FROM produtos_ordem_servico WHERE ordem_servico_id = $id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("OS encerrada com sucesso.");window.location.href = "../PHP/Financeiro.php";</script>';
    }
    // Feche a conexão com o banco de dados
    $conn->close();
}
?>
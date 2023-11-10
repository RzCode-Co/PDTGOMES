<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Consulta SQL para excluir a linha com base no ID
    $sql = "DELETE FROM vendas WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Venda encerrada com sucesso.");window.location.href = "../PHP/Financeiro.php";</script>';
    } else {
        echo "Erro ao excluir o registro: " . $conn->error;
    }
    $conn->close();
}
?>
<?php
require_once "config.php"; 

if (isset($_GET["nome_pesquisa"])) {
    $nome_pesquisa = $_GET["nome_pesquisa"];

    $sql = "SELECT nome_peca, quantidade FROM estoque WHERE nome_peca LIKE '%$nome_pesquisa%'";
    $result = $conn->query($sql);

    $itens_estoque = array(); // Array para armazenar os itens do estoque

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $itens_estoque[] = $row;
        }
    }
}

$conn->close();
?>

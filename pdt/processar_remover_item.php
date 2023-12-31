<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["referencia"]) && isset($_POST["aplicacao"]) && isset($_POST["marca"]) && isset($_POST["ano"]) && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    $nome = strtoupper($_POST["nome"]);
    $referencia = strtoupper($_POST["referencia"]);
    $marca = strtoupper($_POST["marca"]);
    $aplicacao = strtoupper($_POST["aplicacao"]);
    $ano = strtoupper($_POST["ano"]);
    $quantidade = strtoupper($_POST["quantidade"]);

    // Verifica se o item existe no estoque com base nos critérios
    $verifica_sql = "SELECT * FROM estoque WHERE nome = '$nome' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
    $verifica_result = $conn->query($verifica_sql);

    if ($verifica_result->num_rows > 0) {
        // Item encontrado no estoque, agora você pode atualizar a quantidade
        $update_quantidade_sql = "UPDATE estoque SET quantidade = quantidade - $quantidade WHERE nome = '$nome' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
        
        if ($conn->query($update_quantidade_sql) === TRUE) {
            // Verifica se a quantidade restante é maior que zero
            $verifica_quantidade_sql = "SELECT quantidade FROM estoque WHERE nome = '$nome' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
            $verifica_quantidade_result = $conn->query($verifica_quantidade_sql);
            
            if ($verifica_quantidade_result->num_rows > 0) {
                $row = $verifica_quantidade_result->fetch_assoc();
                $quantidade_restante = $row["quantidade"];
                
                if ($quantidade_restante <= 0) {
                    // Se a quantidade restante for menor ou igual a zero, remova o produto
                    $remover_sql = "DELETE FROM estoque WHERE nome = '$nome' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
                    if ($conn->query($remover_sql) === TRUE) {
                        echo "<script>alert('Item removido do estoque com sucesso!');</script>";
                    } else {
                        echo "<script>alert('Erro ao remover o item: " . $conn->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Quantidade atualizada com sucesso!');</script>";
                }
            } else {
                echo "<script>alert('Erro ao verificar a quantidade restante: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Erro ao atualizar a quantidade: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Item não encontrado no estoque.');</script>";
    }
}

$conn->close();
?>
<script>
    // Redireciona para a página "estoque.html" após o pop-up
    window.location.href = "estoque.html";
</script>

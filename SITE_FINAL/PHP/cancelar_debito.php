<?php
require_once "config.php";

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

// Verifique se o formulário foi enviado para cancelar débito
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $data_debito = removeAcentos($_POST["data_debito"]);
    $nome = removeAcentos($_POST["nome"]);
    $tipo = removeAcentos($_POST["tipo"]);
    $descricao = removeAcentos($_POST["descricao"]);

    // Consulta SQL para verificar se o débito existe e obter o valor_debito
    $sql_verifica_debito = "SELECT d.id, d.valor_debito, v.id AS id_op FROM debitos d
    INNER JOIN valores v ON d.id = v.id_op
    WHERE d.data_debito = '$data_debito' AND d.nome = '$nome' AND d.tipo = '$tipo' AND d.descricao = '$descricao'";


    $result_verifica_debito = $conn->query($sql_verifica_debito);

    if ($result_verifica_debito->num_rows > 0) {
        // O débito existe, então podemos prosseguir com a exclusão
        $row = $result_verifica_debito->fetch_assoc();
        $debito_id = $row["id"];
        $valor_debito = $row["valor_debito"];
        $valor_op_id = $row["id_op"];

        // Consulta SQL para excluir o débito
        $sql_excluir_debito = "DELETE FROM debitos WHERE id = $debito_id";

        if ($conn->query($sql_excluir_debito) === TRUE) {
            echo "<script>alert('Débito cancelado com sucesso.');</script>";

            // Consulta SQL para atualizar a tabela "valores" subtraindo o valor_debito
            $sql_atualiza_valores = "UPDATE valores SET valor_debito = valor_debito - $valor_debito WHERE id = $valor_op_id";

            if ($conn->query($sql_atualiza_valores) === TRUE) {
                echo "Valor_debito atualizado.";
            } else {
                echo "Erro ao atualizar valor_debito: " . $conn->error;
            }
        } else {
            echo "<script>alert('Erro ao cancelar o débito: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Nenhum débito correspondente encontrado. Verifique os detalhes informados.');</script>";
    }
}
$conn->close();
?>
<script>
    window.location.href = "debitos.php";
</script>
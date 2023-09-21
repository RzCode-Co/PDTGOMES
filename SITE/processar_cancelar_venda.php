<?php
require_once "config.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["excluir_venda"])) {
// Obtenha os valores do formulário
    $nome_comprador = $_POST["nome_comprador"];
    $nome_peca = $_POST["nome_peca"];
    $marca = $_POST["marca"];
    $ano = $_POST["ano"];
    $referencia = $_POST["referencia"];
    $aplicacao = $_POST["aplicacao"];
    $cpf_cnpj = $_POST["cpf_cnpj"];
    $CPF = $_POST["CPF"];
    $CNPJ = $_POST["CNPJ"];
    $funcionario_vendedor = $_POST["funcionario_vendedor"];

    // Consulta SQL para encontrar a venda com base nos critérios fornecidos
    $sql = "SELECT id FROM vendas WHERE
        nome_comprador = '$nome_comprador' AND
        nome_peca = '$nome_peca' AND
        marca = '$marca' AND
        ano = '$ano' AND
        referencia = '$referencia' AND
        aplicacao = '$aplicacao' AND
        cpf_cnpj = '$cpf_cnpj' AND
        CPF = '$CPF' AND
        CNPJ = '$CNPJ' AND
        funcionario_vendedor = '$funcionario_vendedor'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // A venda foi encontrada, obtenha o ID da venda
        $row = $result->fetch_assoc();
        $venda_id = $row["id"];

        // Consulta SQL para excluir a venda com base no ID
        $sql_excluir_venda = "DELETE FROM vendas WHERE id = $venda_id";
        if ($conn->query($sql_excluir_venda) === TRUE) {
            // A venda foi excluída com sucesso

            // Agora, verifique se o ID corresponde ao ID_OP na tabela "valores" e exclua o registro correspondente
            $sql_verificar_id_op = "SELECT id_op FROM valores WHERE id_op = $venda_id";
            $result_id_op = $conn->query($sql_verificar_id_op);

            if ($result_id_op->num_rows > 0) {
                // O ID corresponde ao ID_OP na tabela "valores", exclua o registro correspondente
                $sql_excluir_valor = "DELETE FROM valores WHERE id_op = $venda_id";
                if ($conn->query($sql_excluir_valor) === TRUE) {
                    echo "Venda e registro de valores excluídos com sucesso.";
                } else {
                    echo "Erro ao excluir registro de valores: " . $conn->error;
                }
            } else {
                echo "Venda excluída com sucesso, mas nenhum registro correspondente de valores encontrado.";
            }
        } else {
            echo "Erro ao excluir venda: " . $conn->error;
        }
    } else {
        echo "Nenhuma venda correspondente encontrada. Verifique os detalhes informados.";
    }
}
$conn->close();
?>
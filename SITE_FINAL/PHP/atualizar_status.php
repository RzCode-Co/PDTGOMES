<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";

    // Receba o ID da ordem de serviço e o novo status do formulário
    $ordem_servico_id = $_POST["ordem_servico_id"];
    $novo_status = $_POST["novo_status"];

    // Prepare a consulta SQL para atualizar o status
    $sql = "UPDATE ordem_servico_completa SET status = ? WHERE ordem_servico_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        // Vincule as variáveis à instrução preparada como parâmetros
        $stmt->bind_param("si", $novo_status, $ordem_servico_id);

        // Execute a instrução preparada
        if ($stmt->execute()) {
            // Redirecione de volta à página de detalhes da ordem de serviço
            header("Location: detalhes_os_em_andamento.php");
            exit();
        } else {
            echo "Erro ao executar a consulta: " . $stmt->error;
        }

        // Feche a instrução preparada
        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }
    

    // Feche a conexão com o banco de dados
    $conn->close();
} else {
    // Se o formulário não foi submetido, redirecione para a página de origem
    header("Location: detalhes_os_em_andamento.php");
    exit();
}
?>

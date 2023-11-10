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
            if($novo_status == 'Em Andamento'){
                header("Location: consultar_ordens_servico.php");
            }else if($novo_status == 'Concluída'){
                header("Location: consultar_ordens_servicos_concluidas.php");
            }
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
    header("Location: consultar_ordens_servico.php");
    exit();
}
?>
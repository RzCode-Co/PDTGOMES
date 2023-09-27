<?php
// Conexão com o banco de dados
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_nome = $_POST["cliente"];
    $veiculo_nome = $_POST["veiculo_nome"];
    $veiculo_placa = $_POST["veiculo_placa"];
    $data_abertura = $_POST["data_abertura"];

    // Consulta SQL para verificar a existência da OS na tabela ordem_servico_completa
    $verifica_ordem_sql = "SELECT ordem_servico_id FROM ordem_servico_completa WHERE cliente_nome = '$cliente_nome' AND veiculo_nome = '$veiculo_nome' AND veiculo_placa = '$veiculo_placa' AND data_abertura = '$data_abertura'";

    $result = $conn->query($verifica_ordem_sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ordem_servico_id = $row["ordem_servico_id"];

        // Remova a OS das tabelas relacionadas (produtos_ordem_servico, servicos_ordem_servico)
        $delete_produtos_sql = "DELETE FROM produtos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";
        $delete_servicos_sql = "DELETE FROM servicos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";

        if ($conn->query($delete_produtos_sql) === TRUE && $conn->query($delete_servicos_sql) === TRUE) {
            // Remova a OS da tabela ordem_servico
            $delete_os_sql = "DELETE FROM ordem_servico WHERE id = '$ordem_servico_id'";
            if ($conn->query($delete_os_sql) === TRUE) {
                // Remova a OS da tabela ordem_servico_completa
                $delete_os_completa_sql = "DELETE FROM ordem_servico_completa WHERE ordem_servico_id = '$ordem_servico_id'";
                if ($conn->query($delete_os_completa_sql) === TRUE) {
                    // Remova a linha na tabela valores relacionada ao ordem_servico_id
                    $delete_valores_sql = "DELETE FROM valores WHERE id_op = '$ordem_servico_id'";
                    if ($conn->query($delete_valores_sql) === TRUE) {
                        echo '<script>
                                alert("Ordem de Serviço cancelada com sucesso!");
                                window.location.href = "pagina_de_redirecionamento.html";
                              </script>';
                    } else {
                        echo "Erro ao excluir a linha na tabela valores: " . $conn->error;
                    }
                } else {
                    echo "Erro ao excluir a OS da tabela ordem_servico_completa: " . $conn->error;
                }
            } else {
                echo "Erro ao excluir a OS da tabela ordem_servico: " . $conn->error;
            }
        } else {
            echo "Erro ao excluir produtos ou serviços relacionados à OS: " . $conn->error;
        }
    } else {
        // OS não encontrada na tabela ordem_servico_completa
        echo "Ordem de serviço não encontrada.";
    }
}

$conn->close();
?>

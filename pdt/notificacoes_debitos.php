<?php
// Conexão com o banco de dados
require_once "config.php";

// Consulta SQL para obter os débitos com data de vencimento próxima
$sql = "SELECT id, nome, data_debito FROM debitos WHERE data_debito >= CURDATE() AND data_debito <= CURDATE() + INTERVAL 3 DAY";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $debito_id = $row['id'];
        $nome = $row['nome'];
        $data_debito = $row['data_debito'];

        // Calcule a diferença entre a data de vencimento e a data atual
        $data_atual = date('Y-m-d');
        $diferenca = strtotime($data_debito) - strtotime($data_atual);
        $dias_faltantes = floor($diferenca / (60 * 60 * 24));

        // Crie a mensagem da notificação com base na diferença de dias
        if ($dias_faltantes > 0) {
            $mensagem = "Faltam $dias_faltantes dias para o vencimento do boleto $nome";
        } elseif ($dias_faltantes === 0) {
            $mensagem = "O boleto $nome vence hoje";
        } else {
            $mensagem = "O boleto $nome está atrasado";
        }

        // Insira a notificação no banco de dados
        $sql = "INSERT INTO notificacoes (mensagem, data) VALUES ('$mensagem', CURDATE())";
        if ($conn->query($sql) === TRUE) {
            echo "Notificação criada com sucesso: $mensagem<br>";
        } else {
            echo "Erro ao criar notificação: " . $conn->error;
        }
    }
} else {
    echo "Nenhum débito próximo encontrado.";
}

$conn->close();
?>

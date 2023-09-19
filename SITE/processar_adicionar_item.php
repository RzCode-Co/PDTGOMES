<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    $nome = $_POST["nome"];
    $referencia = $_POST["referencia"];
    $marca = $_POST["marca"];
    $aplicacao = $_POST["aplicacao"];
    $ano = $_POST["ano"];
    $quantidade = $_POST["quantidade"];
    $valor_custo = $_POST["valor_custo"];
    $valor_varejo = $_POST["valor_varejo"];
    $valor_atacado = $_POST["valor_atacado"];
    $local = $_POST["local"];

    // Verifica se o item já existe no estoque
    $verifica_sql = "SELECT * FROM estoque WHERE nome = '$nome' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano' AND valor_varejo = '$valor_varejo' AND valor_atacado = '$valor_atacado' AND valor_custo = '$valor_custo'";
    $verifica_result = $conn->query($verifica_sql);

    if ($verifica_result->num_rows > 0) {
        // Atualiza a quantidade existente
        $update_sql = "UPDATE estoque SET quantidade = quantidade + $quantidade WHERE nome = '$nome' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano' AND valor_varejo = '$valor_varejo' AND valor_atacado = '$valor_atacado' AND valor_custo = '$valor_custo'";
        if ($conn->query($update_sql) === TRUE) {
            echo '<script>alert("Quantidade adicionada ao estoque com sucesso!");</script>';
            echo '<script>window.location.href = "Estoque.html";</script>';
            $dataVenda = date("d-m-Y");
                          
                          // Insira a notificação no banco de dados de notificações
                          $sql = "INSERT INTO notificacoes (mensagem, data) VALUES ('$nome foi atualizada a quantidade do seu estoque', NOW())";
                          
                          if ($conn->query($sql) === TRUE) {
                              echo "Notificação de atualização criada com sucesso.";
                          } else {
                              echo "Erro ao criar notificação de atualização: " . $conn->error;
                          }
        } else {
            echo '<script>alert("Erro ao atualizar a quantidade: ' . $conn->error . '");</script>';
            echo '<script>window.location.href = "Estoque.html";</script>';
        }
    } else {
        // Insere um novo registro no estoque
        $inserir_sql = "INSERT INTO estoque (nome, referencia, marca, aplicacao, ano, quantidade, valor_custo, valor_varejo, valor_atacado, localizacao) VALUES ('$nome', '$referencia', '$marca', '$aplicacao', '$ano', '$quantidade', '$valor_custo', '$valor_varejo', '$valor_atacado', '$local')";
        if ($conn->query($inserir_sql) === TRUE) {
            echo '<script>alert("Item adicionado ao estoque com sucesso!");</script>';
            echo '<script>window.location.href = "Estoque.html";</script>';
            $dataVenda = date("d-m-Y");
                          
                          // Insira a notificação no banco de dados de notificações
                          $sql = "INSERT INTO notificacoes (mensagem, data) VALUES ('$nome foi adicionado ao estoque em $quantidade quantidades', NOW())";
                          
                          if ($conn->query($sql) === TRUE) {
                              echo "Notificação de inserção criada com sucesso.";
                          } else {
                              echo "Erro ao criar notificação de inserção: " . $conn->error;
                          }
        } else {
            echo '<script>alert("Erro ao adicionar o item: ' . $conn->error . '");</script>';
            echo '<script>window.location.href = "Estoque.html";</script>';
        }
    }
}

$conn->close();
?>

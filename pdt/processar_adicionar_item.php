<?php
require_once "config.php";

// Verifique se um arquivo de imagem foi enviado
if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {
    // O arquivo foi enviado com sucesso.
    $nome_temporario = $_FILES["imagem"]["tmp_name"];
    $nome_arquivo = $_FILES["imagem"]["name"];
    // Verifique se não houve erros durante o upload
    if ($imagem['error'] === 0) {
        $caminho_imagem = 'C:\xampp\htdocs\imagens' . $imagem['name']; 

        // Mova o arquivo para o diretório de destino
        if (move_uploaded_file($nome_temporario, $diretorio_destino . $nome_arquivo)) {
            echo "A imagem foi salva com sucesso no servidor local.";
        } else {
            echo "Erro ao salvar a imagem no servidor local.";
        }
        }
    } else {
        echo 'Erro durante o upload da imagem.';
    }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    // Obtenha os valores do formulário
    $nome = strtoupper($_POST["nome"]);
    $referencia = strtoupper($_POST["referencia"]);
    $marca = strtoupper($_POST["marca"]);
    $aplicacao = strtoupper($_POST["aplicacao"]);
    $ano = strtoupper($_POST["ano"]);
    $quantidade = strtoupper($_POST["quantidade"]);
    $valor_custo = strtoupper($_POST["valor_custo"]);
    $valor_varejo = strtoupper($_POST["valor_varejo"]);
    $valor_atacado = strtoupper($_POST["valor_atacado"]);
    $local = strtoupper($_POST["local"]);

    // Consulta SQL para verificar se o item já existe
    $verifica_sql = "SELECT * FROM estoque WHERE nome = ? AND referencia = ? AND marca = ? AND aplicacao = ? AND ano = ? AND valor_varejo = ? AND valor_atacado = ? AND valor_custo = ?";
    
    // Preparar a declaração
    $verifica_stmt = $conn->prepare($verifica_sql);

    // Vincular parâmetros
    $verifica_stmt->bind_param("ssssssss", $nome, $referencia, $marca, $aplicacao, $ano, $valor_varejo, $valor_atacado, $valor_custo);

    // Executar a consulta
    $verifica_stmt->execute();

    // Obter resultados
    $verifica_result = $verifica_stmt->get_result();

    if ($verifica_result->num_rows > 0) {
        // Atualizar a quantidade existente
        $update_sql = "UPDATE estoque SET quantidade = quantidade + ? WHERE nome = ? AND referencia = ? AND marca = ? AND aplicacao = ? AND ano = ? AND valor_varejo = ? AND valor_atacado = ? AND valor_custo = ?";
        
        // Preparar a declaração de atualização
        $update_stmt = $conn->prepare($update_sql);

        // Vincular parâmetros
        $update_stmt->bind_param("dssssssss", $quantidade, $nome, $referencia, $marca, $aplicacao, $ano, $valor_varejo, $valor_atacado, $valor_custo);

        // Executar a atualização
        if ($update_stmt->execute()) {
            echo '<script>alert("Quantidade adicionada ao estoque com sucesso!");</script>';
            echo '<script>window.location.href = "Estoque.php";</script>';
            $dataVenda = date("d-m-Y");
            
            // Insira a notificação no banco de dados de notificações
            $sql = "INSERT INTO notificacoes (mensagem, data) VALUES (? , NOW())";
            
            // Preparar a declaração de inserção de notificação
            $notificacao_stmt = $conn->prepare($sql);

            // Vincular parâmetros
            $mensagem = "$nome foi atualizada a quantidade do seu estoque";
            $notificacao_stmt->bind_param("s", $mensagem);

            if ($notificacao_stmt->execute()) {
                echo "Notificação de atualização criada com sucesso.";
            } else {
                echo "Erro ao criar notificação de atualização: " . $conn->error;
            }
        } else {
            echo '<script>alert("Erro ao atualizar a quantidade: ' . $update_stmt->error . '");</script>';
            echo '<script>window.location.href = "Estoque.php";</script>';
        }
    } else {
        // Inserir um novo registro no estoque
        $inserir_sql = "INSERT INTO estoque (nome, referencia, marca, aplicacao, ano, quantidade, valor_custo, valor_varejo, valor_atacado, localizacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Preparar a declaração de inserção
        $inserir_stmt = $conn->prepare($inserir_sql);

        // Vincular parâmetros
        $inserir_stmt->bind_param("ssssssssss", $nome, $referencia, $marca, $aplicacao, $ano, $quantidade, $valor_custo, $valor_varejo, $valor_atacado, $local);

        // Executar a inserção
        if ($inserir_stmt->execute()) {
            echo '<script>alert("Item adicionado ao estoque com sucesso!");</script>';
            echo '<script>window.location.href = "Estoque.php";</script>';
            $dataVenda = date("d-m-Y");
            
            // Insira a notificação no banco de dados de notificações
            $sql = "INSERT INTO notificacoes (mensagem, data) VALUES (? , NOW())";
            
            // Preparar a declaração de inserção de notificação
            $notificacao_stmt = $conn->prepare($sql);

            // Vincular parâmetros
            $mensagem = "$nome foi adicionado ao estoque em $quantidade quantidades";
            $notificacao_stmt->bind_param("s", $mensagem);

            if ($notificacao_stmt->execute()) {
                echo "Notificação de inserção criada com sucesso.";
            } else {
                echo "Erro ao criar notificação de inserção: " . $conn->error;
            }
        } else {
            echo '<script>alert("Erro ao adicionar o item: ' . $inserir_stmt->error . '");</script>';
            echo '<script>window.location.href = "Estoque.php";</script>';
        }
    }

    // Fechar as declarações
    $verifica_stmt->close();
    $update_stmt->close();
    $inserir_stmt->close();
    $notificacao_stmt->close();
}

$conn->close();
?>
<?php
// Inicie a sessão
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se o usuário não estiver logado, redirecione para a página de login
    header("Location: ../HTML/index.html");
    exit();
}

// Você agora pode acessar as informações do usuário a partir de $_SESSION
$idUsuario = $_SESSION['id'];
$nomeUsuario = $_SESSION['nome'];
$cargoUsuario = $_SESSION['cargo'];
$arquivo = $_SESSION['arquivo'];

require_once "config.php"; // Arquivo de configuração do banco de dados

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente_nome = removeAcentos($_POST["cliente_nome"]);
    $cpf_cnpj = $_POST["cpf_cnpj"];
    // Verifique se a escolha do usuário (CPF ou CNPJ) é válida
    if ($_POST["cpf_cnpj"] == "CPF") {
        $CPF = $_POST["CPF"];
        $CNPJ = 0; // Defina CNPJ como nulo
    } elseif ($_POST["cpf_cnpj"] == "CNPJ") {
        $CNPJ = $_POST["CNPJ"];
        $CPF = 0; // Defina CPF como nulo
    } else {
        // Trate o caso em que nenhum dos campos foi escolhido
        echo '<script>
            alert("Escolha CPF ou CNPJ.");
            window.location.href = "Criação OS.php";
        </script>';
        exit; // Saia do script
    }
    $nomeFantasia = removeAcentos($_POST['nome_fantasia']);
    $email = strtoupper($_POST['email']);
    $telefone = removeAcentos($_POST['telefone']);
    $endereco = strtoupper($_POST['endereco']);
    $cep = $_POST['CEP'];
    $veiculo_nome = removeAcentos($_POST["veiculo_nome"]);
    $veiculo_placa = removeAcentos($_POST["veiculo_placa"]);
    $data_abertura = $_POST["data_abertura"];
    $observacoes_vendedor = removeAcentos($_POST["observacoes_vendedor"]);

    // Inicialize os totais para produtos e serviços
    $preco_total_produtos = 0;
    $preco_total_servicos = 0;

    // Processar os produtos vendidos
    $produtos = [];
    for ($i = 0; $i < count($_POST["codigo_produto"]); $i++) {
        $codigo_produto = removeAcentos($_POST["codigo_produto"][$i]);
        $produto_nome = removeAcentos($_POST["produto"][$i]);
        $referencia = removeAcentos($_POST["referencia"][$i]);
        $tipo = removeAcentos($_POST["tipo"][$i]);
        $quantidade = $_POST["quantidade"][$i];
        $preco_produto = $_POST["preco"][$i];

        // Consulta SQL para obter dados do estoque para o produto atual
        $sql = "SELECT quantidade, valor_avista, valor_prazo FROM estoque WHERE id = '$codigo_produto' AND nome = '$produto_nome' AND referencia = '$referencia'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $quantidade_disponivel = $row["quantidade"];
            $valor_avista = $row["valor_avista"];
            $valor_prazo = $row["valor_prazo"];

            // Verificar se há quantidade disponível no estoque
            if ($quantidade_disponivel >= $quantidade) {
                // Calcular o subtotal deste produto
                $subtotal_produto = $quantidade * $preco_produto;
                $preco_total_produtos += $subtotal_produto;

                // Armazenar os detalhes do produto
                $produtos[] = [
                    'codigo_produto' => $codigo_produto,
                    'produto' => $produto_nome,
                    'referencia' => $referencia,
                    'tipo' => $tipo,
                    'quantidade' => $quantidade,
                    'preco_produto' => $preco_produto,
                    'subtotal_produto' => $subtotal_produto
                ];
                
            if (isset($_POST["pagamento_previo"]) && $_POST["pagamento_previo"] == "1") {
                $pagamento_previo  = true; // Checkbox marcado, definir como TRUE
            } else {
                $pagamento_previo  = false; // Checkbox não marcado, definir como FALSE
                // Atualizar a quantidade disponível no estoque
                $quantidade_disponivel -= $quantidade;
                // Atualizar a quantidade disponível no estoque
                $sql = "UPDATE estoque SET quantidade = $quantidade_disponivel WHERE id = '$codigo_produto' AND nome = '$produto_nome' AND referencia = '$referencia'";
                $conn->query($sql);
            }
            } else {
                echo "Quantidade insuficiente em estoque para o produto $codigo_produto - $produto_nome - $referencia.";
                exit; // Sai do script em caso de quantidade insuficiente
            }
        }
    }

    // Processar os serviços prestados
    $servicos = [];
    for ($i = 0; $i < count($_POST["servico_nome"]); $i++) {
        $servico_nome = removeAcentos($_POST["servico_nome"][$i]);
        $tecnico_responsavel = removeAcentos($_POST["tecnico_responsavel"][$i]);
        $valor_servico = $_POST["valor_servico"][$i];
        $forma_pagamento = $_POST["forma_pagamento"];
        $numero_parcelas = ($forma_pagamento === "Parcelado") ? $_POST["numero_parcelas"] : null;
        $numero_parcelas = ($forma_pagamento === "Boleto") ? $_POST["numero_parcelas"] : null;

        // Calcular o preço total dos serviços
        $preco_total_servicos += $valor_servico;

        // Armazenar os detalhes do serviço
        $servicos[] = [
            'servico_nome' => $servico_nome,
            'tecnico_responsavel' => $tecnico_responsavel,
            'valor_servico' => $valor_servico,
            'forma_pagamento' => $forma_pagamento,
            'numero_parcelas' => $numero_parcelas
        ];
    }

    // Calcular o preço total geral (produtos + serviços)
    $preco_total_geral = $preco_total_produtos + $preco_total_servicos;

    $valor_debito = NULL;
    // Inserir os dados na tabela ordem_servico
    $status = "Em andamento";
    $sql = "INSERT INTO ordem_servico (cliente_nome, cpf_cnpj, CPF, CNPJ, veiculo_nome, veiculo_placa, data_abertura, status) VALUES ('$cliente_nome', '$cpf_cnpj','$CPF', '$CNPJ', '$veiculo_nome', '$veiculo_placa', '$data_abertura', '$status')";

    if ($conn->query($sql) === TRUE) {
        // Obter o ID da ordem de serviço inserida
        $ordem_servico_id = $conn->insert_id;

        // Inserir os produtos da ordem de serviço na tabela produtos_ordem_servico
        foreach ($produtos as $produto) {

            $sql = "INSERT INTO produtos_ordem_servico (ordem_servico_id, codigo_produto, produto, referencia, tipo, quantidade, preco_produto) VALUES ('$ordem_servico_id', '$codigo_produto', '$produto_nome', '$referencia', '$tipo', '$quantidade', '$preco_produto')";
            $conn->query($sql);
        }

        // Inserir os serviços da ordem de serviço na tabela servicos_ordem_servico
        foreach ($servicos as $servico) {
            $servico_nome = $servico['servico_nome'];
            $tecnico_responsavel = $servico['tecnico_responsavel'];
            $valor_servico = $servico['valor_servico'];

            $sql = "INSERT INTO servicos_ordem_servico (ordem_servico_id, servico_nome, tecnico_responsavel, valor_servico) VALUES ('$ordem_servico_id', '$servico_nome', '$tecnico_responsavel', $valor_servico)";
            $conn->query($sql);
        }

        echo "Ordem de serviço registrada com sucesso!";
    } else {
        echo "Erro ao inserir a ordem de serviço: " . $conn->error;
    }

    // Consulta SQL para verificar o ID da ordem de serviço
    $verifica_ordem_id = "SELECT id FROM ordem_servico WHERE cliente_nome = '$cliente_nome' AND veiculo_nome = '$veiculo_nome' AND veiculo_placa = '$veiculo_placa' AND data_abertura = '$data_abertura'";
    $result_id = $conn->query($verifica_ordem_id);

    if ($result_id->num_rows > 0) {
        $row = $result_id->fetch_assoc();
        $id_op = $row["id"];

        // Consulta SQL para inserir valores na tabela "valores" com o ID da ordem de serviço
        $sql = "INSERT INTO valores (id_op, data_venda, valor_venda, valor_servico, preco_total_geral, valor_debito) VALUES('$id_op', '$data_abertura','$preco_total_produtos', '$preco_total_servicos', '$preco_total_geral', '$valor_debito')";

        if ($conn->query($sql) === TRUE) {
            echo "Valores atualizados.";
        } else {
            echo "Erro ao atualizar valor de venda: " . $conn->error;
        }
    } else {
        echo "Ordem de serviço não encontrada.";
    }
    $status = "Em andamento";
    $sql = "INSERT INTO ordem_servico_completa (ordem_servico_id, codigo_produto, cliente_nome, nome_fantasia, cpf_cnpj, CPF, CNPJ, email, telefone, endereco, CEP, veiculo_nome, veiculo_placa, data_abertura, produto, referencia, tipo, quantidade, preco_total_produto, servico_nome, tecnico_responsavel, preco_total_servico, preco_total_geral, observacoes_vendedor, forma_pagamento, numero_parcelas, pagamento_previo, status) VALUES ('$ordem_servico_id', '$codigo_produto', '$cliente_nome', '$nomeFantasia', '$cpf_cnpj', '$CPF', '$CNPJ', '$email', '$telefone', '$endereco', '$cep', '$veiculo_nome', '$veiculo_placa', '$data_abertura', '$produto_nome', '$referencia', '$tipo', '$quantidade', '$preco_total_produtos', '$servico_nome', '$tecnico_responsavel', '$preco_total_servicos', '$preco_total_geral', '$observacoes_vendedor','$forma_pagamento','$numero_parcelas','$pagamento_previo', '$status')";


    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Ordem de Serviços criada com sucesso!');</script>";
    } else {
        echo "Erro ao inserir dados na tabela ordem_servico_completa: " . $conn->error;
    }

    // Insira a notificação no banco de dados de notificações
    $sql = "INSERT INTO notificacoes (mensagem, data) VALUES ('Uma nova Ordem de Serviço foi criada', NOW())";
                          
    if ($conn->query($sql) === TRUE) {
        echo "Notificação de atualização criada com sucesso.";
    } else {
        echo "Erro ao criar notificação de atualização: " . $conn->error;
    }

    $conn->close();
    header("Location:Criação OS.php");
}
?>
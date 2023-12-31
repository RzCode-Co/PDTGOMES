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

// Conexão com o banco de dados
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_comprador = formatarTexto($_POST["nome_comprador"]);
    $nome_peca = formatarTexto($_POST["nome_peca"]);
    $marca = formatarTexto($_POST["marca"]);
    $ano = formatarTexto($_POST["ano"]);
    $referencia = formatarTexto($_POST["referencia"]);
    $aplicacao = formatarTexto($_POST["aplicacao"]);
    $quantidade = $_POST["quantidade"];
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
            window.location.href = "../PHP/Venda.php";
        </script>';
        exit; // Saia do script
    }

    $forma_pagamento = $_POST["forma_pagamento"];
    $numero_parcelas = ($forma_pagamento === "Parcelado") ? $_POST["numero_parcelas"] : null;
    $numero_parcelas = ($forma_pagamento === "Boleto") ? $_POST["numero_parcelas"] : null;
    $valor_venda = $_POST["valor_venda"];
    $funcionario_vendedor = formatarTexto($_POST["funcionario_vendedor"]);
    $garantia_produto = $_POST["garantia_produto"];

    function formatarTexto($texto) {
        // Remove a acentuação
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

        // Converte para maiúsculas
        $texto = mb_strtoupper($texto, 'UTF-8');

        return $texto;
    }

    // Consulta SQL para verificar se o produto existe com base no nome e outros critérios
    $verificar_produto_sql = "SELECT id, quantidade FROM estoque WHERE nome = '$nome_peca' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";

    $result = $conn->query($verificar_produto_sql);

    if ($result->num_rows > 0) {
        // O produto existe e atende aos critérios

        $row = $result->fetch_assoc();
        $estoque_disponivel = $row["quantidade"];

        // Verificar se a quantidade é suficiente
        if ($quantidade > 0) {
            $data_abertura = date("d-m-Y");
            // Consulta SQL para inserir a venda na tabela "vendas"
            $sql = "INSERT INTO vendas (nome_comprador, nome_peca, marca, ano, referencia, aplicacao, quantidade, cpf_cnpj, CPF, CNPJ, forma_pagamento, numero_parcelas, valor_venda, funcionario_vendedor, garantia_produto, data_venda) VALUES ('$nome_comprador', '$nome_peca', '$marca', '$ano', '$referencia', '$aplicacao', '$quantidade', '$cpf_cnpj', '$CPF', '$CNPJ', '$forma_pagamento', '$numero_parcelas', '$valor_venda', '$funcionario_vendedor', '$garantia_produto', '$data_abertura')";

            // Executar a consulta de inserção
            if ($conn->query($sql) === TRUE) {
                // Obtém o ID da venda recém-inserida
                $venda_id = $conn->insert_id;

                // Consulta SQL para atualizar a quantidade no estoque
                $update_sql = "UPDATE estoque SET quantidade = quantidade - '$quantidade' WHERE id = '" . $row["id"] . "'";

                // Executar a consulta de atualização
                if ($conn->query($update_sql) === TRUE) {
                    $dataVenda = NULL;
                    // Insira a notificação no banco de dados de notificações
                    $sql_notificacao = "INSERT INTO notificacoes (mensagem, data) VALUES ('$funcionario_vendedor realizou uma venda de um(a) $nome_peca no valor de $valor_venda em $dataVenda', NOW())";

                    if ($conn->query($sql_notificacao) === TRUE) {
                        $datavaluca = date("Y-m-d");
                        $valor_servico = NULL;
                        $preco_total_geral = $valor_venda;
                        $valor_debito = NULL;
                        // Consulta SQL para inserir valores na tabela "valores"
                        $sql_valores = "INSERT INTO valores (id_op, data_venda, valor_venda, valor_servico, preco_total_geral, valor_debito) VALUES ('$venda_id', '$datavaluca', '$valor_venda', '$valor_servico', '$preco_total_geral', '$valor_debito')";

                        if ($conn->query($sql_valores) === TRUE) {
                            echo '<script>
                                    alert("Venda registrada com sucesso! Quantidade no estoque atualizada.");
                                    window.location.href = "../PHP/Venda.php";
                                  </script>';
                        } else {
                            echo "Erro ao atualizar valores de venda: " . $conn->error;
                        }
                    } else {
                        echo "Erro ao criar notificação de venda: " . $conn->error;
                    }
                } else {
                    echo "Erro ao atualizar a quantidade no estoque: " . $conn->error;
                }
            } else {
                echo "Erro ao registrar a venda: " . $conn->error;
            }
        } else {
            // A quantidade é insuficiente, exiba um alerta e redirecione para a página venda.html com uma mensagem de erro
            echo '<script>
                    alert("Quantidade para venda Insuficiente!");
                    window.location.href = "../PHP/Venda.php";
                  </script>';
        }
    } else {
        // O produto não existe ou não atende aos critérios
        echo "Produto não encontrado ou valor de venda não atende aos critérios.";
    }
}

$conn->close();
?>
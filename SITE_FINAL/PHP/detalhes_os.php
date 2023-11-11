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

// Verifique se o parâmetro 'ordem_servico_id' está definido na URL
if (isset($_GET['ordem_servico_id'])) {
    $ordem_servico_id = $_GET['ordem_servico_id'];

    // Consulta SQL para recuperar a ordem de serviço com o ID especificado
    $sql = "SELECT * FROM ordem_servico_completa WHERE ordem_servico_id = $ordem_servico_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $os_details = $result->fetch_assoc(); // Apenas uma ordem de serviço deve ser recuperada
    } else {
        echo "<p>Nenhuma Ordem de Serviço encontrada com o ID especificado.</p>";
    }
} else {
    echo "<p>ID da Ordem de Serviço não especificado na URL.</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Detalhes da Ordem de Serviço</title>
    <link rel="stylesheet" href="../CSS/detalhes_os.css">
</head>

<body>
    <div class="titulo_icone">
        <?php
        if ($os_details['status'] == 'Em Andamento') {
            echo "<a id='icone_voltar' href='consultar_ordens_servico.php'><img src='../CSS/img/voltar.svg' alt='voltar página'></a>";
        } else if ($os_details['status'] == 'Concluída') {
            echo "<a id='icone_voltar' href='consultar_ordens_servicos_concluidas.php'><img src='../CSS/img/voltar.svg' alt='voltar página'></a>";
        }
        ?>
        <h2>Detalhes da Ordem de Serviço</h2>
    </div>


    <div class="tabela_os">

        <?php
        if (!empty($os_details)) {
            echo "<h3>Ordem de Serviço ID: {$os_details['ordem_servico_id']}</h3>";
            echo "<table>";
            echo "<tr><th>ID</th><td>{$os_details['ordem_servico_id']}</td></tr>";
            echo "<tr><th>Cliente</th><td>{$os_details['cliente_nome']}</td></tr>";
            echo "<tr><th>Veículo</th><td>{$os_details['veiculo_nome']}</td></tr>";
            echo "<tr><th>Nome Fantasia</th><td>{$os_details['nome_fantasia']}</td></tr>";
            // Exibir CPF ou CNPJ com base nos valores
            if ($os_details['CPF'] !== null && $os_details['CPF'] !== "0") {
                echo "<tr><th>CPF</th><td>{$os_details['CPF']}</td></tr>";
            } elseif ($os_details['CNPJ'] !== null && $os_details['CNPJ'] !== "0") {
                echo "<tr><th>CNPJ</th><td>{$os_details['CNPJ']}</td></tr>";
            } else {
                // Se nenhum CPF ou CNPJ for fornecido, você pode mostrar uma mensagem padrão ou deixar em branco
                echo "<tr><th>CPF/CNPJ</th><td>Não fornecido</td></tr>";
            }
            echo "<tr><th>Placa do Veículo</th><td>{$os_details['veiculo_placa']}</td></tr>";
            echo "<tr><th>Telefone</th><td>{$os_details['telefone']}</td></tr>";
            echo "<tr><th>E-mail</th><td>{$os_details['email']}</td></tr>";
            echo "<tr><th>Endereco</th><td>{$os_details['endereco']}</td></tr>";
            echo "<tr><th>CEP</th><td>{$os_details['CEP']}</td></tr>";
            echo "<tr><th>Data de Abertura</th><td>{$os_details['data_abertura']}</td></tr>";
            echo "<tr><th>Status</th><td>{$os_details['status']}</td></tr>";
            echo '<tr><th>Atualizar Status</th><form method="POST" action="atualizar_status.php">
            <td><input type="hidden" name="ordem_servico_id" value="' . $os_details['ordem_servico_id'] . '">
            <label for="novo_status">Novo Status:</label>
            <select name="novo_status" id="novo_status">
                <option value="Em Andamento">Em Andamento</option>
                <option value="Concluída">Concluída</option>
                <option value="Encerrada">Encerrada</option>
            </select>
            <button type="submit" name="atualizar_status">Atualizar Status</button>
            </form></td></tr>';
            echo "</table>";

            // Quebra de linha para exibir em colunas separadas
            echo "<br>";

            // Exibir a lista de produtos
            $ordem_servico_id = $os_details['ordem_servico_id']; // Alterado de $os para $os_details
            $sql_produtos = "SELECT * FROM produtos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
            $result_produtos = $conn->query($sql_produtos);

            if ($result_produtos->num_rows > 0) {
                // Exibir a lista de produtos
                echo "<h3>Produtos Vendidos</h3>";
                echo "<table>";
                echo "<tr><th>Código do Produto</th><th>Produto</th><th>Referência</th><th>Tipo</th><th>Quantidade</th><th>Preço Unitário</th></tr>";

                while ($produto = $result_produtos->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$produto['codigo_produto']}</td>";
                    echo "<td>{$produto['produto']}</td>";
                    echo "<td>{$produto['referencia']}</td>";
                    echo "<td>{$produto['tipo']}</td>";
                    echo "<td>{$produto['quantidade']}</td>";
                    echo "<td>{$produto['preco_produto']}</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<p>Nenhum produto encontrado para esta ordem de serviço.</p>";
            }

            // Informações adicionais sobre pagamento e observações
            $sql = "SELECT pagamento_previo FROM ordem_servico WHERE id = $ordem_servico_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $pagamentoPrevio = $row['pagamento_previo'];

                if ($pagamentoPrevio === '1') {
                    echo "Produtos já foram pagos previamente no caixa.";
                } else {
                    echo "Produtos devem ser pagos no caixa.";
                }
            } else {
                echo "Informações de pagamento não encontradas no banco de dados.";
            }

            // Exiba as observações
            echo "<h3>Observações do Vendedor</h3>";
            $observacoes_vendedor = $os_details['observacoes_vendedor'];
            if (!empty($observacoes_vendedor)) {
                echo "<p>{$observacoes_vendedor}</p>";
            } else {
                echo "<p>Nenhuma observação foi inserida.</p>";
            }

            // Exiba a lista de serviços prestados
            echo "<h3>Serviços Prestados</h3>";
            echo "<table>";
            echo "<tr><th>Nome do Serviço</th><th>Técnico Responsável</th><th>Valor do Serviço</th></tr>";

            // Consulta SQL para recuperar os serviços prestados para esta ordem de serviço
            $sqlServicos = "SELECT * FROM servicos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
            $resultServicos = $conn->query($sqlServicos);

            while ($servico = $resultServicos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$servico['servico_nome']}</td>";
                echo "<td>{$servico['tecnico_responsavel']}</td>";
                echo "<td>{$servico['valor_servico']}</td>";
                echo "</tr>";
            }

            echo "</table>";

            // Exibir o valor total
            echo "<div class='valor_total'>";
            echo "<p>Valor Total: {$os_details['preco_total_geral']}</p>";
            echo "</div>";

            // Quebra de linha para exibir em colunas separadas
            echo "<br>";

        } else {
            echo "<p>Nenhuma Ordem de Serviço encontrada com o ID especificado.</p>";
        }
        ?>
    </div>

    <div class="baixar_os">
        <form method="get" action="download_os.php">
            <input type="hidden" name="ordem_servico_id" value="<?php echo $ordem_servico_id ?>">
            <button type="submit">Baixar OS</button>
        </form>
    </div class="baixar_os">

</body>

</html>
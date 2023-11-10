<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Verifique se os parâmetros 'CNPJ' e 'CPF' estão definidos na URL
if (isset($_POST['CNPJ']) && isset($_POST['CPF'])) {
    $clienteCNPJ = $_POST['CNPJ'];
    $clienteCPF = $_POST['CPF'];

    // Se o CNPJ não for 0 ou null, faça a consulta considerando o CNPJ
    if ($clienteCNPJ !== null && $clienteCNPJ !== "0") {
        $sql = "SELECT * FROM ordem_servico_completa WHERE CNPJ = '$clienteCNPJ'";
        $sql_usuarios = "SELECT * FROM usuarios WHERE CNPJ = '$clienteCNPJ'";
    }
    // Se o CNPJ for 0 ou null, faça a consulta considerando o CPF
    else {
        $sql = "SELECT * FROM ordem_servico_completa WHERE CPF = '$clienteCPF'";
        $sql_usuarios = "SELECT * FROM usuarios WHERE CPF = '$clienteCPF'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $os_details = $result->fetch_assoc(); // Apenas uma ordem de serviço deve ser recuperada
        $codigo = $os_details['codigo_produto'];
        $ordem_servico_id = $os_details['ordem_servico_id'];

        // Consulta SQL para recuperar informações do estoque com base no código do produto
        $sqlEstoque = "SELECT * FROM estoque WHERE id = $codigo";
        $resultEstoque = $conn->query($sqlEstoque);

        if ($resultEstoque->num_rows > 0) {
            $historico = $resultEstoque->fetch_assoc(); // Apenas uma linha do estoque deve ser recuperada
        } else {
            // Caso não haja correspondência no estoque
            echo "Produto não encontrado no estoque.";
        }
    } else {
        // Caso não haja correspondência na ordem de serviço
        echo "Ordem de serviço não encontrada para o CNPJ ou CPF fornecido.";
    }
    $result = $conn->query($sql_usuarios);
    if($result->num_rows > 0){
        $usuario = $result->fetch_assoc();
    }
} else {
    // Caso os parâmetros 'CNPJ' ou 'CPF' não estejam definidos na URL
    echo "Parâmetros 'CNPJ' ou 'CPF' não encontrados na requisição.";
}

date_default_timezone_set('America/Sao_Paulo');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordem de Serviço</title>
    <link rel="stylesheet" href="../CSS/gerar_pdf.css">

</head>

<body>

    <div id="content">
        <div class="cabecalho">
            <div class="div_direita_img">
                <img src="../CSS/img/Logomarca_oficial.jpg" alt="">
                <div class="div_direita">
                    <h1>PDT P GOMES</h1>
                    <p>VENDA RÁPIDA</p>
                    <p>Data da venda: <?php echo date('d/m/Y', strtotime($os_details['data_abertura'])); ?></p>
                </div>
            </div>

            <div class="div_esquerda">
                <h3>RZSystem</h3>
                <p>Data/Hora: <?php date_default_timezone_set('America/Sao_Paulo'); echo date('d/m/Y H:i:s'); ?></p>
                <p>Usuário:<?php echo $usuario['nome'] ?> </p>
            </div>
        </div>

        <div class="cabecalho_2">
            <div class="line"></div>
            <p>AV. WILSON ROSADO - 6 - ALTO SUMARE - Mossoró - RN</p>
            <p>CNPJ/CPF: 05236182000118 - Inscrição Estadual: 200920383 - Fone: 8433121151</p>
            <div class="line"></div>
            <h2>
                Nº VENDA: <?php echo $ordem_servico_id; ?> DATA: <?php echo $os_details['data_abertura']; ?>
            </h2>
        </div>

        <div class="cliente">
            <div class="cliente_esquerda">
                <h3>Cliente</h3>
                <p>Nome: <?php echo $os_details['cliente_nome']; ?> - CNPJ/CPF: <?php echo ($os_details['CPF'] !== null && $os_details['CPF'] !== "0") ? $os_details['CPF'] : $os_details['CNPJ']; ?></p>
                <p>Nome Fantasia: <?php echo $os_details['nome_fantasia']; ?></p>
                <p>Endereço: <?php echo $os_details['endereco']; ?></p>
                <p>Email: <?php echo $os_details['email']; ?> - Fone: <?php echo $os_details['telefone']; ?></p>
                <p>Data Cadastro: <?php echo $os_details['data_abertura']; ?></p>
                <p>OBS.: <?php echo $os_details['observacoes_vendedor']; ?></p>
            </div>
        </div>

        <div class="relacao_dos_produtos">
            <h3>Relação dos Produtos</h3>
            <table class="relacao_produtos">
                <tr>
                    <th id="pequeno">Cód.</th>
                    <th id="grande">Produto</th>
                    <th id="pequeno">Ref.</th>
                    <th id="pequeno">Tipo</th>
                    <th id="pequeno">Quant.</th>
                    <th id="pequeno">Preço(UN)</th>
                    <th id="pequeno">Valor Item</th>
                </tr>

                <?php
                    if (!empty($os_details)) {
                        echo "<tr>";
                        echo "<td>{$os_details['codigo_produto']}</td>";
                        echo "<td>{$os_details['produto']}</td>";
                        echo "<td>{$os_details['referencia']}</td>";
                        echo "<td>{$os_details['tipo']}</td>";
                        echo "<td>{$os_details['quantidade']}</td>";
                        echo "<td>{$historico['valor_varejo']}</td>";
                        echo "<td>{$os_details['preco_total_produto']}</td>";
                        echo "</tr>";
                    }
                    else {
                        echo '<p>Nenhum produto foi inserido nesta Ordem de Serviço.</p>';
                    }

                    // Consulta SQL para recuperar a informação de pagamento prévio
                    $sqlPagamentoPrevio = "SELECT pagamento_previo FROM ordem_servico WHERE id = $ordem_servico_id";
                    $resultPagamentoPrevio = $conn->query($sqlPagamentoPrevio);

                    if ($resultPagamentoPrevio->num_rows > 0) {
                        $rowPagamentoPrevio = $resultPagamentoPrevio->fetch_assoc();
                        $pagamentoPrevio = $rowPagamentoPrevio['pagamento_previo'];

                        if ($pagamentoPrevio === '1') {
                            echo 'Produtos foram pagos previamente no caixa.';
                        } else {
                            echo 'Produtos devem ser pagos no caixa.';
                        }
                    } else {
                        echo 'Informações de pagamento não encontradas no banco de dados.';
                    }

                ?>
            </table>

        </div class="relacao_dos_produtos">



        <div class="total_geral">
        <h2>Relação de Serviços</h2>
            <?php
                // Consulta SQL para recuperar os serviços prestados para esta ordem de serviço
                $sqlServicos = "SELECT * FROM servicos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
                $resultServicos = $conn->query($sqlServicos);

                echo '<div class="total_geral">';
                echo '<table>';
                echo '<tr><th>Nome do Serviço</th><th>Valor do Serviço</th></tr>';

                if ($resultServicos->num_rows > 0) {
                    while ($servico = $resultServicos->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $servico['servico_nome'] . '</td>';
                        echo '<td>R$ ' . $servico['valor_servico'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="2">Nenhum serviço encontrado para esta ordem de serviço.</td></tr>';
                }

                echo '</table>';
                echo '</div>';
            ?>
        </div>

        <div class="rodape_pagamentos">
            <?php
            if ($pagamentoPrevio === '1') {
                // Se os produtos foram pagos previamente, mostrar o valor total dos serviços
                $totalServicos = 0; // Inicialize o total dos serviços como zero

                // Consulta SQL para recuperar os serviços prestados para esta ordem de serviço
                $sqlServicos = "SELECT valor_servico FROM servicos_ordem_servico WHERE ordem_servico_id = $ordem_servico_id";
                $resultServicos = $conn->query($sqlServicos);

                while ($servico = $resultServicos->fetch_assoc()) {
                    $totalServicos += $servico['valor_servico'];
                }

                echo '<span>Total Geral: <strong>R$ ' . number_format($totalServicos, 2) . '</strong></span>';
            } else {
                // Se os produtos não foram pagos previamente, mostrar o valor total geral da Ordem de Serviço
                echo '<span>Total Geral: <strong>R$ ' . number_format($os_details['preco_total_geral'], 2) . '</strong></span>';
            }
            ?>
        </div>


        <div class="pagamentos">
            <h3>PAGAMENTOS</h3>
            <table>
                <tr>
                    <th id="pg_grande">Modalidade</th>
                    <th id="pg_grande">Condição de Pagamento</th>
                    <th id="pg_pequeno">Valor</th>
                </tr>

                <?php

                // Verifique se a compra está parcelada
                if ($os_details['numero_parcelas'] > 1) {
                    // Se houver parcelamento, mostre o número de parcelas e o valor da parcela
                    echo '<tr>';
                    echo '<td>' . $os_details['forma_pagamento'] . '</td>';
                    echo '<td>' . $os_details['numero_parcelas'] . 'x sem juros</td>';
                    echo '<td>R$ ' . number_format($os_details['preco_total_geral'], 2) . '</td>';
                    echo '</tr>';
                } else {
                    // Se for à vista, mostre "À vista" e o valor total
                    echo '<tr>';
                    echo '<td>' . $os_details['forma_pagamento'] . '</td>';
                    echo '<td>À vista</td>';
                    echo '<td>R$ ' . number_format($os_details['preco_total_geral'], 2) . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <div class="rodape">
        </div>
    </div>

</body>

</html>
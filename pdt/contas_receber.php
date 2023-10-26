<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "config.php"; 

    // Consulta SQL para buscar as vendas com forma de pagamento "Parcelado" e incluir a coluna valor_parcela
    $sql = "SELECT id, nome_comprador, numero_parcelas, data_venda, valor_parcela FROM vendas WHERE forma_pagamento = 'Parcelado'";

    $result = $conn->query($sql);

    // Verifique se a consulta foi bem-sucedida
    if ($result) {
        $currentDate = new DateTime(); // Data atual
        $itemsToUpdate = array(); // Array para armazenar as vendas a serem atualizadas

        while ($row = $result->fetch_assoc()) {
            $dataVenda = new DateTime($row['data_venda']);
            $diff = $currentDate->diff($dataVenda);

            if ($diff->m >= 1) {
                // Se passou 1 mês ou mais desde a venda, atualize a venda
                $itemsToUpdate[] = $row;
            }
        }

        // Atualize as vendas que completaram 1 mês
        foreach ($itemsToUpdate as $item) {
            $idVenda = $item['id'];
            $numeroParcelas = $item['numero_parcelas'];
            $valorParcela = $item['valor_parcela'];

            // Atualize o número de parcelas
            $newNumeroParcelas = $numeroParcelas - 1;

            if ($newNumeroParcelas <= 0) {
                $newNumeroParcelas = 0;
                $sql = "UPDATE vendas SET status = 'Completa' WHERE id = $idVenda";
                $conn->query($sql);
            }

            // Adicione o valor da parcela à tabela de valores
            $sql = "INSERT INTO valores (id_op, preco_total_geral, data_venda) VALUES ($idVenda, $valorParcela, NOW())";
            $conn->query($sql);

            // Atualize o número de parcelas na tabela de vendas
            $sql = "UPDATE vendas SET numero_parcelas = $newNumeroParcelas WHERE id = $idVenda";
            $conn->query($sql);
        }
    }

}

// Consulta SQL para buscar as vendas com forma de pagamento "Parcelado"
$sql = "SELECT nome_comprador, numero_parcelas, data_venda FROM vendas WHERE forma_pagamento = 'Parcelado'";

$result = $conn->query($sql);

// Array para armazenar o histórico de vendas
$historico = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Financeiro</title>
    </head>
    <style>
        body {
            background-color: gray; /* Define o fundo cinza */
            color: yellow; /* Define a cor do texto como amarelo */
        }
        
        #conteudo {
            margin: 20px; /* Adiciona margem para separar o conteúdo do cabeçalho e do menu lateral */
        }
        
        /* Estilização básica para o cabeçalho */
        #cabecalho {
            background-color: black; /* Cor de fundo do cabeçalho (pode ajustar conforme desejado) */
            color: white; /* Cor do texto no cabeçalho (pode ajustar conforme desejado) */
            padding: 10px; /* Espaçamento interno no cabeçalho */
            display: flex; /* Para alinhar os elementos do cabeçalho na horizontal */
            justify-content: space-between; /* Distribui os elementos horizontalmente */
        }
        
        #usuario-info {
            display: flex; /* Alinha os elementos do usuário na horizontal */
            align-items: center; /* Centraliza verticalmente os elementos do usuário */
        }
        
        #icone-notificacoes {
            /* Adicione estilos para o ícone de notificações, como tamanho, margem, etc. */
        }
        
        /* Estilização para o menu lateral */
        #menu-lateral {
            background-color: black; /* Cor de fundo do menu (pode ajustar conforme desejado) */
        }
        
        #menu-lateral ul {
            list-style-type: none; /* Remove marcadores de lista */
            padding: 0; /* Remove o preenchimento padrão da lista */
        }
        
        #menu-lateral ul li {
            margin: 0; /* Remove a margem padrão dos itens da lista */
        }
        
        #menu-lateral ul li a {
            display: block; /* Transforma os links em blocos para preencher o espaço disponível */
            padding: 10px 20px; /* Espaçamento interno nos links */
            color: white; /* Cor do texto dos links */
            text-decoration: none; /* Remove sublinhado dos links */
        }
        
        #menu-lateral ul li a:hover {
            background-color: gray; /* Cor de fundo quando o mouse passa por cima */
        }
</style>
    </style>
    <body>
        <div id="cabecalho">
            <div id="usuario-info">
                <img src="<?php echo $fotoUsuario; ?>" alt="Foto do Usuário">
                <p><?php echo $nomeUsuario; ?></p>
                <p><?php echo $cargoUsuario; ?></p>
            </div>
            <!-- Ícone de notificações -->
            <div id="icone-notificacoes">
                <img src="caminho-para-o-icone.png" alt="Ícone de Notificações">
            </div>
        </div>
        <!-- Seu menu lateral -->
        <div id="menu-lateral">
            <ul>
            <li><a href="inicio.php">Inicio</a></li>
                <li><a href="Venda.html">Venda</a></li>
                <li><a href="Financeiro.php">Financeiro</a></li>
                <li><a href="Graficos.php">Gráficos</a></li>
                <li><a href="Debitos.php">Debitos</a></li>
                <li><a href="Notificações.php">Notificações</a></li>
                <li><a href="Estoque.php">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <div id="contas-a-receber">
            <h1>Contas a Receber</h1>

            <?php
            // Defina o número de itens por página
            $itemsPerPage = 10;

            // Obtenha a página atual a partir dos parâmetros da URL
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

            $totalItems = count($historico);
            $totalPages = ceil($totalItems / $itemsPerPage);

            // Verifique se a página solicitada é válida
            if ($page < 1) {
                $page = 1;
            } elseif ($page > $totalPages) {
                $page = $totalPages;
            }

            // Calcule o índice de início e fim para os itens da página atual
            $startIndex = ($page - 1) * $itemsPerPage;
            $endIndex = min($startIndex + $itemsPerPage, $totalItems);

            // Verifique se há itens a serem exibir
            if (!empty($historico)) {
                echo '<table>';
                echo '<tr>
                        <th>Nome do comprador</th>
                        <th>Número de Parcelas</th>
                        <th>Data de Venda</th>
                    </tr>';

                for ($i = $startIndex; $i < $endIndex; $i++) {
                    echo '<tr>';
                    echo '<td>' . $historico[$i]['nome_comprador'] . '</td>';
                    echo '<td>' . $historico[$i]['numero_parcelas'] . 'x</td>';
                    echo '<td>' . $historico[$i]['data_venda'] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>Não há itens para exibir.</p>';
            }
            ?>

            <div id="pagination">
                <?php
                if ($totalPages > 1) {
                    $currentPage = $page;

                    echo '<ul class="pagination">';
                    if ($currentPage > 1) {
                        echo '<a href="contas_receber.php?page=1">&laquo;&laquo;</a>';
                        echo '<a href="contas_receber.php?page=' . ($currentPage - 1) . '">&laquo;</a>';
                    }

                    // Mostrar até 5 links de página
                    for ($i = max(1, $currentPage - 2); $i <= min($currentPage + 2, $totalPages); $i++) {
                        if ($i == $currentPage) {
                            echo '<strong>' . $i . '</strong>';
                        } else {
                            echo '<a href="contas_receber.php?page=' . $i . '">' . $i . '</a>';
                        }
                    }

                    if ($currentPage < $totalPages) {
                        echo '<a href="contas_receber.php?page=' . ($currentPage + 1) . '">&raquo;</a>';
                        echo '<a href="contas_receber.php?page=' . $totalPages . '">&raquo;&raquo;</a>';
                    }

                    echo '</ul>';
                }
                ?>
            </div>
        </div>
        <script>
            function atualizarVendasUmMes() {
                <?php
                    require_once "config.php";

                    // Execute o procedimento armazenado
                    $sql = "CALL AtualizarValorParcela()";
                    $sql = "CALL AtualizarValorParcelaOrdemCompleta()";
                    $sql = "CALL AtualizarValorParcelaVendas()";
                    if ($conn->query($sql) === TRUE) {
                        echo "Procedimento armazenado executado com sucesso.";
                    } else {
                        echo "Erro ao executar o procedimento armazenado: " . $conn->error;
                    }
                ?>
                
            }
        </script>
    </body>
</html>

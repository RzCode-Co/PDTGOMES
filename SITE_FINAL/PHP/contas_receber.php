<?php
require_once "config.php"; // Certifique-se de incluir a configuração do banco de dados

// Função para executar uma consulta SQL e retornar os resultados como um array
function executarConsulta($sql) {
    global $conn;
    $result = $conn->query($sql);

    if ($result === false) {
        die("Erro na consulta: " . $conn->error);
    }

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

// Consulta SQL para obter as informações de vendas parceladas
$sqlVendas = "SELECT cliente_nome, numero_parcelas, data_abertura, 'Venda' AS tipo FROM vendas WHERE forma_pagamento = 'Parcelado'";

// Consulta SQL para obter as informações de ordens de serviço parceladas
$sqlOrdens = "SELECT cliente_nome, numero_parcelas, data_abertura, 'Ordem de Serviço' AS tipo FROM ordem_servico_completa WHERE forma_pagamento = 'Parcelado'";

// Execute as consultas e combine os resultados em uma única matriz
$resultadosVendas = executarConsulta($sqlVendas);
$resultadosOrdens = executarConsulta($sqlOrdens);
$resultadosCombinados = array_merge($resultadosVendas, $resultadosOrdens);

// Defina o número de itens por página
$itemsPerPage = 10;

// Obtenha a página atual a partir dos parâmetros da URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$totalItems = count($resultadosCombinados);
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
            if (!empty($resultadosCombinados)) {
                echo '<table>';
                echo '<tr>
                        <th>Nome do comprador / Cliente</th>
                        <th>Número de Parcelas</th>
                        <th>Data de Venda / Abertura</th>
                        <th>Tipo</th>
                    </tr>';
        
                for ($i = $startIndex; $i < $endIndex; $i++) {
                    echo '<tr>';
                    echo '<td>' . $resultadosCombinados[$i]['cliente_nome'] . '</td>';
                    echo '<td>' . $resultadosCombinados[$i]['numero_parcelas'] . 'x</td>';
                    echo '<td>' . $resultadosCombinados[$i]['data_abertura'] . '</td>';
                    echo '<td>' . $resultadosCombinados[$i]['tipo'] . '</td>';
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
                        echo '<a href="contas_receber?page=1">&laquo;&laquo;</a>';
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

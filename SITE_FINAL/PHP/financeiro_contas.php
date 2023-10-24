<?php
require_once "config.php"; // Arquivo de configuração do banco de dados
// Consulta SQL para buscar todas as vendas na tabela "vendas"
$sql = "SELECT * FROM vendas";

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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <li><a href="../PHP/inicio.php">Inicio</a></li>
                <li><a href="Venda.html">Venda</a></li>
                <li><a href="Financeiro.html">Financeiro</a></li>
                <li><a href="../PHP/Debitos.php">Debitos</a></li>
                <li><a href="../PHP/Notificações.php">Notificações</a></li>
                <li><a href="../PHP/Estoque.php">Estoque</a></li>
                <li><a href="../PHP/Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <div id="contas-a-receber">
            <h1>Contas a Receber</h1>
            <table>
                <?php
                    // Defina o número de itens por página
                    $itemsPerPage = 10;

                    // Obtenha a página atual a partir dos parâmetros da URL
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Calcule o índice de início e fim para os itens da página atual
                    $startIndex = ($page - 1) * $itemsPerPage;
                    $endIndex = $startIndex + $itemsPerPage;

                    // Crie a tabela para exibir os itens da página atual (contas a receber)
                    echo '<table>';
                    echo '<tr>
                            <th>Comprador</th>
                            <th>Número de Parcelas</th>
                            <th>Data de Venda</th>
                        </tr>';

                    foreach ($historico as $conta) {
                        if ($conta["numero_parcelas"] > 0) {
                            echo '<tr>';
                            echo '<td>' . $conta['nome_comprador'] . '</td>';
                            echo '<td>' . $conta['numero_parcelas'] . 'x</td>';
                            echo '<td>' . $conta['data_venda'] . '</td>';
                            echo '</tr>';
                        }
                    }

                    echo '</table>';

                    // Adicione os links de paginação
                    echo '<div id="pagination">';
                    $totalItems = count($historico);
                    $totalPages = ceil($totalItems / $itemsPerPage);

                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a href="financeiro_contas.php?page=' . $i . '">' . $i . '</a> ';
                    }

                    echo '</div>';
                    ?>
            </table>
        </div>
    </body>
</html>
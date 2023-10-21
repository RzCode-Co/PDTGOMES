<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Estoque</title>
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
        #pagination {
            margin: 20px 0;
        }

        #pagination a {
            padding: 5px 10px;
            background-color: lightgray;
            text-decoration: none;
            margin-right: 5px;
        }
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
                <li><a href="Debitos.php">Debitos</a></li>
                <li><a href="Notificações.php">Notificações</a></li>
                <li><a href="Estoque.php">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <div id="botoes-estoque">
            <button onclick="mostrarAdicionarItem()">Adicionar Item</button>
            <button onclick="mostrarRemoverItem()">Remover Item</button>
            <button onclick="mostrarConsultarItem()">Consultar Item</button>
            <button onclick="mostrarConsultarTodosItens()">Consultar Todos os Itens</button>
        </div>
        <div id="conteudo">

            <div id="adicionar-item" style="display: none;">
                <h2>Adicionar Item ao Estoque</h2>
                <form action="processar_adicionar_item.php" method="post" onchange="mostrarAdicionarItem()">
                    <label>Nome do Item: <input type="text" name="nome"></label><br>
                    <label>Referência: <input type="text" name="referencia"></label><br>
                    <label>Marca: <input type="text" name="marca"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                    <label>Ano: <input type="number" name="ano"></label><br>
                    <label>Quantidade: <input type="number" name="quantidade"></label><br>
                    <label>Valor de Custo: <input type="float" name="valor_custo"></label><br>
                    <label>Valor de Varejo: <input type="float" name="valor_varejo"></label><br>
                    <label>Valor de Atacado: <input type="float" name="valor_atacado"></label><br>
                    <label>Local: <input type="text" name="local"></label><br>
                    <label>Imagem do Produto: <input type="file" name="imagem" accept="image/*"></label><br>
                    <input type="submit" value="Adicionar">
                </form>
            </div>    
                
            <div id="remover-item" style="display: none;">
                <h2>Remover Item do Estoque</h2>
                <form action="processar_remover_item.php" method="post" onchange="mostrarRemoverItem()">
                    <label>Nome do Item: <input type="text" name="nome"></label><br>
                    <label>Referência: <input type="text" name="referencia"></label><br>
                    <label>Marca: <input type="text" name="marca"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                    <label>Ano: <input type="number" name="ano"></label><br>
                    <label>Quantidade: <input type="number" name="quantidade"></label><br>
                    <input type="submit" value="Remover">
                </form>
            </div>

            <div id="consultar-item" style="display: none;">
                <h2>Pesquisa de Estoque</h2>
                <form action="consulta_item.php" method="post"  onchange="mostrarConsultarItem()">
                    <label>Nome do Item: <input type="text" name="nome"></label><br>
                    <label>Referência: <input type="text" name="referencia"></label><br>
                    <label>Marca: <input type="text" name="marca"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                    <label>Ano: <input type="number" name="ano"></label><br>
                    <input type="submit" value="Pesquisar">
                </form>
            </div>

            <div id="resultado_busca_geral">
                <h1>Consulta Geral de Itens no Estoque</h1>
                <table>
                    <?php
                        require_once "config.php"; // Inclua seu arquivo de configuração do banco de dados aqui

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST["nome"])) {
                                $nome_produto = strtoupper($_POST["nome"]);
                                $referencia = strtoupper($_POST["referencia"]);
                                $marca = strtoupper($_POST["marca"]);
                                $aplicacao = strtoupper($_POST["aplicacao"]);
                                $ano = strtoupper($_POST["ano"]);
                        
                                $sql = "SELECT * FROM estoque WHERE nome = '$nome_produto' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";
                        
                                $result = $conn->query($sql);
                        
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $consulta[] = $row;
                                    }
                                } else {
                                    echo "Produto não encontrado.";
                                }
                            }
                        }
                        
                        // Consulta SQL para selecionar todos os produtos da tabela "estoque"
                        $sql = "SELECT * FROM estoque";
                        
                        $result = $conn->query($sql);
                        
                        // Inicialize um array para armazenar os resultados da consulta
                        $consulta = [];
                        
                        if ($result->num_rows > 0) {
                            // Armazene os resultados da consulta no array $consulta
                            while ($row = $result->fetch_assoc()) {
                                $consulta[] = $row;
                            }
                        }
                    ?>

            <div id="resultado_busca_geral">
                <table>
                    <tr>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Preço de Varejo</th>
                        <th>Preço de Atacado</th>
                        <th>Ano</th>
                        <th>Marca</th>
                        <th>Referência</th>
                        <th>Aplicação</th>
                    </tr>
                    <?php
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $itemsPerPage = 10; // Número de itens por página
                    $startIndex = ($page - 1) * $itemsPerPage;
                    $endIndex = $startIndex + $itemsPerPage;

                    // Exibir os itens da página atual
                    for ($i = $startIndex; $i < $endIndex && $i < count($consulta); $i++) {
                        $item = $consulta[$i];
                        echo "<tr>";
                        echo "<td>" . $item["nome"] . "</td>";
                        echo "<td>" . $item["quantidade"] . "</td>";
                        echo "<td>" . $item["valor_varejo"] . "</td>";
                        echo "<td>" . $item["valor_atacado"] . "</td>";
                        echo "<td>" . $item["ano"] . "</td>";
                        echo "<td>" . $item["marca"] . "</td>";
                        echo "<td>" . $item["referencia"] . "</td>";
                        echo "<td>" . $item["aplicacao"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </table>

                    <!-- Adicione um link para a paginação -->
                    <div id="pagination">
                        <?php
                        $totalItems = count($consulta); // Total de itens na consulta
                        $totalPages = ceil($totalItems / $itemsPerPage);

                        // Limita o número de páginas a serem exibidas na paginação
                        $maxVisiblePages = 5;
                        
                        $startPage = max(1, $page - floor($maxVisiblePages / 2));
                        $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);
                        
                        for ($i = $startPage; $i <= $endPage; $i++) {
                            echo '<a href="consulta_geral_estoque.php?page=' . $i . '">' . $i . '</a> ';
                        }
                        ?>
                    </div>
            </div>
        </div>
    </body>
    <script>
        function mostrarAdicionarItem() {
        document.getElementById("adicionar-item").style.display = "block";
        document.getElementById("remover-item").style.display = "none";
        document.getElementById("consultar-item").style.display = "none";
        document.getElementById("resultado_busca_geral").style.display = "none";
        }
        function mostrarRemoverItem() {
            document.getElementById("adicionar-item").style.display = "none";
            document.getElementById("remover-item").style.display = "block";
            document.getElementById("consultar-item").style.display = "none";
            document.getElementById("resultado_busca_geral").style.display = "none";
        }
        function mostrarConsultarItem() {
            document.getElementById("adicionar-item").style.display = "none";
            document.getElementById("remover-item").style.display = "none";
            document.getElementById("consultar-item").style.display = "block";
            document.getElementById("resultado_busca_geral").style.display = "none";
        }
        function mostrarConsultarTodosItens() {
            document.getElementById("adicionar-item").style.display = "none";
            document.getElementById("remover-item").style.display = "none";
            document.getElementById("consultar-item").style.display = "none";
            document.getElementById("resultado_busca_geral").style.display = "block";
        }
    </script>
</html>

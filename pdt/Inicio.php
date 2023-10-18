<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Tela Inicial</title>
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
        <div id="caixa-pesquisa">
            <form action="Inicio.php" method="post">
                <label>Nome do Item: <input type="text" name="nome"></label><br>
                <label>Referência: <input type="text" name="referencia"></label><br>
                <label>Marca: <input type="text" name="marca"></label><br>
                <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                <label>Ano: <input type="number" name="ano"></label><br>
                <button id="btn-pesquisar">Pesquisar</button>
            </form>
        </div>
        <?php
            require_once "config.php";

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Coletar os critérios da pesquisa da página de início.html
                $nome = strtoupper(isset($_POST["nome"]) ? $_POST["nome"] : "");
                $referencia = strtoupper(isset($_POST["referencia"]) ? $_POST["referencia"] : "");
                $marca = strtoupper(isset($_POST["marca"]) ? $_POST["marca"] : "");
                $aplicacao = strtoupper(isset($_POST["aplicacao"]) ? $_POST["aplicacao"] : "");
                $ano = strtoupper(isset($_POST["ano"]) ? $_POST["ano"] : "");
        
                // Inicialize um array de parâmetros para a declaração preparada
                $params = array();
        
                // Construir a consulta SQL com base nos critérios preenchidos
                $sql = "SELECT * FROM estoque WHERE 1=1"; // Começa com uma consulta verdadeira
        
                if (!empty($nome)) {
                    $sql .= " AND nome LIKE ?";
                    $params[] = "%$nome%";
                }
        
                if (!empty($referencia)) {
                    $sql .= " AND referencia LIKE ?";
                    $params[] = "%$referencia%";
                }
        
                if (!empty($marca)) {
                    $sql .= " AND marca LIKE ?";
                    $params[] = "%$marca%";
                }
        
                if (!empty($aplicacao)) {
                    $sql .= " AND aplicacao LIKE ?";
                    $params[] = "%$aplicacao%";
                }
        
                if (!empty($ano)) {
                    $sql .= " AND ano = ?";
                    $params[] = $ano;
                }
        
                // Prepare a declaração SQL
                $stmt = $conn->prepare($sql);
        
                // Verifique se a preparação da declaração foi bem-sucedida
                if ($stmt) {
                    // Vincule os parâmetros
                    if (!empty($params)) {
                        $types = str_repeat('s', count($params)); // Assume que todos os parâmetros são strings
                        $stmt->bind_param($types, ...$params);
                    }
        
                    // Execute a consulta SQL
                    $stmt->execute();
        
                    // Obtenha os resultados
                    $result = $stmt->get_result();
        
                    // Adicione a funcionalidade de paginação
                    $itensPorPagina = 10;
                    $totalItens = $result->num_rows;
                    $numPaginas = ceil($totalItens / $itensPorPagina);
        
                    $paginaAtual = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($paginaAtual - 1) * $itensPorPagina;
                    $result->data_seek($offset);
        
                    if ($result->num_rows > 0) {
                        echo "<h2>Resultados da Pesquisa:</h2>";
                        echo "<table>";
                        echo "<tr><th>Nome</th><th>Referência</th><th>Marca</th><th>Aplicação</th><th>Ano</th></tr>";
        
                        for ($i = 0; $i < $itensPorPagina && $row = $result->fetch_assoc(); $i++) {
                            echo "<tr>";
                            echo "<td>" . $row["nome"] . "</td>";
                            echo "<td>" . $row["referencia"] . "</td>";
                            echo "<td>" . $row["marca"] . "</td>";
                            echo "<td>" . $row["aplicacao"] . "</td>";
                            echo "<td>" . $row["ano"] . "</td>";
                            echo "</tr>";
                        }
        
                        echo "</table>";
        
                        // Exiba a paginação
                        echo "<div class='pagination'>";
                        for ($i = 1; $i <= $numPaginas; $i++) {
                            echo "<a href='Inicio.php?page=$i'>$i</a>";
                        }
                        echo "</div>";
                    } else {
                        echo "Nenhum resultado encontrado.";
                    }
        
                    // Verifique se deseja listar itens similares
                    if (!empty($_POST['nome']) && !empty($_POST['referencia']) && !empty($_POST['marca']) && !empty($_POST['aplicacao']) && !empty($_POST['ano'])) {
                        // Se todos os campos estão preenchidos, pesquise produtos com nomes semelhantes
                        $sqlSimilares = "SELECT * FROM estoque WHERE nome LIKE ?";
                        $param = "%$nome";
                        $stmtSimilares = $conn->prepare($sqlSimilares);
        
                        if ($stmtSimilares) {
                            $stmtSimilares->bind_param("s", $param);
                            $stmtSimilares->execute();
                            $resultSimilares = $stmtSimilares->get_result();
        
                            if ($resultSimilares->num_rows > 0) {
                                echo "<h2>Também temos essas opções:</h2>";
                                echo "<table>";
                                echo "<tr><th>Nome</th><th>Referência</th><th>Marca</th><th>Aplicação</th><th>Ano</th></tr>";
        
                                while ($rowSimilar = $resultSimilares->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $rowSimilar["nome"] . "</td>";
                                    echo "<td>" . $rowSimilar["referencia"] . "</td>";
                                    echo "<td>" . $rowSimilar["marca"] . "</td>";
                                    echo "<td>" . $rowSimilar["aplicacao"] . "</td>";
                                    echo "<td>" . $rowSimilar["ano"] . "</td>";
                                    echo "</tr>";
                                }
        
                                echo "</table>";

                                // Exiba a paginação
                                echo "<div class='pagination'>";
                                for ($i = 1; $i <= $numPaginas; $i++) {
                                    echo "<a href='Inicio.php?page=$i'>$i</a>";
                                }
                                echo "</div>";
                            } else {
                                echo "Nenhum resultado encontrado.";
                            }

                        }
                    }
                } else {
                    echo "Erro na preparação da declaração SQL: " . $conn->error;
                }
            }
        ?>
    </body>
</html>
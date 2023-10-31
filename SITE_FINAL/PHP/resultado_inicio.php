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
        .paginacao {
        margin-top: 20px;
        text-align: center;
        }
        
        .paginacao a {
            padding: 5px 10px;
            background-color: black;
            color: white;
            text-decoration: none;
            margin: 5px;
        }
        
        .paginacao a:hover {
            background-color: gray;
        }
    </style>
    <body>
        <div id="cabecalho">
            <div id="usuario-info">

            <?php
                require_once "config.php";

                if (isset($_POST['clienteid'])) {
                    $idUsuario = $_POST['clienteid'];

                    // Agora você tem o ID do usuário e pode usá-lo para buscar informações adicionais no banco de dados
                    $sql = "SELECT nome, cargo, arquivo FROM usuarios WHERE id = ?";
                    
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $idUsuario);
                    $stmt->execute();
                    $stmt->bind_result($nome, $cargo, $arquivo);
                    $stmt->fetch();
                    $stmt->close();
                    
                    // Exiba as informações do usuário
                    echo '<div id="cabecalho">';
                    echo '<div id="usuario-info">';
                    echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';
                    echo '<p>' . $nome . '</p>';
                    echo '<br>';
                    echo '<p>' . $cargo . '</p>';
                    echo '</div>';
                    echo '<div id="icone-notificacoes">';
                    echo '<img src="caminho-para-o-icone.png" alt="Ícone de Notificações">';
                    echo '</div>';
                    echo '</div>';

                    // Agora, você pode continuar com o restante do seu conteúdo da página
                } else {
                    if (isset($_GET['id'])) {
                        $idUsuario = $_GET['id'];
        
                        // Agora você tem o ID do usuário e pode usá-lo para buscar informações adicionais no banco de dados
                        $sql = "SELECT nome, cargo, arquivo FROM usuarios WHERE id = ?";
        
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $idUsuario);
                        $stmt->execute();
                        $stmt->bind_result($nome, $cargo, $arquivo);
                        $stmt->fetch();
                        $stmt->close();
                    }
                }
            ?>
            </div>
            <!-- Ícone de notificações -->
            <div id="icone-notificacoes">
                <img src="caminho-para-o-icone.png" alt="Ícone de Notificações">
            </div>
        </div>
        <!-- Seu menu lateral -->
        <div id="menu-lateral">
            <ul>
                <li><a href="inicio.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Inicio</a></li>
                <li><a href="Venda.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Venda</a></li>
                <li><a href="Financeiro.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Financeiro</a></li>
                <li><a href="Debitos.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Debitos</a></li>
                <li><a href="Notificações.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Notificações</a></li>
                <li><a href="Estoque.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Estoque</a></li>
                <li><a href="Criação OS.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <?php
                require_once "config.php";

                // Inicialize as variáveis para armazenar os valores dos campos
                $nome = $referencia = $marca = $aplicacao = $ano = "";

                if ($_SERVER["REQUEST_METHOD"] === "GET") {
                    $nome = isset($_GET['nome']) ? $_GET['nome'] : "";
                    $referencia = isset($_GET['referencia']) ? $_GET['referencia'] : "";
                    $marca = isset($_GET['marca']) ? $_GET['marca'] : "";
                    $aplicacao = isset($_GET['aplicacao']) ? $_GET['aplicacao'] : "";
                    $ano = isset($_GET['ano']) ? $_GET['ano'] : "";
                }   

                // Consulta SQL sem LIMIT
                $sql = "SELECT * FROM estoque WHERE 1=1";

                if (!empty($nome)) {
                    $nome = mysqli_real_escape_string($conn, $nome);
                    $sql .= " AND nome LIKE '%$nome%'";
                }

                if (!empty($referencia)) {
                    $referencia = mysqli_real_escape_string($conn, $referencia);
                    $sql .= " AND referencia LIKE '%$referencia%'";
                }

                if (!empty($marca)) {
                    $marca = mysqli_real_escape_string($conn, $marca);
                    $sql .= " AND marca LIKE '%$marca%'";
                }

                if (!empty($aplicacao)) {
                    $aplicacao = mysqli_real_escape_string($conn, $aplicacao);
                    $sql .= " AND aplicacao LIKE '%$aplicacao%'";
                }

                if (!empty($ano)) {
                    $sql .= " AND ano = $ano";
                }

                $result = $conn->query($sql);
                $totalResultados = $result->num_rows;

                // Adicione a funcionalidade de paginação
                $itensPorPagina = 10;
                $numPaginas = ceil($totalResultados / $itensPorPagina);
                $paginaAtual = isset($_GET['page']) ? $_GET['page'] : 1;

                // Calcula o OFFSET com base na página atual
                $offset = ($paginaAtual - 1) * $itensPorPagina;

                // Consulta SQL com LIMIT e OFFSET
                $sql .= " LIMIT $itensPorPagina OFFSET $offset";
                $result = $conn->query($sql);

                // Exiba os resultados
                if ($result->num_rows > 0) {
                    echo "<h2>Resultados da Pesquisa:</h2>";
                    echo "<table>";
                    echo "<tr><th>Nome</th><th>Referência</th><th>Marca</th><th>Aplicação</th><th>Ano</th><th>Quantidade</th><th>Valor de Varejo</th><th>Valor de Atacado</th>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo '<img src="' . $row["imagem"] . '">';
                        echo "<td>" . $row["nome"] . "</td>";
                        echo "<td>" . $row["referencia"] . "</td>";
                        echo "<td>" . $row["marca"] . "</td>";
                        echo "<td>" . $row["aplicacao"] . "</td>";
                        echo "<td>" . $row["ano"] . "</td>";
                        echo "<td>" . $row["quantidade"] . "</td>";
                        echo "<td>" . $row["valor_varejo"] . "</td>";
                        echo "<td>" . $row["valor_atacado"] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";

                    // Exiba a paginação
                    echo "<div class='paginacao'>";
                    if ($numPaginas > 1) {
                        if ($paginaAtual > 1) {
                            echo "<a href='?page=" . ($paginaAtual - 1) . "' class='pagina-anterior'>&laquo;</a>";
                        }

                        $quantidadeLinks = 5;
                        $inicio = max(1, $paginaAtual - floor($quantidadeLinks / 2));
                        $fim = min($numPaginas, $paginaAtual + floor($quantidadeLinks / 2));

                        for ($i = $inicio; $i <= $fim; $i++) {
                            if ($paginaAtual == $i) {
                                echo "<span class='pagina-atual'>$i</span>";
                            } else {
                                echo "<a href='?page=$i&nome=$nome&referencia=$referencia&marca=$marca&aplicacao=$aplicacao&ano=$ano' class='pagina'>$i</a>";
                            }
                        }

                        if ($paginaAtual < $numPaginas) {
                            echo "<a href='?page=" . ($paginaAtual + 1) . "' class='proxima-pagina'>&raquo;</a>";
                        }
                    } else {
                        echo "<span class='pagina-atual'>1</span>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>Este item não se encontra no estoque.</p>";
                }
                
            ?>
        </div>
        </body>
</html>

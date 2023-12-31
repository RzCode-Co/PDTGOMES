<?php
require_once "config.php"; // arquivo de config do bd

$nome_produto = strtoupper($_POST["nome"]);
$referencia = strtoupper($_POST["referencia"]);
$marca = strtoupper($_POST["marca"]);
$aplicacao = strtoupper($_POST["aplicacao"]);
$ano = strtoupper($_POST["ano"]);

$sql = "SELECT * FROM estoque WHERE nome = '$nome_produto' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";

$result = $conn->query($sql);

$consulta = array(); // Inicialize a variável $consulta como um array vazio

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consulta[] = $row;
    }
}

if (empty($consulta)) {
    echo "Produto não encontrado.";
}
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
        <div id="resultado_busca">
        <h1>Consulta de Item</h1>
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
            foreach ($consulta as $pesquisa) {
                echo "<tr>";
                echo "<td>" . $pesquisa["nome"] . "</td>";
                echo "<td>" . $pesquisa["quantidade"] . "</td>";
                echo "<td>" . $pesquisa["valor_varejo"] . "</td>";
                echo "<td>" . $pesquisa["valor_atacado"] . "</td>";
                echo "<td>" . $pesquisa["ano"] . "</td>";
                echo "<td>" . $pesquisa["marca"] . "</td>";
                echo "<td>" . $pesquisa["referencia"] . "</td>";
                echo "<td>" . $pesquisa["aplicacao"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        </div>
    </body>
</html>
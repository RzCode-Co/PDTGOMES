<?php
require_once "config.php"; // arquivo de config do bd

$nome_produto = $_POST["nome"];

$sql = "SELECT * FROM estoque WHERE nome = '$nome_produto'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Criação do conteúdo HTML
    $html = '<html>
    <head>
        <title>Resultado da Busca</title>
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
            <img src="" alt="Foto do Usuário">
            <p></p>
            <p></p>
        </div>
        <!-- Ícone de notificações -->
        <div id="icone-notificacoes">
            <img src="caminho-para-o-icone.png" alt="Ícone de Notificações">
        </div>
    </div>
    <!-- Seu menu lateral -->
    <div id="menu-lateral">
        <ul>
            <li><a href="inicio.html">Inicio</a></li>
            <li><a href="Venda.html">Venda</a></li>
            <li><a href="Financeiro.html">Financeiro</a></li>
            <li><a href="Debitos.html">Debitos</a></li>
            <li><a href="#">Notificações</a></li>
            <li><a href="estoque.html">Estoque</a></li>
        </ul>
    </div>';

    $html .= '<h1>Resultados da Busca</h1>';
    $html .= '<ul>';
    while ($row = $result->fetch_assoc()) {
        $html .= '<li>Nome do Produto: ' . $row["nome"] . '</li>';
        $html .= '<li>Quantidade em Estoque: ' . $row["quantidade"] . '</li>';
        $html .= '<li>Valor de Varejo: ' . $row["valor_varejo"] . '</li>';
        $html .= '<li>Valor de Atacado: ' . $row["valor_atacado"] . '</li>';
        $html .= '<li>Localização: ' . $row["localizacao"] . '</li>';
        // Adicione mais campos conforme necessário
    }
    $html .= '</ul>';
    $html .= '</body>
    </html>';

    // Fecha a conexão com o banco de dados
    $conn->close();

    // Imprime o HTML gerado
    echo $html;
} else {
    echo "Produto não encontrado.";
}
?>
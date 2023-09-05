<?php
// Conexão com o banco de dados (use suas configurações)
require_once "config.php";

$files = []; // Array para armazenar os resultados

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome_arquivo"]) && isset($_POST["data_debito"])) {
    // Obtenha o nome do arquivo e a data do formulário POST
    $nome_arquivo = $_POST["nome_arquivo"];
    $data_debito = $_POST["data_debito"];

    // Consulta SQL para obter os arquivos correspondentes ao nome e à data
    $sql = "SELECT id, arquivo, nome, data_debito FROM debitos WHERE nome = '$nome_arquivo' AND data_debito = '$data_debito'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $files[] = $row; // Adicione o resultado ao array $files
        }
    }

    // Feche a conexão com o banco de dados
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Download</title>
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
            <li><a href="inicio.html">Inicio</a></li>
            <li><a href="Venda.html">Venda</a></li>
            <li><a href="Financeiro.php">Financeiro</a></li>
            <li><a href="Debitos.html">Debitos</a></li>
            <li><a href="#">Notificações</a></li>
            <li><a href="estoque.html">Estoque</a></li>
            <li><a href="Criação OS.html">Criação/Consulta de OS</a></li>
        </ul>
    </div>

    <div id="conteudo">
        <h1>Lista de Arquivos para Download</h1>
        <div id="lista_arquivos">
            <!-- Lista não ordenada para as informações -->
            <ul>
                <?php foreach ($files as $file) { ?>
                    <li>
                        <strong>Nome do Arquivo:</strong> <?php echo $file["nome"]; ?><br>
                        <strong>Data de Débito:</strong> <?php echo $file["data_debito"]; ?><br>
                        <a href="<?php echo $file["arquivo"]; ?>" download>
                            <button>Download</button>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    </body>
</html>
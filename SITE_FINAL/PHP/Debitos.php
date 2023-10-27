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
}
$formularioUtilizado = count($files) > 0; // Verifica se o formulário foi utilizado
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Debitos</title>
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
                <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';?>
                <?php echo '<p>' . $cargo . '</p>';?>
                <?php echo '<p>' . $nome . '</p>';?>
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
                <li><a href="Debitos.html">Debitos</a></li>
                <li><a href="Notificações.php">Notificações</a></li>
                <li><a href="estoque.html">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <div id="botoes-debitos">
            <button onclick="mostrarRegistrarDebito()">Registrar Débito</button>
            <button onclick="mostrarCancelarDebito()">Cancelar Débito</button>
            <button onclick="mostrarBaixarDebitos()">Baixar Débitos</button>
            <button onclick="redirecionarParaBoletos()">Boletos dos Clientes</button>
        </div>
        <div id="registrar-debito" style="display: none;">
            <form enctype="multipart/form-data" action="processar_debito.php" method="post">

                <label>Data: <input type="date" name="data_debito"></label>

                <br>

                <label>Nome: <input type="text" name="nome"></label>

                <br>    

                <label>Valor: <input type="number" name="valor_debito"></label>

                <br>

                <label>Tipo: <input type="text" name="tipo"></label>

                <br>
                
                <label>Descrição: <input type="text" name="descricao"></label>

                <br>

                <label>Envie o arquivo aqui: <input type="file" name="arquivo"></label>

                <br>

                <input type="submit" value="Registrar Custo">
            </form>
        </div>
        <div id="cancelar-debito" style="display: none;">
            <form enctype="multipart/form-data" action="cancelar_debito.php" method="post">

                <label>Data: <input type="date" name="data_debito"></label>

                <br>

                <label>Nome: <input type="text" name="nome"></label>

                <br>    

                <label>Valor: <input type="number" name="valor_debito"></label>

                <br>

                <label>Tipo: <input type="text" name="tipo"></label>

                <br>

                <label>Descrição: <input type="text" name="descricao"></label>

                <br>

                <input type="submit" value="Cancelar Débito">
            </form>
        </div>
        <div id="baixar-debitos" style="display: none">
            <form action="debitos.php" method="post">
                <label for="nome_arquivo">Nome do Arquivo:</label>
                <input type="text" name="nome_arquivo" id="nome_arquivo" required>
                <label for="data_debito">Data:</label>
                <input type="date" name="data_debito" id="data_debito" required>
                <input type="submit" value="Pesquisar">
            </form>
        </div>
        <div id="lista-arquivos" style="display: <?php echo $formularioUtilizado ? 'block' : 'none'; ?>">
            <!-- Se o formulário não foi utilizado, exibe a lista de arquivos -->
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
    </body>
    <script>
        function mostrarBaixarDebitos(){
            document.getElementById("baixar-debitos").style.display = "block";
            document.getElementById("registrar-debito").style.display = "none";
            document.getElementById("cancelar-debito").style.display = "none";
            document.getElementById("lista-arquivos").style.display = "none";
        }
        function mostrarRegistrarDebito() {
            document.getElementById("baixar-debitos").style.display = "none";
            document.getElementById("registrar-debito").style.display = "block";
            document.getElementById("cancelar-debito").style.display = "none";
            document.getElementById("lista-arquivos").style.display = "none";
        }
        function mostrarCancelarDebito() {
            document.getElementById("baixar-debitos").style.display = "none";
            document.getElementById("registrar-debito").style.display = "none";
            document.getElementById("cancelar-debito").style.display = "block";
            document.getElementById("lista-arquivos").style.display = "none";
        }
        function redirecionarParaBoletos() {
            window.location.href = "../PHP/boleto_clientes.php";
        }
    </script>
</html>
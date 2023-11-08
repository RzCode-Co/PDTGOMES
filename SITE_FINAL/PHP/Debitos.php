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
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <link rel="stylesheet" href="../CSS/debitos.css">
</head>

<body>
    <nav class="menu_lateral">

        <!-- Barra MENU -->
        <div class="btn_expandir">
            <img src="../CSS/img/Três barras.svg" alt="menu" id="btn_exp">
        </div>

        <!--  itens MENU LATERAL-->
        <ul class="ul_menu_lateral">

            <li class="item_menu ativo">
                <a href="../PHP/Inicio.php">
                    <img class="icon" src="../CSS/img/Logo Circular verde.svg" alt="logo">
                    <span class="txt_link">Home</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../HTML/Venda.html">
                    <img class="icon" src="../CSS/img/VENDAS.svg" alt="icone compras">
                    <span class="txt_link">Vendas</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/estoque.php">
                    <img class="icon" src="../CSS/img/Compras.svg" alt="icone compras">
                    <span class="txt_link">Estoque</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../HTML/Financeiro.html">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Vendas</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Debitos.php">
                    <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                    <span class="txt_link">Débitos</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Criação OS.php">
                    <img class="icon" src="../CSS/img/OS.svg" alt="icone OS">
                    <span class="txt_link">O.S</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="#">
                    <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                    <span class="txt_link">Perfil</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Notificações.php">
                    <img class="icon" src="../CSS/img/Sino.svg" alt="logo">
                    <span class="txt_link">Notificações</span>
                </a>
            </li>

        </ul>
        <!-- importando o JS para o Menu Lateral-->
        <script src="../JS/menu.js"></script>

    </nav>

    <!-- Menu horizonatl -->
    <nav class="menu_horizontal">
        <ul>
            <li id="logo_menu_horizontal"><a href="../PHP/Inicio.php"><img src="../CSS/img/Logo Horizontal.png"
                        alt="logo da empresa"></a>
            </li>

            <li id="direita">

                <!-- Perfil -->
                <div class="image_container">
                    <img src="../CSS/img/editar.png" alt="insira foto de perfil" id="img_photo">
                </div>

                <!-- Escolhendo Imagem -->
                <input type="file" id="file_image" name="file_image" accept="image/*">

                <script src="../JS/login_preview.js"></script>
            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <h3>Cargo</h3>
                    <p>Nome e Sobrenome</p>
                </div>
            </li>

            <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg"
                        alt="Notificações"></a></li>

        </ul>

    </nav>

    <section class="container_debitos">

        <div id="botoes-debitos">
            <button onclick="mostrarRegistrarDebito()">Registrar Débito</button>
            <button onclick="mostrarCancelarDebito()">Cancelar Débito</button>
            <button onclick="mostrarBaixarDebitos()">Baixar Débitos</button>
            <button onclick="redirecionarParaBoletos()">Boletos dos Clientes</button>
        </div>

        <div class="conteudo_debitos">
            <div id="registrar-debito" style="display: none;">
                <form enctype="multipart/form-data" action="processar_debito.php" method="post" class="form_debito">


                    <section class="section_one">
                        <label>Nome: <input type="text" name="nome"></label>
                    </section>

                    <section class="section_two">
                        <label>Data: <input type="date" name="data_debito"></label>
                        <label>Valor: <input type="number" name="valor_debito"></label>
                        <label>Tipo: <input type="text" name="tipo"></label>
                        <label>Descrição: <input type="text" name="descricao"></label>
                    </section>

                    <section class="section_three">
                        <label>Envie o arquivo aqui: <input type="file" name="arquivo"></label>
                        <input type="submit" value="Registrar Custo">
                    </section>


                </form>
            </div>
            <div id="cancelar-debito" style="display: none;">
                <form enctype="multipart/form-data" action="cancelar_debito.php" method="post" class="form_debito">

                    <section class="section_one"><label>Nome: <input type="text" name="nome"></label></section>

                    <section class="section_two">
                        <label>Data: <input type="date" name="data_debito"></label>
                        <label>Valor: <input type="number" name="valor_debito"></label>
                        <label>Tipo: <input type="text" name="tipo"></label>
                        <label>Descrição: <input type="text" name="descricao"></label>
                    </section>

                    <div class="section_three"><input type="submit" value="Cancelar Débito"></div>
                </form>
            </div>

            <div id="baixar-debitos" style="display: none">
                <form action="debitos.php" method="post" class="form_debito">

                    <section class="section_one"><label for="nome_arquivo">Nome:<input type="text" name="nome_arquivo"
                                id="nome_arquivo" required></label>
                    </section>

                    <section class="section_two">
                        <label for="data_debito">Data:<input type="date" name="data_debito" id="data_debito" required></label>
                    </section>

                    <div class="section_three"><input type="submit" value="Pesquisar"></div>
                </form>
            </div>
            <div id="lista-arquivos" style="display: <?php echo $formularioUtilizado ? 'block' : 'none'; ?>">
                <!-- Se o formulário não foi utilizado, exibe a lista de arquivos -->
                <ul>
                    <?php foreach ($files as $file) { ?>
                        <li>
                            <strong>Nome do Arquivo:</strong>
                            <?php echo $file["nome"]; ?><br>
                            <strong>Data de Débito:</strong>
                            <?php echo $file["data_debito"]; ?><br>
                            <a href="<?php echo $file["arquivo"]; ?>" download>
                                <button>Download</button>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </section>
</body>
<script>
    function mostrarBaixarDebitos() {
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
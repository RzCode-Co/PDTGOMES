<?php
require_once "config.php"; // arquivo de config do bd

$nome_produto = $_POST["nome"];
$referencia = $_POST["referencia"];
$marca = $_POST["marca"];
$aplicacao = $_POST["aplicacao"];
$ano = $_POST["ano"];

$sql = "SELECT * FROM estoque WHERE nome = '$nome_produto' AND referencia = '$referencia' AND marca = '$marca' AND aplicacao = '$aplicacao' AND ano = '$ano'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consulta[] = $row;
    }
} else {
    echo "Produto não encontrado.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Financeiro</title>

    <link rel="stylesheet" href="../CSS/estoque.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
</head>

<body>


    <!-- Menu horizonatl -->
    <nav class="menu_horizontal">
        <ul>
            <li id="logo_menu_horizontal"><a href="../HTML/pagina_incial.html"><img src="../CSS/img/Logo Horizontal.png"
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

            <li id="direita"><a href="#"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>

        </ul>

    </nav>

    <div id="resultado_busca">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/estoque.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
            <h1>Consulta Item</h1>
        </div>
        <div class="centralização">
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
                        echo '<img src="' . $pesquisa["imagem"] . '">';
                        echo "<td>" . $pesquisa["nome"] . "</td>";
                        echo "<td>" . $pesquisa["quantidade"] . "</td>";
                        echo "<td>" . $pesquisa["valor_varejo"] . "</td>";
                        echo "<td>" . $pesquisa["valor_atacado"] . "</td>";
                        echo "<td>" . $pesquisa["ano"] . "</td>";
                        echo "<td>" . $pesquisa["marca"] . "</td>";
                        echo "<td>" . $pesquisa["referencia"] . "</td>";
                        echo "<td>" . $pesquisa["aplicacao"] . "</td>";
                        echo '<td><a href="javascript:void(0);" onclick="abrirPopupEdicao(' . $pesquisa["id"] . ', \'' . $pesquisa["nome"] . '\', ' . $pesquisa["quantidade"] . ', ' . $pesquisa["valor_varejo"] . ', ' . $pesquisa["valor_atacado"] . ')">Editar</a></td>';
                    }
                ?>


            </table>
        </div>
    </div>
    <div class="popup" id="editarPopup" style="display: none;">
    <div class="popup-content">
        <h2>Editar Produto</h2>
        <form action="processar_edicao.php" method="POST">
            <input type="hidden" id="id" name="id">
            
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome"><br><br>

            <label for="quantidade">Quantidade:</label>
            <input type="text" id="quantidade" name="quantidade"><br><br>

            <label for="valorVarejo">Preço de Varejo:</label>
            <input type="text" id="valorVarejo" name="valorVarejo"><br><br>

            <label for="valorAtacado">Preço de Atacado:</label>
            <input type="text" id="valorAtacado" name="valorAtacado"><br><br>

            <!-- Adicione outros campos conforme necessário -->

            <input type="submit" value="Salvar">
            <button onclick="fecharPopup()">Fechar</button>
        </form>
    </div>
</div>
<script>
    // Função para abrir o pop-up de edição com os dados existentes
    function abrirPopupEdicao(id, nome, quantidade, valorVarejo, valorAtacado) {
        document.getElementById("id").value = id; // Defina o valor do campo ID
        document.getElementById("nome").value = nome;
        document.getElementById("quantidade").value = quantidade;
        document.getElementById("valorVarejo").value = valorVarejo;
        document.getElementById("valorAtacado").value = valorAtacado;
        document.getElementById("editarPopup").style.display = "block";
    }

    // Função para fechar o pop-up
    function fecharPopup() {
        document.getElementById("editarPopup").style.display = "none";
    }
</script>
</body>

</html>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Estoque</title>

    <link rel="stylesheet" href="../CSS/estoque.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
</head>
<html>

<body>
    <main>
        <nav class="menu_lateral">

            <!-- Barra MENU -->
            <div class="btn_expandir">
                <img src="../CSS/img/Três barras.svg" alt="menu" id="btn_exp">
            </div>

            <!--  itens MENU LATERAL-->
            <ul class="ul_menu_lateral">

                <li class="item_menu">
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
                    <a href="../PHP/Financeiro.php">
                        <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                        <span class="txt_link">Financeiro</span>
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
                    <a href="../HTML/pagina_cadastro.html">
                        <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                        <span class="txt_link">Cadastro</span>
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

        <section class="estoque">
            <div id="botoes-estoque">
                <button onclick="mostrarAdicionarItem()">Adicionar Item</button>
                <button onclick="mostrarRemoverItem()">Remover Item</button>
                <button onclick="mostrarConsultarItem()">Consultar Item</button>
                <button onclick="window.location.href='consulta_geral_estoque.php'">Consultar Todos os Itens</button>
            </div>
            <div class="conteudo">

                <div id="adicionar-item" style="display: none;">
                    <h2>Adicionar Item ao Estoque</h2>
                    <form enctype="multipart/form-data" class="form_estoque" action="../PHP/processar_adicionar_item.php" method="post" onchange="mostrarAdicionarItem()">
                        <div class="section_one">
                            <label>Nome do Item: <input type="text" name="nome"></label><br>
                            <label>Referência: <input type="text" name="referencia"></label><br>
                        </div>

                        <div class="section_two">
                            <label>Marca: <input type="text" name="marca"></label><br>
                            <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                            <label>Ano: <input type="number" name="ano"></label><br>
                            <label>Quantidade: <input type="number" name="quantidade"></label><br>
                        </div>

                        <div class="section_three">
                            <label>Valor de Custo: <input type="number" name="valor_custo"></label><br>
                            <label>Valor de Varejo: <input type="number" name="valor_varejo"></label><br>
                            <label>Valor de Atacado: <input type="number" name="valor_atacado"></label><br>
                            <label>Local: <input type="text" name="local"></label><br>
                        </div>

                        <div class="section_four">
                            <label for="arquivo">ENVIAR FOTO<input type="file" name="arquivo" id="arquivo"></label>
                            <input type="submit" value="Adicionar">
                        </div>

                    </form>
                </div>

                <div id="remover-item" style="display: none;">
                    <h2>Remover Item do Estoque</h2>
                    <form class="form_estoque" action="../PHP/processar_remover_item.php" method="post" onsubmit="return validarFormulario();" onchange="mostrarRemoverItem();">
                        <div class="section_one">
                            <label>Nome do Item: <input type="text" name="nome"></label><br>
                            <label>Referência: <input type="text" name="referencia"></label><br>
                        </div>

                        <div class="section_two">
                            <label>Marca: <input type="text" name="marca"></label><br>
                            <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                            <label>Ano: <input type="number" name="ano"></label><br>
                            <label>Quantidade: <input type="number" name="quantidade"></label><br>
                        </div>

                        <div class="remover">
                            <input type="submit" value="Remover" id="botão_remover">
                        </div>
                    </form>
                </div>

                <div id="consultar-item" style="display: none;">
                    <h2>Pesquisa de Estoque</h2>
                    <form class="form_estoque" action="../PHP/consulta_item.php" method="post" onsubmit="return validarCampos()">

                        <div class="section_one">
                            <label>Nome do Item: <input type="text" name="nome" id="nome"></label><br>
                            <label>Referência: <input type="text" name="referencia" id="referencia"></label><br>
                        </div>

                        <div class="section_two">
                            <label>Marca: <input type="text" name="marca" id="marca"></label><br>
                            <label>Aplicação: <input type="text" name="aplicacao" id="aplicacao"></label><br>
                            <label>Ano: <input type="number" name="ano" id="ano"></label><br>
                        </div>
                        <div class="pesquisa">
                            <input type="submit" value="Pesquisar">
                        </div>
                    </form>
                </div>

                <script src="../JS/estoque.js"></script>
            </div 
        
        </section>
    </main>
    <script>
        function validarFormulario(event) {
            var nome = document.querySelector('input[name="nome"]').value;
            var referencia = document.querySelector('input[name="referencia"]').value;
            var marca = document.querySelector('input[name="marca"]').value;
            var aplicacao = document.querySelector('input[name="aplicacao"]').value;
            var ano = document.querySelector('input[name="ano"]').value;
            var quantidade = document.querySelector('input[name="quantidade"]').value;

            if (nome === "" || referencia === "" || marca === "" || aplicacao === "" || ano === "" || quantidade === "") {
                alert("Por favor, preencha todos os campos obrigatórios.");
                event.preventDefault(); // Impede o envio do formulário se campos obrigatórios estiverem vazios
            }
        }
        function validarCampos() {
            var nome = document.getElementById("nome").value;
            var referencia = document.getElementById("referencia").value;
            var marca = document.getElementById("marca").value;
            var aplicacao = document.getElementById("aplicacao").value;
            var ano = document.getElementById("ano").value;

            if (!nome && !referencia && !marca && !aplicacao && !ano) {
                alert("Pelo menos um campo deve ser preenchido para realizar a pesquisa.");
                return false;
            }

            return true;
        }

        // Adicione um evento de envio ao formulário
        var formulario = document.querySelector('.form_estoque');
        formulario.addEventListener('submit', validarFormulario);
    </script>



</body>

</html>
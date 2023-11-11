<?php
// Inicie a sessão
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se o usuário não estiver logado, redirecione para a página de login
    header("Location: ../HTML/index.html");
    exit();
}

// Você agora pode acessar as informações do usuário a partir de $_SESSION
$idUsuario = $_SESSION['id'];
$nomeUsuario = $_SESSION['nome'];
$cargoUsuario = $_SESSION['cargo'];
$arquivo = $_SESSION['arquivo'];

require_once "config.php"; // Arquivo de configuração do banco de dados

                // Verifique a opção selecionada no formulário
                if (isset($_POST['saldos'])) {
                    $opcaoSelecionada = $_POST['saldos'];

                    // Verifique se alguma opção que requer filtragem de data foi selecionada
                    if ($opcaoSelecionada === 'Dias') {
                        if (isset($_POST['intervalo-saldos'])) {
                            $dataInicial = $_POST['intervalo-saldos'];
                            $dataFinal = $dataInicial; // Para filtrar apenas um dia
                        }
                    }elseif ($opcaoSelecionada === 'Semana') {
                        if (isset($_POST['intervalo-saldos'])) {
                            $dataSelecionada = $_POST['intervalo-saldos']; // Data selecionada pelo usuário
                            $dataInicial = date("d-m-Y", strtotime($dataSelecionada));
                            $dataFinal = date("d-m-Y", strtotime($dataInicial . " + 7 days")); // Adiciona 7 dias à data inicial
                        }
                    }elseif ($opcaoSelecionada === 'Mes') {
                        if (isset($_POST['mes']) && isset($_POST['ano'])) {
                            $mesSelecionado = $_POST['mes'];
                            $anoSelecionado = $_POST['ano'];
                            $dataInicial = "$anoSelecionado-$mesSelecionado-01";
                            $dataFinal = date("t-m-Y", strtotime($dataInicial)); // Último dia do mês
                        }
                    }elseif ($opcaoSelecionada === 'Ano') {
                        if (isset($_POST['ano'])) {
                            $anoSelecionado = $_POST['ano'];
                            $dataInicial = "$anoSelecionado-01-01";
                            $dataFinal = "$anoSelecionado-12-31";
                        }
                    }
                }

                // Consulta SQL para buscar as vendas no intervalo de datas
                $sql = "SELECT id, data_venda, valor_venda FROM vendas WHERE data_venda BETWEEN ? AND ? AND funcionario_vendedor = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $dataInicial, $dataFinal, $CPF);
                $stmt->execute();
                $result = $stmt->get_result();

                // Inicializar a lista de vendas
                $vendas = array();

                // Calcular o valor total das vendas no intervalo de datas
                $totalVendas = 0;

                // Loop através das vendas
                while ($row = $result->fetch_assoc()) {
                    $venda = array(
                        'id' => $row['id'],
                        'data_venda' => $row['data_venda'],
                        'valor_venda' => $row['valor_venda'],
                    );
                    $vendas[] = $venda;

                    // Adicione o valor da venda ao total
                    $totalVendas += $row['valor_venda'];
                }

                // Calcular 1% do valor total
                $umPorcento = $totalVendas * 0.01;

                // Exibir a lista de vendas
                echo " <link rel='stylesheet' href='../CSS/financeiro-vendedor.css'>";
                echo " <link rel='stylesheet' href='../CSS/pagina_inicial.css'>";
                echo '<div class="titulo-vendas">';
                echo '<a id="voltar-icone" href="../PHP/Financeiro.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>';
                 echo "<h1>Lista de Vendas</h1>";
                echo '</div>';
                
                echo "<div class='total-vendas'>";
                
                echo "<div>";
                echo '<img src="../CSS/img/VENDAS.svg" alt="voltar página"></a>';
                echo "</div>";
                echo "<div>";
                echo "Valor total de vendas no período: <span>R$ " . number_format($totalVendas, 2) . "</span><br>";
                echo "Comissão total: <span>R$" . number_format($totalVendas * 0.01, 2) . "</span>";
                echo "</div>";
                
                echo "</div>";
                echo "<ul>";
                foreach ($vendas as $venda) {
                    echo "<li>";
                    echo "<span>ID:</span> " . $venda['id'] . "<br>";
                    echo "<span>Data da Venda:</span> " . $venda['data_venda'] . "<br>";
                    echo "<span>Valor da Venda:</span> R$ " . $venda['valor_venda'] . "<br>";
                    echo "<span>Comissão:</span> <span class='comissao'>R$" . number_format($venda['valor_venda'] * 0.01, 2) . "</span>";
                    echo "</li>";
                }
                echo "</ul>";
$conn->close();
?>
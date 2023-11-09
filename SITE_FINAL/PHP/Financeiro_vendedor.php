<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
        // Consulta SQL para recuperar o cargo e CPF do usuário com o ID especificado
        $sql = "SELECT nome, cargo, CPF, arquivo FROM usuarios WHERE id = $id";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); // Dados do usuário
            $cargo = $user['cargo']; // Defina a variável $cargo
    
            if ($cargo == 'vendedor') {
                $CPF = $user['CPF'];
                    // Inicialize as variáveis de filtragem como nulas
                $dataInicial = null;
                $dataFinal = null;

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
                            $dataInicial = date("Y-m-d", strtotime($dataSelecionada));
                            $dataFinal = date("Y-m-d", strtotime($dataInicial . " + 7 days")); // Adiciona 7 dias à data inicial
                        }
                    }elseif ($opcaoSelecionada === 'Mes') {
                        if (isset($_POST['mes']) && isset($_POST['ano'])) {
                            $mesSelecionado = $_POST['mes'];
                            $anoSelecionado = $_POST['ano'];
                            $dataInicial = "$anoSelecionado-$mesSelecionado-01";
                            $dataFinal = date("Y-m-t", strtotime($dataInicial)); // Último dia do mês
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
                $sql = "SELECT valor_venda FROM vendas WHERE data_venda BETWEEN ? AND ? AND funcionario_vendedor = $CPF";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $dataInicial, $dataFinal);
                $stmt->execute();
                $result = $stmt->get_result();

                // Calcular o valor total das vendas no intervalo de datas
                $totalVendas = 0;
                while ($row = $result->fetch_assoc()) {
                    $totalVendas += $row['valor_venda'];
                }

                // Calcular 1% do valor total
                $umPorcento = $totalVendas * 0.01;

                // Resto do código para exibir os resultados (tabela e total dos valores) permanece o mesmo

                // Exibir o 1% do valor total
                echo "1% do valor total de vendas no período: $umPorcento";
            }
        }
} else {
    echo "ID do usuário não especificado no formulário.";
}

$conn->close();
?>

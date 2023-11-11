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

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ordem_servico_id = $_POST["ordem_servico_id"];
    $estornar_produtos = $_POST["estornar_produtos"];

    // Consulta SQL para verificar a existência da OS na tabela ordem_servico_completa
    $verifica_ordem_sql = "SELECT * FROM ordem_servico_completa WHERE ordem_servico_id = '$ordem_servico_id'";
    $result = $conn->query($verifica_ordem_sql);

    if ($result->num_rows > 0) {
        // Existe uma OS com o ID fornecido

        if ($estornar_produtos == "Sim") {
            // Consulta SQL para obter o código do produto associado a esta OS
            $consulta_produtos = "SELECT codigo_produto, quantidade FROM produtos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";
            $result_produtos = $conn->query($consulta_produtos);
        
            while ($row = $result_produtos->fetch_assoc()) {
                $codigo_produto = $row["codigo_produto"];
                $quantidade_estorno = $row["quantidade"];
        
                // Atualize o estoque, adicionando a quantidade de volta ao estoque do produto correspondente
                $atualiza_estoque_sql = "UPDATE estoque SET quantidade = quantidade + $quantidade_estorno WHERE id = '$codigo_produto'";
                if ($conn->query($atualiza_estoque_sql) !== TRUE) {
                    echo "Erro ao atualizar o estoque: " . $conn->error;
                    exit; // Saia se houver um erro
                }
            }

                    // Remova a OS das tabelas relacionadas (produtos_ordem_servico, servicos_ordem_servico)
                    $delete_produtos_sql = "DELETE FROM produtos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";
                    $delete_servicos_sql = "DELETE FROM servicos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";

                    if ($conn->query($delete_produtos_sql) === TRUE && $conn->query($delete_servicos_sql) === TRUE) {
                        // Remova a OS da tabela ordem_servico
                        $delete_os_sql = "DELETE FROM ordem_servico WHERE id = '$ordem_servico_id'";
                        if ($conn->query($delete_os_sql) === TRUE) {
                            // Remova a OS da tabela ordem_servico_completa
                            $delete_os_completa_sql = "DELETE FROM ordem_servico_completa WHERE ordem_servico_id = '$ordem_servico_id'";
                            if ($conn->query($delete_os_completa_sql) === TRUE) {
                                // Remova a linha na tabela valores relacionada ao ordem_servico_id
                                $delete_valores_sql = "DELETE FROM valores WHERE id_op = '$ordem_servico_id'";
                                if ($conn->query($delete_valores_sql) === TRUE) {
                                    echo '<script>
                                            alert("Ordem de Serviço cancelada com sucesso!");
                                            window.location.href = "Criação OS.php";
                                        </script>';
                                } else {
                                    echo "Erro ao excluir a linha na tabela valores: " . $conn->error;
                                }
                            } else {
                                echo "Erro ao excluir a OS da tabela ordem_servico_completa: " . $conn->error;
                            }
                        } else {
                            echo "Erro ao excluir a OS da tabela ordem_servico: " . $conn->error;
                        }
                    } else {
                        echo "Erro ao excluir produtos ou serviços relacionados à OS: " . $conn->error;
                    }

                    echo "Produtos estornados com sucesso e ordem de serviço excluída.";
        } else {
                    // Remova a OS das tabelas relacionadas (produtos_ordem_servico, servicos_ordem_servico)
                    $delete_produtos_sql = "DELETE FROM produtos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";
                    $delete_servicos_sql = "DELETE FROM servicos_ordem_servico WHERE ordem_servico_id = '$ordem_servico_id'";

                    if ($conn->query($delete_produtos_sql) === TRUE && $conn->query($delete_servicos_sql) === TRUE) {
                        // Remova a OS da tabela ordem_servico
                        $delete_os_sql = "DELETE FROM ordem_servico WHERE id = '$ordem_servico_id'";
                        if ($conn->query($delete_os_sql) === TRUE) {
                            // Remova a OS da tabela ordem_servico_completa
                            $delete_os_completa_sql = "DELETE FROM ordem_servico_completa WHERE ordem_servico_id = '$ordem_servico_id'";
                            if ($conn->query($delete_os_completa_sql) === TRUE) {
                                // Remova a linha na tabela valores relacionada ao ordem_servico_id
                                $delete_valores_sql = "DELETE FROM valores WHERE id_op = '$ordem_servico_id'";
                                if ($conn->query($delete_valores_sql) === TRUE) {
                                    echo '<script>
                                            alert("Ordem de Serviço cancelada com sucesso!");
                                            window.location.href = "pagina_de_redirecionamento.html";
                                        </script>';
                                } else {
                                    echo "Erro ao excluir a linha na tabela valores: " . $conn->error;
                                }
                            } else {
                                echo "Erro ao excluir a OS da tabela ordem_servico_completa: " . $conn->error;
                            }
                        } else {
                            echo "Erro ao excluir a OS da tabela ordem_servico: " . $conn->error;
                        }
                    } else {
                        echo "Erro ao excluir produtos ou serviços relacionados à OS: " . $conn->error;
                    }

                    echo "Produtos estornados com sucesso e ordem de serviço excluída.";
                }
    } else {
        // OS não encontrada na tabela ordem_servico_completa
        echo "Ordem de serviço não encontrada.";
    }
}

$conn->close();
?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdt";

// Cria uma conexão
$conn = mysqli_connect ($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


    $nome = $_POST['nome'];
    $marca = $_POST['marca'];
    $referencia = $_POST['referencia'];
    $aplicacao = $_POST['aplicacao'];
    $ano = $_POST['ano'];
    $quantidade_estoque = $_POST['quantidade_estoque'];
    $valor_atacado = $_POST['valor_atacado'];
    $valor_varejo = $_POST['valor_varejo'];

    $sql = "INSERT INTO pecas (nome, marca, referencia, aplicacao, ano, quantidade_em_estoque, valor_atacado, valor_varejo) VALUES ('$nome', '$marca', '$referencia', '$aplicacao', '$ano', '$quantidade_estoque', '$valor_atacado', '$valor_varejo')";

    if ($conn->query($sql) === TRUE) {
        echo "Item adicionado ao estoque com sucesso.";
    } else {
        echo "Erro ao adicionar item: " . $conn->error;
    }

    $mandei = mysqli_query($conn,$sql);


$conn->close();
?>

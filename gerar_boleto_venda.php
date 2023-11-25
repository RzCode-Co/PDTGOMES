<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__.'/NFe.php';
require_once 'config.php';
use WebmaniaBR\NFe;
// Supondo que $conn está definido em config.php
// Certifique-se de que a conexão com o banco de dados está estabelecida corretamente

if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $CPF = $_GET['CPF'];
  $CNPJ = $_GET['CNPJ'];

  if ($CPF == null || $CPF == 0) {
  // Substitua 'sua_tabela1' pelo nome real da tabela da primeira consulta
  $sql1 = "SELECT codigo_produto FROM ordem_servico_completa WHERE CNPJ = $CNPJ";
  $result1 = $conn->query($sql1);
  if ($result1->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result1->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
    foreach ($os_details as $os) {
        $codigo_produto = $os["codigo_produto"];
      }
  }

  // Substitua 'sua_tabela2' pelo nome real da tabela da segunda consulta
  $sql2 = "SELECT id, nome_comprador, nome_peca, quantidade, valor_venda, forma_pagamento FROM vendas WHERE CNPJ = $CNPJ";
  $result2 = $conn->query($sql2);
  if ($result2->num_rows > 0) {
    $vendas = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result2->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $vendas[] = $row;
    }
  }
  foreach ($vendas as $venda) {
    $id = $venda["id"];
    $nome_cliente = $venda["nome_comprador"];
    $nome_produto = $venda["nome_peca"];
    $quantidade = $venda["quantidade"];
    $valor_venda = $venda["valor_venda"];
    $forma_pagamento = $venda["forma_pagamento"];
  }
  if($forma_pagamento == "Parcelado"){
    $pagamento = 1;
    $forma_pagamento = 99;
    $desc_pagamento = "parcelado";
  }else if($forma_pagamento == "Boleto"){
    $pagamento = 1;
    $forma_pagamento = 15;
  }else if($forma_pagamento =="Crédito"){
    $pagamento = 0;
    $forma_pagamento = 99;
    $desc_pagamento = "credito";
  }else if($forma_pagamento =="Dinheiro"){
    $pagamento = 0;
    $forma_pagamento = 99;
    $desc_pagamento = "dinheiro";
  }else if($forma_pagamento =="Débito"){
    $pagamento = 0;
    $forma_pagamento = 99;
    $desc_pagamento = "debito";
  }else if($forma_pagamento =="Pix"){
    $pagamento = 0;
    $forma_pagamento = 17;
  }else{
    $pagamento = 0;
  }
  $sql4 = "SELECT CEP, telefone, endereco, numero_endereco, bairro, Cidade, uf, email FROM usuarios WHERE CNPJ = $CNPJ";
  $result2 = $conn->query($sql4);
  if ($result2->num_rows > 0) {
    $usuario = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result2->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $usuario[] = $row;
    }
  }
  foreach ($usuario as $usu) {
    $CEP = $usu["CEP"];
    $telefone = $usu["telefone"];
    $endereco = $usu["endereco"];
    $numero_endereco = $usu["numero_endereco"];
    $bairro = $usu["bairro"];
    $cidade = $usu["Cidade"];
    $uf = $usu["uf"];
    $email = $usu["email"];
  }
  $data = array(
    'ID' => $id, // Número do pedido (opcional)
    'operacao' => 1, // Tipo de Operação da Nota Fiscal
    'natureza_operacao' => 'Venda de produção do estabelecimento', // Natureza da Operação
    'modelo' => 2, // Modelo da Nota Fiscal
    'finalidade' => 1, // Finalidade de emissão da Nota Fiscal
    'ambiente' => 1, // Identificação do Ambiente do Sefaz
  );

  /**
   * Informações do Cliente
   */

   $data['cliente'] = array(
    'cnpj' => $CNPJ, // Número do CPF
    'nome_completo' => $nome_cliente, // Nome completo
    'endereco' => $endereco, // Endereço de entrega dos produtos
    'numero' => $numero_endereco, //Bairro do cliente
    'bairro' => $bairro, //Bairro do cliente
    'cidade' => $cidade, //Bairro do cliente
    'uf' => $uf, //Bairro do cliente
    'cep' => $CEP, // CEP do endereço de entrega
    'telefone' => $telefone, // Telefone do cliente
    'email' => $email, // E-mail do cliente para envio da NF-e
  );


  $valor_total = ($valor_venda * $quantidade);
  /**
   * Produtos
   */
  $data['produtos'] = array(
    array(
      'nome' => $nome_produto, // Nome do produto
      'ncm' => '87088000', // Código NCM
      'quantidade' => $quantidade, // Quantidade de itens
      'unidade' => 'UN', // Unidade de medida da quantidade de itens
      'origem' => 0, // Origem do produto
      'subtotal' => $valor_venda, // Preço unitário do produto - sem descontos
      'total' => $valor_total, // Preço total (quantidade x preço unitário) - sem descontos
      'classe_imposto' => 'REF150240576', // Classe de Imposto cadastrado no painel WebmaniaBR ou via API no endpoint /1/nfe/classe-imposto/
    )
  );

  /**
   * Informações do Pedido
   */
  $data['pedido'] = array(
    'presenca' => 1, // Indicador de presença do comprador no estabelecimento comercial no momento da operação
    'intermediador' => 0, // Indicador de intermediador/marketplace
    'modalidade_frete' => 9, // Modalidade do frete
    'frete' => '', // Total do frete
    'desconto' => '', // Total do desconto
    'total' => $valor_venda, // Valor total do pedido pago pelo cliente
    'pagamento' => $pagamento, // Indicador da forma de pagamento: 0 - Pagamento à vista ou 1 - Pagamento a prazo
    'forma_pagamento' => $forma_pagamento, // Meio de pagamento
    'desc_pagamento' => $desc_pagamento, // Valor total do pedido pago pelo cliente

  );
  }else{
     // Substitua 'sua_tabela1' pelo nome real da tabela da primeira consulta
  $sql1 = "SELECT codigo_produto FROM ordem_servico_completa WHERE CPF = $CPF";
  $result1 = $conn->query($sql1);
  if ($result1->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result1->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
    foreach ($os_details as $os) {
        $codigo_produto = $os["codigo_produto"];
      }
  }

  // Substitua 'sua_tabela2' pelo nome real da tabela da segunda consulta
  $sql2 = "SELECT id, nome_comprador, nome_peca, quantidade, valor_venda, forma_pagamento FROM vendas WHERE CPF = $CPF";
  $result2 = $conn->query($sql2);
  if ($result2->num_rows > 0) {
    $vendas = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result2->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $vendas[] = $row;
    }
  }
  foreach ($vendas as $venda) {
    $id = $venda["id"];
    $nome_cliente = $venda["nome_comprador"];
    $nome_produto = $venda["nome_peca"];
    $quantidade = $venda["quantidade"];
    $valor_venda = $venda["valor_venda"];
    $forma_pagamento = $venda["forma_pagamento"];
  }
  if($forma_pagamento == "Parcelado"){
    $pagamento = 1;
    $forma_pagamento = 99;
    $desc_pagamento = "parcelado";
  }else if($forma_pagamento == "Boleto"){
    $pagamento = 1;
    $forma_pagamento = 15;
  }else if($forma_pagamento =="Crédito"){
    $pagamento = 0;
    $forma_pagamento = 99;
    $desc_pagamento = "credito";
  }else if($forma_pagamento =="Dinheiro"){
    $pagamento = 0;
    $forma_pagamento = 99;
    $desc_pagamento = "dinheiro";
  }else if($forma_pagamento =="Débito"){
    $pagamento = 0;
    $forma_pagamento = 99;
    $desc_pagamento = "debito";
  }else if($forma_pagamento =="Pix"){
    $pagamento = 0;
    $forma_pagamento = 17;
  }else{
    $pagamento = 0;
  }
  $sql4 = "SELECT CEP, telefone, endereco, numero_endereco, bairro, Cidade, uf, email FROM usuarios WHERE CPF = $CPF";
  $result2 = $conn->query($sql4);
  if ($result2->num_rows > 0) {
    $usuario = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result2->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $usuario[] = $row;
    }
  }
  foreach ($usuario as $usu) {
    $CEP = $usu["CEP"];
    $telefone = $usu["telefone"];
    $endereco = $usu["endereco"];
    $numero_endereco = $usu["numero_endereco"];
    $bairro = $usu["bairro"];
    $cidade = $usu["Cidade"];
    $uf = $usu["uf"];
    $email = $usu["email"];
  }
  $data = array(
    'ID' => $id, // Número do pedido (opcional)
    'operacao' => 1, // Tipo de Operação da Nota Fiscal
    'natureza_operacao' => 'Venda de produção do estabelecimento', // Natureza da Operação
    'modelo' => 2, // Modelo da Nota Fiscal
    'finalidade' => 1, // Finalidade de emissão da Nota Fiscal
    'ambiente' => 1, // Identificação do Ambiente do Sefaz
  );

  /**
   * Informações do Cliente
   */

   $data['cliente'] = array(
    'cpf' => $CPF, // Número do CPF
    'nome_completo' => $nome_cliente, // Nome completo
    'endereco' => $endereco, // Endereço de entrega dos produtos
    'numero' => $numero_endereco, //Bairro do cliente
    'bairro' => $bairro, //Bairro do cliente
    'cidade' => $cidade, //Bairro do cliente
    'uf' => $uf, //Bairro do cliente
    'cep' => $CEP, // CEP do endereço de entrega
    'telefone' => $telefone, // Telefone do cliente
    'email' => $email, // E-mail do cliente para envio da NF-e
  );

  $valor_total = ($valor_venda * $quantidade);
  /**
   * Produtos
   */
  $data['produtos'] = array(
    array(
      'nome' => $nome_produto, // Nome do produto
      'ncm' => '87088000', // Código NCM
      'quantidade' => $quantidade, // Quantidade de itens
      'unidade' => 'UN', // Unidade de medida da quantidade de itens
      'origem' => 0, // Origem do produto
      'subtotal' => $valor_venda, // Preço unitário do produto - sem descontos
      'total' => $valor_total, // Preço total (quantidade x preço unitário) - sem descontos
      'classe_imposto' => 'REF150240576', // Classe de Imposto cadastrado no painel WebmaniaBR ou via API no endpoint /1/nfe/classe-imposto/
    )
  );

  /**
   * Informações do Pedido
   */
  $data['pedido'] = array(
    'presenca' => 1, // Indicador de presença do comprador no estabelecimento comercial no momento da operação
    'intermediador' => 0, // Indicador de intermediador/marketplace
    'modalidade_frete' => 9, // Modalidade do frete
    'frete' => '', // Total do frete
    'desconto' => '', // Total do desconto
    'total' => $valor_venda, // Valor total do pedido pago pelo cliente
    'pagamento' => $pagamento, // Indicador da forma de pagamento: 0 - Pagamento à vista ou 1 - Pagamento a prazo
    'forma_pagamento' => $forma_pagamento, // Meio de pagamento
    'desc_pagamento' => $desc_pagamento, // Valor total do pedido pago pelo cliente

  );

  }
// Emissão
$webmaniabr = new NFe('muDrdWTocAO1i1wYprEAUMH5Qj4SOWGP', 'UeqKZoZXADQVuH7oIgrKsjFDUQNwcf2uI1PqfSiNDsscUDkh', '4308-7lG0HND6iJ8Z7P8TY4vSr944RglEJgDl6N9a4kYruuprh73B', ' J4Lc8spnRF1IB3MlNMDOJRGB13BodYBGf5ndeosKb2ppFzGm');
$response = $webmaniabr->emissaoNotaFiscal( $data );

// Retorno
if (!isset($response->error)){

  echo '<h2>NF-e enviada com sucesso.</h2>';

  $uuid = (string) $response->uuid; // Número único de identificação da Nota Fiscal
  $status = (string) $response->status; // aprovado, reprovado, cancelado, processamento ou contingencia
  $motivo = (string) $response->motivo; // Motivo do status
  $nfe = (int) $response->nfe; // Número da NF-e
  $serie = (int) $response->serie; // Número de série
  $modelo = (string) $response->modelo; // Modelo da Nota Fiscal (nfe, nfce, cce)
  $chave = (string)$response->chave; // Número da chave de acesso
  $xml = (string) $response->xml; // URL do XML
  $danfe = (string) $response->danfe; // URL do XML
  $log = $response->log; // Log do Sefaz
  $data_abertura = date("Y-m-d H:i:s");
  $sql = "INSERT INTO cancelar_nota_fiscal (nome_cliente,data_nota,uuid) VALUES ('$nome_cliente','$data_abertura','$uuid')";
  if ($conn->query($sql) === TRUE) {

    echo'<br>';
    echo '<a href="' . $danfe . '" target="_blank"><button>Imprimir Danfe</button></a>';
  }

  exit();

} else {

  echo '<h2>Erro: '.$response->error.'</h2>';

  if (isset($response->log)){

    echo '<h2>Log:</h2>';
    echo '<ul>';

    foreach ($response->log as $erros){
      foreach ($erros as $erro) {
        echo '<li>'.$erro.'</li>';
      }
    }

    echo '</ul>';

  }

  exit();

}
}
?>
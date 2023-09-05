-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06-Set-2023 às 00:40
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pdt`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `debitos`
--

CREATE TABLE `debitos` (
  `id` int(11) NOT NULL,
  `data_debito` date NOT NULL,
  `nome` text NOT NULL,
  `valor_debito` float NOT NULL,
  `tipo` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `descricao` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `arquivo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `debitos`
--

INSERT INTO `debitos` (`id`, `data_debito`, `nome`, `valor_debito`, `tipo`, `descricao`, `arquivo`) VALUES
(1, '2023-09-07', 'caio', 200, 'ajuda', 'sua mãe', 'uploads/WAGNER CELTA.pdf'),
(2, '2023-09-07', 'caio', 200, 'ajuda', 'sua mãe', 'C:/xampp/htdocs/rzcode/uploads/WAGNER CELTA.pdf'),
(3, '2023-09-08', 'arthur', 200, 'ajuda', 'sua mãe', 'uploads/Boleto.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `referencia` text NOT NULL,
  `marca` text NOT NULL,
  `aplicacao` varchar(255) NOT NULL,
  `ano` int(255) NOT NULL,
  `quantidade` int(255) NOT NULL,
  `valor_custo` float NOT NULL,
  `valor_varejo` float NOT NULL,
  `valor_atacado` float NOT NULL,
  `localizacao` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `estoque`
--

INSERT INTO `estoque` (`id`, `nome`, `referencia`, `marca`, `aplicacao`, `ano`, `quantidade`, `valor_custo`, `valor_varejo`, `valor_atacado`, `localizacao`) VALUES
(1, 'caio', '', '', '', 0, 5, 0, 200, 265, 'mossoro'),
(4, 'ajai', 'sla', 'honda', '', 2012, 10, 0, 200, 289, 'mossoro'),
(5, 'aj', 'sla', 'honda', '', 2012, 20, 0, 200, 289, 'mossoro'),
(6, 'es', 'sla', 'honda', '', 2012, 20, 0, 200, 289, 'mossoro'),
(11, 'aj', 'sla', 'honda', 'motor', 2018, 10, 0, 200, 289, 'mossoro');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cpf_cnpj` varchar(255) NOT NULL,
  `CPF` varchar(255) NOT NULL,
  `CNPJ` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `nome_comprador` varchar(255) NOT NULL,
  `nome_peca` varchar(255) NOT NULL,
  `marca` text NOT NULL,
  `ano` int(255) NOT NULL,
  `referencia` text NOT NULL,
  `aplicacao` varchar(255) NOT NULL,
  `quantidade` int(255) NOT NULL,
  `cpf_cnpj` varchar(255) NOT NULL,
  `CPF` text NOT NULL,
  `CNPJ` text NOT NULL,
  `valor_venda` float NOT NULL,
  `forma_pagamento` varchar(255) NOT NULL,
  `numero_parcelas` int(255) NOT NULL,
  `funcionario_vendedor` varchar(255) NOT NULL,
  `garantia_produto` int(11) NOT NULL,
  `data_venda` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `nome_comprador`, `nome_peca`, `marca`, `ano`, `referencia`, `aplicacao`, `quantidade`, `cpf_cnpj`, `CPF`, `CNPJ`, `valor_venda`, `forma_pagamento`, `numero_parcelas`, `funcionario_vendedor`, `garantia_produto`, `data_venda`) VALUES
(8, 'sergio', 'caio', '', 0, '', '', 0, 'CPF', '01795205474', '', 250, 'Crédito', 0, 'fabio', 60, '2023-09-05'),
(9, 'sergio', 'caio', '', 0, '', '', 15, 'CPF', '01795205474', '', 220, 'Crédito', 0, 'fabio', 35, '2023-09-05'),
(10, 'sergio', 'caio', '', 0, '', '', 10, 'CNPJ', '', '51092075000180', 220, 'Débito', 0, 'fabio', 65, '2023-09-05'),
(11, 'sergio', 'caio', '', 0, '', '', 10, 'CNPJ', '', '51092075000180', 220, 'Débito', 0, 'fabio', 65, '2023-09-05'),
(12, 'sergio', 'caio', '', 0, '', '', 0, 'CPF', '01795205474', '', 200, 'Crédito', 0, 'fabio', 20, '2023-09-05'),
(13, 'sergio', 'caio', 'honda', 2012, 'sla', '', 20, 'CPF', '01795205474', '', 200, 'Parcelado', 0, 'fabio', 30, '2023-09-05'),
(14, 'sergio', 'caio', 'honda', 2012, 'sla', '', 20, 'CPF', '01795205474', '', 200, 'Parcelado', 0, 'fabio', 30, '2023-09-05'),
(15, 'sergio', 'aj', 'honda', 2018, 'sla', 'motor', 20, 'CPF', '01795205474', '', 289, 'Parcelado', 0, 'fabio', 20, '2023-09-05'),
(16, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 0, 'fabio', 70, '2023-09-05'),
(17, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 2, 'fabio', 70, '2023-09-05'),
(18, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 2, 'fabio', 70, '2023-09-05'),
(19, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 2, 'fabio', 70, '2023-09-05'),
(20, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 3, 'fabio', 20, '2023-09-05'),
(21, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 3, 'fabio', 20, '2023-09-05'),
(22, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 3, 'fabio', 20, '2023-09-05'),
(23, 'sergio', 'caio', '', 0, '', '', 10, 'CPF', '01795205474', '', 200, 'Parcelado', 3, 'fabio', 20, '0000-00-00'),
(24, 'sergio', 'aj', 'honda', 2018, 'sla', 'motor', 10, 'CPF', '01795205474', '', 289, 'Parcelado', 5, 'fabio', 25, NULL),
(25, 'sergio', 'aj', 'honda', 2018, 'sla', 'motor', 10, 'CPF', '01795205474', '', 289, 'Parcelado', 5, 'fabio', 25, '2023-09-05');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `debitos`
--
ALTER TABLE `debitos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `vendas`
--
ALTER TABLE `vendas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `debitos`
--
ALTER TABLE `debitos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

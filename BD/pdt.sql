-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03-Set-2023 às 00:29
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
-- Estrutura da tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `quantidade` int(255) NOT NULL,
  `valor_varejo` float NOT NULL,
  `valor_atacado` float NOT NULL,
  `localizacao` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `estoque`
--

INSERT INTO `estoque` (`id`, `nome`, `quantidade`, `valor_varejo`, `valor_atacado`, `localizacao`) VALUES
(1, 'caio', 65, 200, 265, 'mossoro'),
(2, 'caio', 5, 220, 265, 'mossoro');

-- --------------------------------------------------------

--
-- Estrutura da tabela `vendas`
--

CREATE TABLE `vendas` (
  `id` int(11) NOT NULL,
  `nome_comprador` varchar(255) NOT NULL,
  `nome_peca` varchar(255) NOT NULL,
  `quantidade` int(255) NOT NULL,
  `cpf_cnpj` varchar(255) NOT NULL,
  `CPF` text NOT NULL,
  `CNPJ` text NOT NULL,
  `valor_venda` float NOT NULL,
  `forma_pagamento` varchar(255) NOT NULL,
  `funcionario_vendedor` varchar(255) NOT NULL,
  `garantia_produto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `vendas`
--

INSERT INTO `vendas` (`id`, `nome_comprador`, `nome_peca`, `quantidade`, `cpf_cnpj`, `CPF`, `CNPJ`, `valor_venda`, `forma_pagamento`, `funcionario_vendedor`, `garantia_produto`) VALUES
(8, 'sergio', 'caio', 0, 'CPF', '01795205474', '', 250, 'Crédito', 'fabio', 60),
(9, 'sergio', 'caio', 15, 'CPF', '01795205474', '', 220, 'Crédito', 'fabio', 35),
(10, 'sergio', 'caio', 10, 'CNPJ', '', '51092075000180', 220, 'Débito', 'fabio', 65),
(11, 'sergio', 'caio', 10, 'CNPJ', '', '51092075000180', 220, 'Débito', 'fabio', 65),
(12, 'sergio', 'caio', 0, 'CPF', '01795205474', '', 200, 'Crédito', 'fabio', 20);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `estoque`
--
ALTER TABLE `estoque`
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
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `vendas`
--
ALTER TABLE `vendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

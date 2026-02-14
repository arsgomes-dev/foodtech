-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 14-Fev-2026 às 10:25
-- Versão do servidor: 11.4.4-MariaDB-log
-- versão do PHP: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dados: `food_thetec`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbsys_privilege_type`
--

CREATE TABLE `tbsys_privilege_type` (
  `id` int(4) NOT NULL,
  `description` varchar(40) NOT NULL,
  `description_type` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Extraindo dados da tabela `tbsys_privilege_type`
--

INSERT INTO `tbsys_privilege_type` (`id`, `description`, `description_type`) VALUES
(38, 'Alimento - Visualizar', 'food_view'),
(39, 'Alimento - Editar', 'food_edit'),
(40, 'Alimento - Deletar', 'food_delete'),
(41, 'Alimento - Cadastrar', 'food_create'),
(42, 'Marca - Visualizar', 'brand_view'),
(43, 'Marca - Editar', 'brand_edit'),
(44, 'Marca - Deletar', 'brand_delete'),
(45, 'Marca - Cadastrar', 'brand_create'),
(46, 'Grupo de Alimentos - Visualizar', 'food_group_view'),
(47, 'Grupo de Alimentos - Editar', 'food_group_edit'),
(48, 'Grupo de Alimentos - Cadastrar', 'food_group_create'),
(49, 'Tabela de Alimentos - Visualizar', 'food_table_view'),
(50, 'Tabela de Alimentos - Editar', 'food_table_edit');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tbsys_privilege_type`
--
ALTER TABLE `tbsys_privilege_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbsys_privilege_type`
--
ALTER TABLE `tbsys_privilege_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

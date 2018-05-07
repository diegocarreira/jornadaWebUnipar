-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 24-Abr-2018 às 16:04
-- Versão do servidor: 5.5.51-38.2
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inter670_api_filmes`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `filmes`
--

CREATE TABLE IF NOT EXISTS `filmes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `lancamento` datetime NOT NULL,
  `sinopse` text NOT NULL,
  `id_genero` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `filmes`
--

INSERT INTO `filmes` (`id`, `titulo`, `lancamento`, `sinopse`, `id_genero`) VALUES
(1, 'Capitão América - O Primeiro Vingador', '2011-07-29 00:00:00', '', 2),
(2, 'Homem de Ferro', '2008-04-30 00:00:00', '', 2),
(3, 'A Espiã Que Sabia de Menos', '2015-06-04 00:00:00', '', 1),
(4, 'Até O Último Homem', '2017-01-26 00:00:00', '', 4),
(5, 'Hotel Transilvânia 2', '2015-09-24 00:00:00', '', 1),
(6, 'Divertida Mente', '2015-06-18 00:00:00', '', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `filmes_imagens`
--

CREATE TABLE IF NOT EXISTS `filmes_imagens` (
  `id` int(11) NOT NULL,
  `id_filme` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ordem` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `filmes_imagens`
--

INSERT INTO `filmes_imagens` (`id`, `id_filme`, `url`, `ordem`) VALUES
(1, 1, 'http://br.web.img3.acsta.net/r_1920_1080/medias/nmedia/18/87/34/62/19922687.jpg', 0),
(2, 2, 'http://br.web.img3.acsta.net/r_1920_1080/medias/nmedia/18/91/79/19/20163665.jpg', 0),
(3, 3, 'http://br.web.img3.acsta.net/r_1920_1080/pictures/15/05/20/14/20/080698.jpg', 0),
(4, 4, 'http://br.web.img3.acsta.net/r_1920_1080/pictures/16/11/21/15/29/457312.jpg', 0),
(5, 5, 'http://br.web.img3.acsta.net/r_1920_1080/pictures/15/09/11/23/14/389912.jpg', 0),
(6, 6, 'http://br.web.img3.acsta.net/r_1920_1080/pictures/15/05/14/14/20/365361.jpg', 0),
(7, 6, 'http://br.web.img3.acsta.net/r_1920_1080/pictures/15/06/03/20/19/232442.jpg', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `generos`
--

CREATE TABLE IF NOT EXISTS `generos` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `generos`
--

INSERT INTO `generos` (`id`, `nome`) VALUES
(1, 'Comédia'),
(2, 'Fantasia'),
(3, 'Herói'),
(4, 'Guerra'),
(5, 'Animação');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(11, 'aa', 'aa', 'aa'),
(12, 'bb', 'bb', 'bb'),
(13, 'cc', 'cc', 'cc'),
(14, 'dd', 'dd', 'dd'),
(15, 'ee', 'ee', 'ee'),
(16, 'ee', 'ee', '123'),
(17, 'teste', 'teste', 'teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_filmes`
--

CREATE TABLE IF NOT EXISTS `usuarios_filmes` (
  `id_usuario` int(11) NOT NULL,
  `id_filme` int(11) NOT NULL,
  `favorito` tinyint(1) NOT NULL DEFAULT '0',
  `assistido` tinyint(1) NOT NULL DEFAULT '0',
  `nota` tinyint(4) DEFAULT NULL,
  `comentario` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_token`
--

CREATE TABLE IF NOT EXISTS `usuarios_token` (
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `validade` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios_token`
--

INSERT INTO `usuarios_token` (`id_usuario`, `token`, `validade`) VALUES
(15, '2db71f197a5ecdb371bf530cb41074c6', '2018-04-25 07:02:55'),
(16, '94b54efda600345669406e7e13a34a4a', '2018-04-25 01:51:07'),
(17, '8a29597f2ebf252898bb7ad714abfbc6', '2018-04-25 06:51:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `filmes`
--
ALTER TABLE `filmes`
  ADD PRIMARY KEY (`id`), ADD KEY `id_genero` (`id_genero`);

--
-- Indexes for table `filmes_imagens`
--
ALTER TABLE `filmes_imagens`
  ADD PRIMARY KEY (`id`), ADD KEY `id_filme` (`id_filme`);

--
-- Indexes for table `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios_filmes`
--
ALTER TABLE `usuarios_filmes`
  ADD PRIMARY KEY (`id_usuario`,`id_filme`), ADD KEY `id_filme` (`id_filme`);

--
-- Indexes for table `usuarios_token`
--
ALTER TABLE `usuarios_token`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `filmes`
--
ALTER TABLE `filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `filmes_imagens`
--
ALTER TABLE `filmes_imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `filmes`
--
ALTER TABLE `filmes`
ADD CONSTRAINT `filmes_ibfk_1` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id`);

--
-- Limitadores para a tabela `filmes_imagens`
--
ALTER TABLE `filmes_imagens`
ADD CONSTRAINT `filmes_imagens_ibfk_1` FOREIGN KEY (`id_filme`) REFERENCES `filmes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuarios_filmes`
--
ALTER TABLE `usuarios_filmes`
ADD CONSTRAINT `usuarios_filmes_ibfk_1` FOREIGN KEY (`id_filme`) REFERENCES `filmes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `usuarios_filmes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuarios_token`
--
ALTER TABLE `usuarios_token`
ADD CONSTRAINT `usuarios_token_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

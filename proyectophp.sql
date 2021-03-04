-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 14-02-2021 a las 21:55:33
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `proyectophp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--
CREATE DATABASE IF NOT EXISTS proyectophp;
use proyectophp;

CREATE TABLE IF NOT EXISTS `carrito` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelo` varchar(50) NOT NULL,
  `imagen` varchar(100) NOT NULL,
  `precio` int(11) NOT NULL,
  `talla` int(11) NOT NULL,
  `color` varchar(100) NOT NULL,
  `idusuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcado de datos para la tabla `carrito`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` varchar(20) NOT NULL,
  `imagenmarca` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `categorias` (`id`, `marca`, `imagenmarca`) VALUES
(1, 'Nike', 'nike.jpg'),
(2, 'Adidas', 'adidas.jpg'),
(3, 'Jordan', 'jordan.jpg'),
(4, 'Fila', 'fila.jpg'),
(5, 'Reebok', 'reebok.jpg'),
(6, 'Puma', 'puma.png'),
(7, 'Under Armour', 'ua.png');
--
-- Volcado de datos para la tabla `categorias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tallas`
--

CREATE TABLE IF NOT EXISTS `tallas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `talla` int(11) NOT NULL,
  `color` varchar(30) NOT NULL,
  `stock` int(11) NOT NULL,
  `idzapatilla` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idzapatilla` (`idzapatilla`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `tallas` (`id`, `talla`, `color`, `stock`, `idzapatilla`) VALUES
(1, 40, 'Blanco', 20, 1),
(2, 45, 'Blanco', 20, 1),
(3, 42, 'Marron/Blanco', 2, 3);

--
-- Volcado de datos para la tabla `tallas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `tarjeta` varchar(10)  not NULL,
  `email` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `usuarios` (`id`, `nombre`, `password`, `email`) VALUES
(1, 'Root', '62e1545ac1972b45683b5e53ecc05e45', 'root@gmail.com');

--
-- Volcado de datos para la tabla `usuarios`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarioseliminados`
--

CREATE TABLE IF NOT EXISTS `usuarioseliminados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL,
  `email` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcado de datos para la tabla `usuarioseliminados`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` text NOT NULL,
  `modelo` varchar(20) NOT NULL,
  `talla` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `color` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcado de datos para la tabla `ventas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zapatillas`
--

CREATE TABLE IF NOT EXISTS `zapatillas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelo` varchar(100) NOT NULL,
  `imagen` varchar(30) NOT NULL,
  `precio` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idcategoria` (`idcategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `zapatillas` (`id`, `modelo`, `imagen`, `precio`, `idcategoria`) VALUES
(1, 'Air Force 1', 'af1.jpg', 100, 1),
(2, 'Yeezy Black Red', 'yeezy.webp', 400, 2),
(3, 'Jordan 1 Travis Scott', 'j1tc.jpg', 1200, 3);

--
-- Volcado de datos para la tabla `zapatillas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zapatillasvendidas`
--

CREATE TABLE IF NOT EXISTS `zapatillaseliminadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modelo` varchar(20) NOT NULL,
  `imagen` varchar(15) NOT NULL,
  `precio` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idcategoria` (`idcategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcado de datos para la tabla `zapatillasvendidas`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

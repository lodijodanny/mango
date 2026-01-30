-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-01-2026 a las 13:16:32
-- Versión del servidor: 5.7.23-23
-- Versión de PHP: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mango`
--
CREATE DATABASE IF NOT EXISTS `mango` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `mango`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bases_datos`
--

DROP TABLE IF EXISTS `bases_datos`;
CREATE TABLE IF NOT EXISTS `bases_datos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `base` int(15) NOT NULL,
  `local` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bases_denominaciones`
--

DROP TABLE IF EXISTS `bases_denominaciones`;
CREATE TABLE IF NOT EXISTS `bases_denominaciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `base_id` int(15) NOT NULL,
  `denominacion_id` int(15) NOT NULL,
  `denominacion` varchar(15) NOT NULL,
  `cantidad` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierres_datos`
--

DROP TABLE IF EXISTS `cierres_datos`;
CREATE TABLE IF NOT EXISTS `cierres_datos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `cierre` int(15) NOT NULL,
  `local` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierres_denominaciones`
--

DROP TABLE IF EXISTS `cierres_denominaciones`;
CREATE TABLE IF NOT EXISTS `cierres_denominaciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `cierre_id` int(15) NOT NULL,
  `denominacion_id` int(15) NOT NULL,
  `denominacion` varchar(15) NOT NULL,
  `cantidad` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `documento_tipo` varchar(15) NOT NULL,
  `documento` varchar(15) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componentes`
--

DROP TABLE IF EXISTS `componentes`;
CREATE TABLE IF NOT EXISTS `componentes` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `unidad` varchar(50) NOT NULL,
  `componente` varchar(255) NOT NULL,
  `costo_unidad` varchar(15) NOT NULL,
  `proveedor` int(15) NOT NULL,
  `productor` int(15) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composiciones`
--

DROP TABLE IF EXISTS `composiciones`;
CREATE TABLE IF NOT EXISTS `composiciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `producto` int(15) NOT NULL,
  `componente` int(15) NOT NULL,
  `cantidad` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `composiciones_componentes_producidos`
--

DROP TABLE IF EXISTS `composiciones_componentes_producidos`;
CREATE TABLE IF NOT EXISTS `composiciones_componentes_producidos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `componente_producido` int(15) NOT NULL,
  `componente` int(15) NOT NULL,
  `cantidad` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `denominaciones`
--

DROP TABLE IF EXISTS `denominaciones`;
CREATE TABLE IF NOT EXISTS `denominaciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `denominacion` int(15) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descuentos`
--

DROP TABLE IF EXISTS `descuentos`;
CREATE TABLE IF NOT EXISTS `descuentos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `descuento` varchar(150) NOT NULL,
  `porcentaje` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos`
--

DROP TABLE IF EXISTS `despachos`;
CREATE TABLE IF NOT EXISTS `despachos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `fecha_envio` datetime NOT NULL,
  `fecha_recibe` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `origen` int(15) NOT NULL,
  `destino` int(15) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `usuario_recibe` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos_componentes`
--

DROP TABLE IF EXISTS `despachos_componentes`;
CREATE TABLE IF NOT EXISTS `despachos_componentes` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `despacho_id` int(15) NOT NULL,
  `componente_id` int(15) NOT NULL,
  `cantidad` int(15) NOT NULL,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_plantillas`
--

DROP TABLE IF EXISTS `facturas_plantillas`;
CREATE TABLE IF NOT EXISTS `facturas_plantillas` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `texto_superior` text NOT NULL,
  `texto_inferior` text NOT NULL,
  `local` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los datos de las mesas, barras, puntos de venta...';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

DROP TABLE IF EXISTS `gastos`;
CREATE TABLE IF NOT EXISTS `gastos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `concepto` varchar(150) NOT NULL,
  `valor` int(15) NOT NULL,
  `local` int(15) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `periodicidad` int(3) NOT NULL,
  `imagen` varchar(2) NOT NULL,
  `imagen_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los distintos tipos de impuestos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impuestos`
--

DROP TABLE IF EXISTS `impuestos`;
CREATE TABLE IF NOT EXISTS `impuestos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `impuesto` varchar(50) NOT NULL,
  `porcentaje` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los distintos tipos de impuestos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

DROP TABLE IF EXISTS `inventario`;
CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `componente_id` int(15) NOT NULL,
  `componente` varchar(150) NOT NULL,
  `cantidad` varchar(15) NOT NULL,
  `unidad` varchar(150) NOT NULL,
  `minimo` varchar(15) NOT NULL,
  `maximo` varchar(15) NOT NULL,
  `local_id` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_novedades`
--

DROP TABLE IF EXISTS `inventario_novedades`;
CREATE TABLE IF NOT EXISTS `inventario_novedades` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `inventario_id` int(15) NOT NULL,
  `cantidad_anterior` int(15) NOT NULL,
  `operacion` varchar(15) NOT NULL,
  `cantidad_modificada` int(15) NOT NULL,
  `cantidad_nueva` int(15) NOT NULL,
  `motivo` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los datos de las mesas, barras, puntos de venta...';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locales`
--

DROP TABLE IF EXISTS `locales`;
CREATE TABLE IF NOT EXISTS `locales` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `local` varchar(50) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `apertura` time NOT NULL,
  `cierre` time NOT NULL,
  `propina` int(3) NOT NULL,
  `imagen` varchar(2) NOT NULL,
  `imagen_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los locales y puntos de venta';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producciones`
--

DROP TABLE IF EXISTS `producciones`;
CREATE TABLE IF NOT EXISTS `producciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `fecha_envio` datetime NOT NULL,
  `fecha_recibe` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `origen` int(15) NOT NULL,
  `destino` int(15) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `usuario_recibe` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producciones_componentes`
--

DROP TABLE IF EXISTS `producciones_componentes`;
CREATE TABLE IF NOT EXISTS `producciones_componentes` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `produccion_id` int(15) NOT NULL,
  `componente_id` int(15) NOT NULL,
  `cantidad` int(15) NOT NULL,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `categoria` int(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `local` int(255) NOT NULL,
  `zona` int(15) NOT NULL,
  `producto` varchar(255) NOT NULL,
  `precio` int(255) NOT NULL,
  `impuesto_id` int(15) NOT NULL,
  `impuesto_incluido` varchar(15) NOT NULL,
  `descripcion` text,
  `codigo_barras` varchar(100) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `imagen_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_categorias`
--

DROP TABLE IF EXISTS `productos_categorias`;
CREATE TABLE IF NOT EXISTS `productos_categorias` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `adicion` varchar(5) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `imagen` varchar(2) NOT NULL,
  `imagen_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena las categorías en las que están divididas los produ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_variaciones`
--

DROP TABLE IF EXISTS `productos_variaciones`;
CREATE TABLE IF NOT EXISTS `productos_variaciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `producto_id` int(255) NOT NULL,
  `producto` varchar(255) NOT NULL,
  `variacion` varchar(255) NOT NULL,
  `grupo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `proveedor` varchar(255) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `imagen` varchar(2) NOT NULL,
  `imagen_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_pagos`
--

DROP TABLE IF EXISTS `tipos_pagos`;
CREATE TABLE IF NOT EXISTS `tipos_pagos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los diferentes tipos de pagos ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
CREATE TABLE IF NOT EXISTS `ubicaciones` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `ubicacion` varchar(50) NOT NULL,
  `ubicada` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `local` int(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los datos de las mesas, barras, puntos de venta...';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(50) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `local` int(15) NOT NULL,
  `imagen` varchar(2) NOT NULL,
  `imagen_nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los usuarios y personas que tienen acceso';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_permisos`
--

DROP TABLE IF EXISTS `usuarios_permisos`;
CREATE TABLE IF NOT EXISTS `usuarios_permisos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `id_usuario` int(15) NOT NULL,
  `ajustes` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `ventas` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `zonas_entregas` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `base` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `cierre` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `compras` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `producciones` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `inventario` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `gastos` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `clientes` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `reportes` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_datos`
--

DROP TABLE IF EXISTS `ventas_datos`;
CREATE TABLE IF NOT EXISTS `ventas_datos` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `fecha_cierre` datetime NOT NULL,
  `usuario_id` int(15) NOT NULL,
  `local_id` int(15) NOT NULL,
  `ubicacion_id` int(15) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `cliente_id` int(15) NOT NULL,
  `tipo_pago_id` int(15) NOT NULL,
  `tipo_pago` varchar(255) NOT NULL,
  `estado` varchar(15) NOT NULL,
  `total_bruto` int(15) NOT NULL,
  `descuento_id` int(15) NOT NULL,
  `descuento_porcentaje` varchar(15) NOT NULL,
  `descuento_valor` int(15) NOT NULL,
  `propina` int(15) NOT NULL,
  `total_neto` int(15) NOT NULL,
  `observaciones` text NOT NULL,
  `eliminar_motivo` text NOT NULL,
  `pago` varchar(15) NOT NULL,
  `fecha_pago` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

DROP TABLE IF EXISTS `ventas_productos`;
CREATE TABLE IF NOT EXISTS `ventas_productos` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `venta_id` int(15) NOT NULL,
  `ubicacion_id` int(15) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `categoria_id` int(15) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `local` int(15) NOT NULL,
  `zona` int(15) NOT NULL,
  `producto_id` int(15) NOT NULL,
  `producto` varchar(255) NOT NULL,
  `precio_final` int(15) NOT NULL,
  `porcentaje_impuesto` int(3) NOT NULL,
  `estado` varchar(255) NOT NULL,
  `estado_zona_entregas` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas_entregas`
--

DROP TABLE IF EXISTS `zonas_entregas`;
CREATE TABLE IF NOT EXISTS `zonas_entregas` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `usuario` int(15) NOT NULL,
  `zona` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena los datos de las mesas, barras, puntos de venta...';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-01-2026 a las 13:17:44
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

--
-- Volcado de datos para la tabla `bases_datos`
--

INSERT INTO `bases_datos` (`id`, `fecha`, `usuario`, `base`, `local`) VALUES
(1, '2022-11-22 15:58:47', 1, 400000, 1),
(2, '2022-11-22 15:59:43', 1, 400000, 6),
(3, '2023-09-05 10:42:25', 1, 120000, 1);

--
-- Volcado de datos para la tabla `bases_denominaciones`
--

INSERT INTO `bases_denominaciones` (`id`, `fecha`, `usuario`, `base_id`, `denominacion_id`, `denominacion`, `cantidad`) VALUES
(1, '2022-11-22 15:59:01', 1, 1, 1, '50000', 4),
(2, '2022-11-22 15:59:17', 1, 1, 2, '20000', 10),
(3, '2022-11-22 15:59:45', 1, 2, 1, '50000', 4),
(4, '2022-11-22 15:59:49', 1, 2, 2, '20000', 10),
(5, '2023-09-05 10:42:34', 1, 3, 1, '50000', 2),
(6, '2023-09-05 10:42:37', 1, 3, 2, '20000', 1);

--
-- Volcado de datos para la tabla `cierres_datos`
--

INSERT INTO `cierres_datos` (`id`, `fecha`, `usuario`, `cierre`, `local`) VALUES
(1, '2022-03-01 00:33:23', 1, 20000, 1),
(2, '2023-09-05 10:43:19', 1, 190000, 1);

--
-- Volcado de datos para la tabla `cierres_denominaciones`
--

INSERT INTO `cierres_denominaciones` (`id`, `fecha`, `usuario`, `cierre_id`, `denominacion_id`, `denominacion`, `cantidad`) VALUES
(1, '2022-03-01 00:33:30', 1, 1, 2, '20000', 1),
(2, '2023-09-05 10:43:26', 1, 2, 1, '50000', 3),
(3, '2023-09-05 10:43:28', 1, 2, 2, '20000', 2);

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `fecha`, `usuario`, `nombre`, `documento_tipo`, `documento`, `correo`, `telefono`, `direccion`) VALUES
(1, '2023-05-15 13:03:56', 1, 'Danny Estrada', 'CC', '8359856', 'dannyws@gmail.com', '322975008', ''),
(2, '2023-10-06 15:23:44', 1, 'Juan Alimaña', 'cedula extranje', '123456', '', '3696969', 'Carrera 129 #56-7'),
(3, '2024-02-19 13:29:00', 1, 'Eduardo ', 'NIT', '8885421645', 'corozo@gmail.com', '3213215555', 'argelia');

--
-- Volcado de datos para la tabla `componentes`
--

INSERT INTO `componentes` (`id`, `fecha`, `usuario`, `unidad`, `componente`, `costo_unidad`, `proveedor`, `productor`, `tipo`) VALUES
(1, '2022-02-22 18:40:23', 1, 'ml', 'Leche', '5', 1, 0, 'comprado'),
(2, '2022-02-22 18:40:40', 1, 'gr', 'Café en polvo', '6', 1, 0, 'comprado'),
(3, '2022-03-08 11:47:27', 1, 'unid', 'Palito de queso hojaldrado ', '0', 0, 1, 'comprado'),
(4, '2022-03-08 11:48:22', 1, 'unid', 'Arepa de huevo  ', '0', 0, 1, 'comprado'),
(5, '2022-03-08 11:50:26', 1, 'unid', 'Copa de helado sin azúcar  ', '0', 0, 1, 'comprado'),
(6, '2022-03-08 12:19:39', 3, 'ml', 'Salsa de fresa ', '2', 2, 0, 'comprado'),
(7, '2022-03-08 12:20:15', 3, 'unid', 'Cómo de galleta ', '100', 2, 0, 'comprado'),
(8, '2022-03-08 12:20:44', 3, 'ml', 'Helado de vainilla ', '100', 2, 0, 'comprado'),
(9, '2023-11-22 14:33:24', 1, 'unid', 'multigrano', '100', 4, 1, 'comprado'),
(10, '2023-11-22 14:32:38', 1, 'gr', 'chocolate amargo', '1', 4, 0, 'comprado'),
(11, '2023-11-22 14:35:33', 1, 'gr', 'azucar', '5', 1, 0, 'comprado'),
(12, '2023-11-22 14:35:47', 1, 'gr', 'panela en polvo', '9', 1, 0, 'comprado'),
(13, '2024-02-19 13:54:09', 1, 'gr', 'mezcla po', '109', 0, 1, 'producido'),
(14, '2024-04-05 11:11:02', 1, 'gr', 'Carne res', '10', 5, 0, 'comprado'),
(15, '2024-04-05 11:12:22', 1, 'unid', 'Queso mozarella', '300', 1, 0, 'comprado'),
(16, '2024-04-05 11:12:50', 1, 'unid', 'Pan artesanal hamburguesa', '400', 3, 0, 'comprado'),
(17, '2025-02-24 15:30:22', 1, 'gr', 'azucar morena', '2', 1, 0, 'comprado'),
(18, '2025-02-24 15:31:18', 1, 'gr', 'chocolo desgranado', '50', 1, 0, 'comprado'),
(19, '2025-02-24 15:31:59', 1, 'gr', 'harina de trigo', '70', 1, 0, 'comprado'),
(20, '2025-04-10 14:09:44', 1, 'gr', 'MEZCLA CHOCOLO ', '295', 0, 4, 'producido');

--
-- Volcado de datos para la tabla `composiciones`
--

INSERT INTO `composiciones` (`id`, `fecha`, `usuario`, `producto`, `componente`, `cantidad`) VALUES
(1, '2022-02-22 18:41:46', 1, 1, 1, '200'),
(2, '2022-02-22 18:42:11', 1, 1, 2, '75'),
(3, '2022-03-08 11:47:27', 1, 10, 3, '1'),
(4, '2022-03-08 11:48:22', 1, 11, 4, '1'),
(5, '2022-03-08 11:50:26', 1, 13, 5, '1'),
(6, '2022-03-08 12:24:33', 1, 12, 6, '3'),
(7, '2022-03-08 12:25:06', 1, 12, 7, '1'),
(9, '2022-03-08 12:26:12', 1, 12, 8, '15'),
(10, '2023-11-22 13:50:26', 1, 19, 9, '1'),
(11, '2023-11-22 14:34:42', 1, 18, 10, '250'),
(12, '2023-11-22 14:35:59', 1, 18, 11, '20'),
(13, '2023-11-22 14:36:12', 1, 18, 12, '50'),
(14, '2024-02-19 13:50:32', 1, 22, 11, '1'),
(15, '2024-02-19 13:50:48', 1, 22, 2, '1'),
(17, '2024-04-05 11:14:52', 1, 23, 14, '160'),
(18, '2024-04-05 11:15:45', 1, 23, 15, '2'),
(19, '2024-04-05 11:16:13', 1, 23, 16, '1'),
(21, '2025-02-24 15:34:17', 1, 24, 19, '5'),
(23, '2025-02-24 15:35:01', 1, 24, 17, '100'),
(25, '2025-02-24 15:36:00', 1, 24, 18, '20');

--
-- Volcado de datos para la tabla `composiciones_componentes_producidos`
--

INSERT INTO `composiciones_componentes_producidos` (`id`, `fecha`, `usuario`, `componente_producido`, `componente`, `cantidad`) VALUES
(1, '2024-02-19 13:53:54', 1, 13, 12, '1'),
(2, '2024-02-19 13:54:05', 1, 13, 7, '1'),
(5, '2025-04-10 14:09:13', 1, 20, 11, '3'),
(6, '2025-04-10 14:09:26', 1, 20, 19, '4');

--
-- Volcado de datos para la tabla `denominaciones`
--

INSERT INTO `denominaciones` (`id`, `fecha`, `usuario`, `denominacion`, `tipo`) VALUES
(1, '2018-06-07 08:47:13', 1, 50000, 'billete'),
(2, '2018-06-07 08:47:13', 1, 20000, 'billete'),
(3, '2018-06-07 08:47:13', 1, 10000, 'billete'),
(4, '2018-06-07 08:47:13', 1, 5000, 'billete'),
(5, '2018-06-07 08:47:13', 1, 2000, 'billete'),
(6, '2018-06-07 08:47:13', 1, 1000, 'billete'),
(7, '2018-06-07 08:47:13', 1, 500, 'billete'),
(8, '2018-06-07 08:47:13', 1, 200, 'billete'),
(9, '2018-06-07 08:47:13', 1, 100, 'billete'),
(10, '2018-06-07 08:47:13', 1, 50, 'billete');

--
-- Volcado de datos para la tabla `descuentos`
--

INSERT INTO `descuentos` (`id`, `fecha`, `usuario`, `descuento`, `porcentaje`) VALUES
(1, '2022-01-25 15:17:26', 1, 'Clientes VIP', 10),
(2, '2022-03-08 11:35:56', 1, 'Familia', 50),
(3, '2024-04-05 10:54:10', 1, 'Socios y clientes', 25),
(4, '2026-01-28 19:06:22', 1, 'cortesia', 100);

--
-- Volcado de datos para la tabla `despachos`
--

INSERT INTO `despachos` (`id`, `fecha`, `fecha_envio`, `fecha_recibe`, `usuario`, `origen`, `destino`, `estado`, `usuario_recibe`) VALUES
(1, '2022-03-08 12:28:33', '2022-03-08 12:32:02', '2022-03-08 12:32:59', 1, 0, 4, 'recibido', 1),
(2, '2022-03-08 12:38:07', '2022-03-08 12:39:43', '2022-03-08 12:40:16', 3, 0, 4, 'recibido', 1),
(3, '2022-11-22 16:06:08', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, 6, 'creado', 0),
(4, '2023-02-01 15:54:19', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, 5, 'creado', 0),
(5, '2023-10-06 15:12:06', '2023-10-06 15:13:35', '2023-10-06 15:14:15', 1, 0, 1, 'recibido', 1),
(6, '2023-10-06 15:17:39', '2023-10-06 15:17:52', '2023-10-06 15:18:15', 1, 0, 1, 'recibido', 1),
(7, '2023-11-22 14:37:16', '2023-11-22 14:38:50', '2023-11-22 14:39:24', 1, 0, 7, 'recibido', 1),
(8, '2023-11-22 17:59:58', '2023-11-22 18:01:28', '2023-11-22 18:02:21', 7, 0, 7, 'recibido', 7),
(9, '2024-04-05 11:17:41', '2024-04-05 11:20:19', '2024-04-05 11:21:10', 1, 0, 1, 'recibido', 1),
(10, '2025-02-24 15:37:27', '2025-02-24 15:40:31', '2025-02-24 15:41:20', 1, 0, 1, 'recibido', 1),
(11, '2025-04-10 14:16:26', '2025-04-10 14:17:02', '2025-04-10 14:17:21', 1, 0, 1, 'recibido', 1);

--
-- Volcado de datos para la tabla `despachos_componentes`
--

INSERT INTO `despachos_componentes` (`id`, `fecha`, `usuario`, `despacho_id`, `componente_id`, `cantidad`, `estado`) VALUES
(1, '2022-03-08 12:29:10', 1, 1, 8, 1000, 'recibido'),
(2, '2022-03-08 12:29:43', 1, 1, 7, 50, 'recibido'),
(3, '2022-03-08 12:30:09', 1, 1, 6, 2000, 'recibido'),
(5, '2022-03-08 12:39:37', 3, 2, 7, 100, 'recibido'),
(6, '2023-10-06 15:12:32', 1, 5, 2, 1000, 'recibido'),
(7, '2023-10-06 15:12:56', 1, 5, 4, 50, 'recibido'),
(8, '2023-10-06 15:13:21', 1, 5, 7, 25, 'recibido'),
(9, '2023-10-06 15:17:49', 1, 6, 4, 5, 'recibido'),
(10, '2023-11-22 14:38:02', 1, 7, 10, 5000, 'recibido'),
(11, '2023-11-22 14:38:22', 1, 7, 11, 10000, 'recibido'),
(12, '2023-11-22 14:38:35', 1, 7, 12, 2000, 'recibido'),
(14, '2023-11-22 18:00:57', 7, 8, 10, 2000, 'recibido'),
(15, '2024-04-05 11:18:24', 1, 9, 14, 3000, 'recibido'),
(16, '2024-04-05 11:19:15', 1, 9, 16, 50, 'recibido'),
(17, '2024-04-05 11:19:53', 1, 9, 15, 100, 'recibido'),
(18, '2025-02-24 15:37:59', 1, 10, 19, 10000, 'recibido'),
(19, '2025-02-24 15:38:33', 1, 10, 18, 3000, 'recibido'),
(21, '2025-02-24 15:39:54', 1, 10, 17, 500, 'recibido'),
(22, '2025-04-10 14:16:51', 1, 11, 4, 5, 'recibido');

--
-- Volcado de datos para la tabla `facturas_plantillas`
--

INSERT INTO `facturas_plantillas` (`id`, `fecha`, `usuario`, `nombre`, `titulo`, `texto_superior`, `texto_inferior`, `local`) VALUES
(1, '2024-04-05 10:57:58', 1, 'Plantilla Principal', 'Factura de venta', 'NIT 900009999\r\nSomo régimen simplificado\r\nResolución de facturación No xxxxxx\r\nDesde xxxxx Hasta xxxxx', 'Gracias por su compra\r\nSiguenos en redes @mangoappcox', 1),
(2, '2022-03-08 11:38:36', 1, 'Genérica catalina ', 'Factura de venta ', '901006397 \r\nRégimen contributivo ', 'Gracias por su compra \r\nTe esperamos de nuevo\r\nRecuerda seguirnos en nuestras redes ', 4),
(3, '2022-03-29 13:59:25', 1, 'Factura pipe', 'Factura de venta', 'Nit 900789990\r\nSomos regimen nazi\r\nresolucion de fact desde xxxxx hasta xxxx', 'Siguenos en redes @pipecafe\r\npropina voluntaria no sea tacano', 5),
(4, '2022-10-20 16:42:07', 1, 'Plantilla perritos', 'Factura de venta ', 'Somos régimen simplificado\r\nNit xxxxxx\r\nResolucion desde xxxx hasta xxxxx', 'Gracias por su compra\r\nSíguenos en @losperritos_dedistrito33', 6),
(5, '2023-11-22 13:40:11', 1, 'generica migaito', 'factura de venta', 'nit 1037621539 \r\nno responsables de iva\r\n', 'gracias por su compra\r\nsiguenos en @migaito_med\r\n', 7),
(6, '2023-12-29 13:50:34', 9, 'Plantilla Lorena', 'Factura de venta', 'No responsables de iva \r\npersona natural\r\n', 'Gracias por su compra\r\nSíguenos en @lorettaarepas ', 8),
(7, '2026-01-28 19:12:10', 1, 'Factura basica', 'Factura de venta', 'Nit 71674466\r\nSomos regimen simplicado\r\nno responsabes de iva', 'Gracias por su compra\r\nUna experiencia deliciosa\r\nSiguenos en @parrillapamplona', 10);

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id`, `fecha`, `usuario`, `tipo`, `concepto`, `valor`, `local`, `estado`, `fecha_pago`, `periodicidad`, `imagen`, `imagen_nombre`) VALUES
(1, '2022-11-22 16:09:34', 1, 'operativo', 'Bavaria', 250000, 6, 'pagado', '2022-11-22 00:00:00', 8, 'no', '20221122160934'),
(2, '2024-04-05 10:42:17', 1, 'administrativo', 'Arriendo local', 2000000, 1, 'pendiente', '2024-04-05 00:00:00', 30, 'no', '20240405104141'),
(3, '2026-01-28 18:46:05', 1, 'operativo', '10 bultos de carbon', 48000, 10, 'pagado', '2026-01-28 00:00:00', 3, 'no', '20260128184605');

--
-- Volcado de datos para la tabla `impuestos`
--

INSERT INTO `impuestos` (`id`, `fecha`, `usuario`, `impuesto`, `porcentaje`) VALUES
(1, '2022-01-25 15:15:07', 1, 'Sin impuesto', 0),
(2, '2022-01-25 15:15:15', 1, 'IVA', 19),
(3, '2022-03-08 11:34:13', 1, 'ICO', 7),
(4, '2022-10-20 16:36:45', 6, 'Superiva', 0),
(5, '2023-11-22 13:36:30', 1, 'Ipoconsumo', 8),
(6, '2023-12-29 13:47:27', 9, 'Sin impuestos ', 0),
(7, '2024-04-05 10:52:41', 1, 'IVA Petro', 45);

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `fecha`, `usuario`, `componente_id`, `componente`, `cantidad`, `unidad`, `minimo`, `maximo`, `local_id`) VALUES
(1, '2022-03-08 12:32:42', 1, 6, 'Salsa de fresa ', '1967', 'ml', '400', '2000', 4),
(2, '2022-03-08 12:40:16', 1, 7, 'Cómo de galleta ', '129', 'unid', '25', '129', 4),
(3, '2022-03-08 12:32:59', 1, 8, 'Helado de vainilla ', '835', 'ml', '200', '1000', 4),
(4, '2023-10-06 15:14:12', 1, 7, 'Cómo de galleta ', '25', 'unid', '5', '25', 1),
(5, '2025-04-10 14:17:21', 1, 4, 'Arepa de huevo  ', '50', 'unid', '10', '50', 1),
(6, '2023-10-06 15:14:15', 1, 2, 'Café en polvo', '94', 'gr', '200', '1000', 1),
(7, '2023-11-22 14:39:19', 1, 12, 'panela en polvo', '1800', 'gr', '400', '2000', 7),
(8, '2023-11-22 14:39:22', 1, 11, 'azucar', '9914', 'gr', '2000', '10000', 7),
(9, '2023-11-22 18:01:55', 1, 10, 'chocolate amargo', '6000', 'gr', '1200', '6000', 7),
(10, '2024-04-05 11:21:01', 1, 15, 'Queso mozarella', '76', 'unid', '20', '100', 1),
(11, '2024-04-05 11:21:06', 1, 16, 'Pan artesanal hamburguesa', '38', 'unid', '10', '50', 1),
(12, '2024-04-05 11:21:10', 1, 14, 'Carne res', '1080', 'gr', '600', '3000', 1),
(13, '2025-02-24 15:41:15', 1, 17, 'azucar morena', '-1000', 'gr', '100', '500', 1),
(14, '2025-02-24 15:41:17', 1, 18, 'chocolo desgranado', '2700', 'gr', '600', '3000', 1),
(15, '2025-02-24 15:41:20', 1, 19, 'harina de trigo', '9925', 'gr', '2000', '10000', 1),
(16, '2025-04-10 14:13:43', 1, 20, 'MEZCLA CHOCOLO ', '5000', 'gr', '1000', '5000', 1);

--
-- Volcado de datos para la tabla `inventario_novedades`
--

INSERT INTO `inventario_novedades` (`id`, `fecha`, `usuario`, `inventario_id`, `cantidad_anterior`, `operacion`, `cantidad_modificada`, `cantidad_nueva`, `motivo`, `descripcion`) VALUES
(1, '2022-03-08 12:32:42', 1, 1, 0, 'suma', 2000, 2000, 'primer despacho', 'despacho No 1'),
(2, '2022-03-08 12:32:55', 1, 2, 0, 'suma', 50, 50, 'primer despacho', 'despacho No 1'),
(3, '2022-03-08 12:32:59', 1, 3, 0, 'suma', 1000, 1000, 'primer despacho', 'despacho No 1'),
(4, '2022-03-08 12:36:52', 1, 2, 39, 'resta', 10, 29, 'deterioro', 'Estaba trapeando y se me regó el fabuloso y cayo en el paquete y se mojo'),
(5, '2022-03-08 12:40:16', 1, 2, 29, 'suma', 100, 129, 'despacho', 'despacho No 2'),
(6, '2023-10-06 15:14:12', 1, 4, 0, 'suma', 25, 25, 'primer despacho', 'despacho No 5'),
(7, '2023-10-06 15:14:13', 1, 5, 0, 'suma', 50, 50, 'primer despacho', 'despacho No 5'),
(8, '2023-10-06 15:14:15', 1, 6, 0, 'suma', 1000, 1000, 'primer despacho', 'despacho No 5'),
(9, '2023-10-06 15:16:27', 1, 5, 50, 'resta', 10, 40, 'ajuste', 'Ajuste por inventario inicial '),
(10, '2023-10-06 15:18:15', 1, 5, 40, 'suma', 5, 45, 'despacho', 'despacho No 6'),
(11, '2023-11-22 14:39:19', 1, 7, 0, 'suma', 2000, 2000, 'primer despacho', 'despacho No 7'),
(12, '2023-11-22 14:39:22', 1, 8, 0, 'suma', 10000, 10000, 'primer despacho', 'despacho No 7'),
(13, '2023-11-22 14:39:24', 1, 9, 0, 'suma', 5000, 5000, 'primer despacho', 'despacho No 7'),
(14, '2023-11-22 18:01:55', 7, 9, 4000, 'suma', 2000, 6000, 'despacho', 'despacho No 8'),
(15, '2024-04-05 11:21:01', 1, 10, 0, 'suma', 100, 100, 'primer despacho', 'despacho No 9'),
(16, '2024-04-05 11:21:06', 1, 11, 0, 'suma', 50, 50, 'primer despacho', 'despacho No 9'),
(17, '2024-04-05 11:21:10', 1, 12, 0, 'suma', 3000, 3000, 'primer despacho', 'despacho No 9'),
(18, '2025-02-24 15:41:15', 1, 13, 0, 'suma', 500, 500, 'primer despacho', 'despacho No 10'),
(19, '2025-02-24 15:41:17', 1, 14, 0, 'suma', 3000, 3000, 'primer despacho', 'despacho No 10'),
(20, '2025-02-24 15:41:20', 1, 15, 0, 'suma', 10000, 10000, 'primer despacho', 'despacho No 10'),
(21, '2025-04-10 14:13:43', 1, 16, 0, 'suma', 5000, 5000, 'primera produccion', 'produccion No 3'),
(22, '2025-04-10 14:17:21', 1, 5, 45, 'suma', 5, 50, 'despacho', 'despacho No 11');

--
-- Volcado de datos para la tabla `locales`
--

INSERT INTO `locales` (`id`, `fecha`, `usuario`, `local`, `direccion`, `telefono`, `tipo`, `apertura`, `cierre`, `propina`, `imagen`, `imagen_nombre`) VALUES
(1, '2025-12-15 07:25:19', 1, 'Demo Café', 'Av Hermosa #123', '3003144886', 'punto de venta', '06:00:00', '23:00:00', 0, 'si', '20240201143636'),
(4, '2023-04-10 14:22:19', 1, '3H café ', 'Santa Marta ', '302444444', 'punto de venta', '07:00:00', '19:00:00', 0, 'si', '20230410142219'),
(5, '2022-06-02 13:41:51', 1, 'Tostao Café y Pan', 'Carrera 65f 32a35 ', '5832942', 'punto de venta', '13:30:00', '18:52:00', 10, 'si', '20220602134116'),
(6, '2022-10-20 16:30:02', 1, 'Los perritos de distrito', 'Avenida 100', '3128341984', 'punto de venta', '11:00:00', '21:00:00', 0, 'si', '20221020163002'),
(7, '2023-11-22 14:13:46', 1, 'Migaito Santa Monica ', 'Carrera 90 #40-63', '3012427209', 'punto de venta', '06:00:00', '00:29:00', 0, 'no', '20231122133031'),
(8, '2023-12-29 13:43:29', 1, 'Loretta', 'Carrera 63.41 43', '3197870993', 'punto de venta', '07:00:00', '23:00:00', 0, 'si', '20231229134329'),
(9, '2024-04-05 10:47:45', 1, 'Donde el coste oviedo', 'Cc oviedo', '678687', 'punto de venta', '01:00:00', '01:00:00', 0, 'no', '20240405104745'),
(10, '2026-01-25 10:04:24', 1, 'Pamplona Parrilla', 'Sabaneta – Av. Las Vegas al frente de la Molienda', '310 429 36 40', 'punto de venta', '08:00:00', '23:00:00', 0, 'no', '20260125094632');

--
-- Volcado de datos para la tabla `producciones`
--

INSERT INTO `producciones` (`id`, `fecha`, `fecha_envio`, `fecha_recibe`, `usuario`, `origen`, `destino`, `estado`, `usuario_recibe`) VALUES
(1, '2022-11-22 16:04:02', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 0, 6, 'creado', 0),
(2, '2025-04-10 14:10:54', '2025-04-10 14:12:49', '0000-00-00 00:00:00', 1, 0, 4, 'enviado', 0),
(3, '2025-04-10 14:13:15', '2025-04-10 14:13:28', '2025-04-10 14:13:43', 1, 0, 1, 'recibido', 1),
(4, '2025-04-10 14:20:07', '2025-04-10 14:22:40', '0000-00-00 00:00:00', 1, 0, 1, 'enviado', 0);

--
-- Volcado de datos para la tabla `producciones_componentes`
--

INSERT INTO `producciones_componentes` (`id`, `fecha`, `usuario`, `produccion_id`, `componente_id`, `cantidad`, `estado`) VALUES
(1, '2025-04-10 14:11:40', 1, 2, 20, 5000, 'enviado'),
(2, '2025-04-10 14:13:26', 1, 3, 20, 5000, 'recibido'),
(3, '2025-04-10 14:22:31', 1, 4, 20, 20000, 'enviado');

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `fecha`, `usuario`, `categoria`, `tipo`, `local`, `zona`, `producto`, `precio`, `impuesto_id`, `impuesto_incluido`, `descripcion`, `codigo_barras`, `imagen`, `imagen_nombre`) VALUES
(1, '2022-01-25 15:57:45', 1, 1, 'compuesto', 0, 2, 'Capuchino', 7000, 2, 'si', 'Delicioso café vaporizado con leche deslactosada', '', 'si', '20220125155745'),
(2, '2022-01-25 16:00:01', 1, 1, 'compuesto', 0, 2, 'Latte frio', 5000, 2, 'si', 'Delicioso y refrescante café frio con leche', '', 'si', '20220125160001'),
(3, '2025-11-12 11:46:10', 1, 1, 'compuesto', 0, 2, 'Americano', 4500, 2, 'si', 'Clásico café negro receta americana', '', 'si', '20220125160222'),
(4, '2022-01-25 16:06:40', 1, 2, 'compuesto', 0, 1, 'Croissant', 10000, 2, 'si', 'Crujiente croissant de queso', '', 'si', '20220125160640'),
(5, '2022-01-25 16:07:25', 1, 2, 'compuesto', 0, 1, 'Torta de zanahoria', 12000, 2, 'si', 'Esponjosa porción de torta de zanahoria con la receta de la abuela', '', 'si', '20220125160725'),
(6, '2022-01-25 16:08:10', 1, 2, 'compuesto', 0, 1, 'Palito de queso', 6000, 2, 'si', 'Rico palito de queso mozarella recién horneado', '', 'si', '20220125160810'),
(7, '2022-01-25 16:13:15', 1, 3, 'compuesto', 0, 2, 'Botella de agua', 3000, 2, 'si', 'Botella de agua mineral sin gas', '', 'si', '20220125161136'),
(8, '2022-01-25 16:12:26', 1, 3, 'compuesto', 0, 2, 'Cerveza corona', 10000, 2, 'si', 'Cerveza importada de méxico', '', 'si', '20220125161226'),
(9, '2022-01-25 16:13:00', 1, 3, 'compuesto', 0, 2, 'Coca Cola', 5000, 2, 'si', 'Botella tamaño personal', '', 'si', '20220125161300'),
(10, '2022-03-08 11:47:27', 1, 4, 'simple', 4, 1, 'Palito de queso hojaldrado ', 10000, 2, 'no', 'Rico palito de queso con la receta de la abuela \r\n', '', 'no', '20220308114727'),
(11, '2022-03-08 11:48:22', 1, 4, 'simple', 4, 1, 'Arepa de huevo  ', 12000, 2, 'no', 'Arepa de huevo con la receta típica costeña  \r\n', '', 'no', '20220308114822'),
(12, '2022-03-08 11:49:32', 1, 5, 'compuesto', 4, 2, 'Cucurucho ', 5000, 2, 'no', 'Rico cucurucho de caramelo o fresa ', '', 'no', '20220308114932'),
(13, '2022-03-08 11:50:26', 1, 5, 'simple', 4, 2, 'Copa de helado sin azúcar  ', 6000, 2, 'no', 'Delicioso helado sin azúcar apto para diabéticos  ', '', 'no', '20220308115026'),
(14, '2024-01-19 14:31:41', 1, 1, 'compuesto', 5, 1, 'Cafe colombiano pipe', 5000, 2, 'si', 'Rico cafe de las montanas de colombia', '', 'si', '20240119143141'),
(15, '2022-03-29 14:04:00', 1, 1, 'compuesto', 5, 1, 'Capuccino colombiano pipe', 10000, 2, 'si', 'Rico capuccino de las montanas de colombia', '', 'no', '20220329140400'),
(16, '2022-10-20 16:47:44', 6, 6, 'compuesto', 6, 4, 'Chili dog ', 12000, 4, 'no', '', '', 'no', '20221020164744'),
(17, '2022-10-20 16:48:01', 5, 6, 'compuesto', 6, 4, 'Perro tradicional ', 6000, 1, 'no', '', '', 'no', '20221020164801'),
(18, '2023-11-22 13:49:44', 1, 7, 'compuesto', 7, 1, 'tradicional pequeño', 2400, 1, 'no', 'rico chocolate con la recete clasica de la abuela', '', 'no', '20231122134944'),
(19, '2023-11-22 13:50:26', 1, 8, 'simple', 7, 1, 'multigrano', 2300, 1, 'no', 'pan con granos seleccionados', '', 'no', '20231122135026'),
(20, '2023-12-29 13:54:08', 9, 9, 'compuesto', 8, 1, 'Matoci ', 5000, 6, 'no', 'Exquisita arepa rellena de queso maicitos y tocineta', '', 'no', '20231229135408'),
(21, '2025-11-18 11:38:34', 1, 9, 'compuesto', 8, 1, 'Aborrajada', 6000, 6, 'no', 'Exquisita arepa rellena de queso plátano y bocadillo', '', 'no', '20231229135451'),
(22, '2024-02-19 13:46:03', 1, 4, 'compuesto', 0, 1, 'pizza jyq', 25000, 5, 'si', 'pizza jyq pers', '', 'no', '20240219134603'),
(23, '2024-04-05 11:04:39', 1, 10, 'compuesto', 1, 1, 'Hamburguesa Julio', 15000, 7, 'no', 'Rica hamburguesa hecha al carbón con el toque de Julio', '', 'si', '20240405110439'),
(24, '2025-02-24 15:27:59', 1, 2, 'compuesto', 0, 1, 'Arepa de chocolo', 5500, 6, 'no', 'arepa de la receta clasica de mamá', '', 'no', '20250224152759'),
(25, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Punta de Anca (350g)', 34000, 2, 'si', 'Corte de la cadera madurado, tipo mariposa con capa externa de grasa.', '', 'no', ''),
(26, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Churrasco (350g)', 34000, 2, 'si', 'Corte de lomo alto sin hueso con una delgada capa de grasa.', '', 'no', ''),
(27, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Costillas de Cerdo (600g)', 34000, 2, 'si', 'Corte estilo St. Louis, ahumadas y a la brasa en salsa BBQ.', '', 'no', ''),
(28, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Filete de Pollo (350g)', 32000, 2, 'si', 'Pechuga marinada en finas hierbas, al BBQ o al natural.', '', 'no', ''),
(29, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Solomo de Res (250g)', 26000, 2, 'si', 'Corte fino de suave textura marinado con la receta de la casa.', '', 'no', ''),
(30, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Cerdo Premium (250g)', 24000, 2, 'si', 'Corte magro de cerdo seleccionado y asado en su punto.', '', 'no', ''),
(31, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Chuzos de Pollo (250g)', 22000, 2, 'si', 'Chuzos de contramuslo con tocineta dorados al BBQ o al natural.', '', 'no', ''),
(32, '2026-01-24 10:05:00', 1, 11, 'compuesto', 10, 1, 'Chorizo de Cerdo', 20000, 2, 'si', 'Carne de cerdo selecta, famosos chorizos ahumados artesanales.', '', 'no', ''),
(33, '2026-01-24 10:10:00', 1, 12, 'compuesto', 10, 1, 'Picada Especial x 4', 100000, 2, 'si', 'Costilla BBQ, Res, Cerdo, Chorizo, 5 arepas, papa y ensalada.', '', 'no', ''),
(34, '2026-01-24 10:10:00', 1, 12, 'compuesto', 10, 1, 'Picada Sencilla x 3', 70000, 2, 'si', 'Res, Cerdo, Chorizo, 4 arepas tela, papas al vapor y ensalada.', '', 'no', ''),
(35, '2026-01-24 10:15:00', 1, 13, 'simple', 10, 2, 'Gaseosas / Jugos Hit', 5000, 2, 'si', 'Postobón, Coca-Cola, Hit o H2O.', '', 'no', ''),
(36, '2026-01-24 10:15:00', 1, 13, 'simple', 10, 2, 'Cerveza', 10000, 2, 'si', 'Cerveza nacional o importada según disponibilidad.', '', 'no', ''),
(37, '2026-01-28 19:21:55', 1, 11, 'compuesto', 10, 6, 'Carne de conejo', 22000, 6, 'no', 'Deliciosa carne de conejo hecha con papas al vapor y mucho amor y la receta la casa', '', 'no', '20260128192155');

--
-- Volcado de datos para la tabla `productos_categorias`
--

INSERT INTO `productos_categorias` (`id`, `fecha`, `usuario`, `categoria`, `tipo`, `adicion`, `estado`, `imagen`, `imagen_nombre`) VALUES
(1, '2022-01-25 15:42:20', 1, 'Cafés', 'productos', 'no', 'activo', 'si', '20220125154220'),
(2, '2022-01-25 15:42:49', 1, 'Reposteria', 'productos', 'no', 'activo', 'si', '20220125154249'),
(3, '2022-01-25 15:43:13', 1, 'Bebidas', 'productos', 'no', 'activo', 'si', '20220125154313'),
(4, '2022-03-08 11:43:04', 1, 'Horneados ', 'productos', 'no', 'activo', 'no', '20220308114304'),
(5, '2022-03-08 11:43:22', 1, 'Helados', 'productos', 'no', 'activo', 'no', '20220308114322'),
(6, '2022-10-20 16:45:23', 5, 'Perritos', 'productos', 'no', 'activo', 'no', '20221020164523'),
(7, '2023-11-22 13:42:47', 1, 'chocolates', 'productos', 'no', 'activo', 'no', '20231122134247'),
(8, '2023-11-22 13:42:59', 1, 'panes', 'productos', 'no', 'activo', 'no', '20231122134259'),
(9, '2023-12-29 13:52:30', 9, 'Arepas', 'productos', 'no', 'activo', 'no', '20231229135230'),
(10, '2024-04-05 10:59:35', 1, 'Hamburguesas', 'productos', 'no', 'activo', 'no', '20240405105935'),
(11, '2026-01-24 10:00:00', 1, 'Especiales (Carnes)', 'productos', 'no', 'activo', 'no', ''),
(12, '2026-01-24 10:00:00', 1, 'Para Compartir', 'productos', 'no', 'activo', 'no', ''),
(13, '2026-01-24 10:00:00', 1, 'Bebidas Parrilla', 'productos', 'no', 'activo', 'no', ''),
(14, '2026-01-28 19:14:48', 1, 'adiciones', 'servicios', 'no', 'activo', 'no', '20260128191448');

--
-- Volcado de datos para la tabla `productos_variaciones`
--

INSERT INTO `productos_variaciones` (`id`, `fecha`, `usuario`, `producto_id`, `producto`, `variacion`, `grupo`) VALUES
(1, '2024-02-19 13:49:54', 1, 22, 'pizza jyq', 'pizza 1', 'hornn'),
(2, '2024-02-19 13:50:06', 1, 22, 'pizza jyq', 'pizza 2', 'hornne');

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `fecha`, `usuario`, `proveedor`, `correo`, `telefono`, `imagen`, `imagen_nombre`) VALUES
(1, '2022-02-22 18:39:50', 1, 'Colanta', 'ventas@colanta.com', '3134078693', 'no', '20220222183950'),
(2, '2022-03-08 12:16:32', 3, 'Helados del Magdalena ', 'ventas@heladosdemagdalena.com', '33353985', 'no', '20220308121632'),
(3, '2022-03-08 12:17:03', 3, 'Panadería dona rica ', 'panaderia@hotmail.com', '5582308', 'no', '20220308121703'),
(4, '2023-11-22 14:31:05', 1, 'pecositas', 'ventas@pecositas.com', '23336655', 'no', '20231122143105'),
(5, '2024-04-05 11:09:16', 1, 'Fricar', 'ventas@fricar.com', '4565656', 'no', '20240405110916');

--
-- Volcado de datos para la tabla `tipos_pagos`
--

INSERT INTO `tipos_pagos` (`id`, `fecha`, `usuario`, `tipo_pago`, `tipo`) VALUES
(1, '2022-01-25 15:15:36', 1, 'Efectivo', 'efectivo'),
(2, '2022-01-25 15:16:55', 1, 'VISA', 'tarjeta'),
(3, '2022-01-25 15:16:44', 1, 'MASTERCARD', 'tarjeta'),
(4, '2022-03-08 11:35:10', 1, 'Transferencia 01069040545', 'transferencia'),
(5, '2022-10-20 16:37:31', 1, 'Qr', 'transferencia'),
(6, '2024-04-05 10:53:23', 1, 'Daviplata', 'transferencia');

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `fecha`, `usuario`, `ubicacion`, `ubicada`, `estado`, `tipo`, `local`) VALUES
(1, '2022-01-25 15:14:10', 1, 'Mesa 01', 'Piso 1', 'libre', 'mesa', 1),
(2, '2022-01-25 15:14:16', 1, 'Mesa 02', 'Piso 1', 'libre', 'mesa', 1),
(3, '2022-01-25 15:14:19', 1, 'Mesa 03', 'Piso 1', 'libre', 'mesa', 1),
(4, '2022-01-25 15:14:21', 1, 'Mesa 04', 'Piso 1', 'libre', 'mesa', 1),
(5, '2022-03-08 11:30:42', 1, 'Mesa 01', 'Lobby', 'libre', 'mesa', 4),
(6, '2022-03-08 11:30:53', 1, 'Mesa 02', 'Lobby', 'libre', 'mesa', 4),
(7, '2022-03-29 13:56:23', 1, 'Mesa 1', 'Piso 2 ', 'libre', 'mesa', 5),
(8, '2022-03-29 13:56:35', 1, 'Mesa 2', 'Piso 2 ', 'libre', 'mesa', 5),
(9, '2022-10-20 16:34:25', 6, 'Caja 2', '1 piso', 'libre', 'caja', 6),
(10, '2022-10-20 16:34:27', 5, 'Caja 1', 'Primer piso', 'ocupado', 'caja', 6),
(11, '2024-02-01 14:43:10', 1, 'Barra 01', 'Mostrador', 'libre', 'barra', 1),
(12, '2023-11-22 13:33:54', 1, 'Caja 01', 'Adentro ', 'ocupado', 'caja', 7),
(13, '2023-11-22 13:34:02', 1, 'Caja 02', 'Adentro ', 'libre', 'caja', 7),
(14, '2023-12-29 13:46:08', 9, 'Caja 1', 'Mostrador', 'libre', 'caja', 8),
(15, '2023-12-29 13:46:16', 9, 'Caja 2', 'Mostrador', 'libre', 'caja', 8),
(16, '2024-02-01 14:43:30', 1, 'Barra 02', 'Mostrador', 'libre', 'barra', 1),
(17, '2024-04-05 10:50:24', 1, 'Caja 15', 'Barra', 'libre', 'caja', 1),
(18, '2026-01-25 10:02:14', 1, 'Vendedor 1', 'Calle', 'libre', 'mesa', 10),
(19, '2026-01-25 10:01:59', 1, 'Vendedor 2', 'Calle', 'libre', 'mesa', 10),
(20, '2026-01-25 10:02:02', 1, 'Vendedor 3', 'Calle', 'libre', 'mesa', 10),
(21, '2026-01-28 18:59:12', 1, 'Mesón 1', 'Zona sur', 'libre', 'mesa', 10),
(22, '2026-01-28 18:59:21', 1, 'Marron1', 'Zona sur', 'ocupado', 'mesa', 10),
(23, '2026-01-28 18:59:26', 1, 'Mesón 2', 'Zona sur', 'libre', 'mesa', 10),
(24, '2026-01-28 18:59:36', 1, 'Mesón 3', 'Zona sur', 'libre', 'mesa', 10),
(25, '2026-01-28 18:59:41', 1, 'Marron2', 'Zona sur', 'libre', 'mesa', 10),
(26, '2026-01-28 18:59:47', 1, 'Mesón 4', 'Zona sur', 'libre', 'mesa', 10),
(27, '2026-01-28 19:00:03', 1, 'Marron3', 'Zona sur', 'ocupado', 'mesa', 10);

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `fecha`, `usuario`, `correo`, `contrasena`, `nombres`, `apellidos`, `tipo`, `local`, `imagen`, `imagen_nombre`) VALUES
(1, '2026-01-25 10:01:10', 1, 'demo@demo.com', 'demo', 'Pepito', 'Perez', 'socio', 10, 'si', '20220125143241'),
(2, '2022-02-22 16:51:11', 1, 'pepe@gmail.com', '12345', 'Pepe', 'Pérez', 'administrador', 1, 'no', '20220222165111'),
(4, '2023-04-11 10:50:39', 1, 'felipemarin.mol@gmail.com', '123446', 'Felipe ', 'Marin', 'socio', 5, 'si', '20230411105039'),
(5, '2022-10-20 16:31:50', 1, 'mauriciosanchezq@hotmail.com', '1234', 'Mauricio', 'Sachez', 'socio', 6, 'no', '20221020163150'),
(6, '2022-10-20 16:31:58', 1, 'ninatapia1@hotmail.com', '1234', 'Nina', 'Tapia', 'socio', 6, 'no', '20221020163158'),
(7, '2023-11-22 13:31:24', 1, 'estefa46@hotmail.com', '1234', 'Estefania ', 'Urrego ', 'socio', 7, 'no', '20231122133124'),
(8, '2023-12-02 18:02:00', 1, 'juanita2475@hotmail.com', '1234', 'Juanita', 'Sierra', 'socio', 1, 'no', '20231202180200'),
(9, '2023-12-29 13:44:35', 1, 'lorettaarepas@gmail.com', 'Loreta04', 'Lorena', 'Alzate', 'socio', 8, 'no', '20231229134435'),
(10, '2026-01-25 10:00:45', 1, 'dannyws@gmail.com', '1234', 'Danny ', 'Estrada ', 'socio', 10, 'no', '20241103161117'),
(11, '2025-06-20 11:33:28', 1, 'gestor7.emprende@parque-e.co', '1234', 'Helen', 'Gutierrez', 'socio', 1, 'no', '20250620113328'),
(12, '2026-01-28 18:56:38', 1, 'luispamplona75@gmail.com', '0123', 'Fernando ', 'PAMPLONA ', 'socio', 10, 'no', '20260128185638'),
(13, '2026-01-28 18:56:42', 1, 'echeverry302010@gmail.com', '1313', 'Cata', 'Echeverry ', 'socio', 10, 'no', '20260128185642');

--
-- Volcado de datos para la tabla `usuarios_permisos`
--

INSERT INTO `usuarios_permisos` (`id`, `fecha`, `usuario`, `id_usuario`, `ajustes`, `ventas`, `zonas_entregas`, `base`, `cierre`, `compras`, `producciones`, `inventario`, `gastos`, `clientes`, `reportes`) VALUES
(1, '2022-01-25 14:24:09', 1, 1, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(2, '2022-03-08 11:29:18', 1, 3, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(3, '2022-10-20 16:33:07', 1, 6, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(4, '2022-10-20 16:33:24', 1, 5, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(5, '2023-04-10 14:06:50', 1, 4, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(6, '2023-11-22 13:52:57', 1, 7, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(7, '2023-12-29 13:45:14', 1, 9, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si'),
(8, '2026-01-25 10:00:36', 1, 10, 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si', 'si');

--
-- Volcado de datos para la tabla `ventas_datos`
--

INSERT INTO `ventas_datos` (`id`, `fecha`, `fecha_cierre`, `usuario_id`, `local_id`, `ubicacion_id`, `ubicacion`, `cliente_id`, `tipo_pago_id`, `tipo_pago`, `estado`, `total_bruto`, `descuento_id`, `descuento_porcentaje`, `descuento_valor`, `propina`, `total_neto`, `observaciones`, `eliminar_motivo`, `pago`, `fecha_pago`) VALUES
(1, '2023-11-26 08:39:30', '2023-12-01 09:54:08', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 23529, 0, '0', 0, 0, 28000, '', 'no aplica', 'contado', '2023-11-26 08:39:30'),
(2, '2023-11-29 17:14:29', '2023-12-01 09:47:58', 1, 1, 2, 'Mesa 02', 0, 1, 'Efectivo', 'liquidado', 15126, 0, '0', 0, 0, 18000, '', 'no aplica', 'contado', '2023-11-29 17:14:29'),
(3, '2023-12-01 10:02:30', '2023-12-01 10:02:48', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2023-12-01 10:02:30'),
(4, '2023-12-01 10:07:53', '2023-12-01 11:29:32', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 47899, 0, '0', 0, 0, 57000, '', 'no aplica', 'contado', '2023-12-01 10:07:53'),
(5, '2023-12-01 11:31:06', '2023-12-15 11:12:40', 1, 1, 4, 'Mesa 04', 0, 1, 'efectivo', 'liquidado', 15126, 0, '0', 0, 0, 18000, '', 'no aplica', 'contado', '2023-12-01 11:31:06'),
(6, '2023-12-02 16:34:16', '2023-12-02 17:54:21', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'liquidado', 82353, 0, '0', 0, 0, 98000, '', 'no aplica', 'credito', '2023-12-15 00:00:00'),
(7, '2023-12-02 18:02:10', '2023-12-02 18:03:17', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'eliminado', 0, 1, '10', 0, 0, 0, '', 'el cliente se fue sin pagar', 'contado', '2023-12-02 18:02:10'),
(8, '2023-12-16 07:44:20', '2023-12-16 07:44:28', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2023-12-16 07:44:20'),
(9, '2023-12-29 13:55:35', '2023-12-29 14:00:15', 9, 8, 14, 'Caja 1', 0, 4, 'Transferencia 01069040545', 'eliminado', 0, 1, '10', 0, 0, 0, '', 'el cliente canceló el pedido', 'contado', '2023-12-29 13:55:35'),
(10, '2023-12-29 13:57:25', '2023-12-29 14:01:16', 9, 8, 15, 'Caja 2', 0, 1, 'efectivo', 'liquidado', 10000, 0, '0', 0, 0, 10000, '', 'no aplica', 'contado', '2023-12-29 13:57:25'),
(11, '2023-12-29 14:02:37', '2023-12-29 14:03:00', 9, 8, 14, 'Caja 1', 0, 4, 'Transferencia 01069040545', 'liquidado', 95000, 0, '0', 0, 0, 95000, '', 'no aplica', 'contado', '2023-12-29 14:02:37'),
(12, '2024-01-18 14:04:24', '2024-01-18 14:05:52', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'liquidado', 23109, 0, '0', 0, 0, 27500, '', 'no aplica', 'contado', '2024-01-18 14:04:24'),
(13, '2024-01-18 14:21:14', '2024-02-01 14:37:28', 1, 1, 11, 'Venta personalizada', 0, 1, 'efectivo', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2024-01-18 14:21:14'),
(14, '2024-01-18 14:21:18', '2024-01-19 11:29:27', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 21429, 0, '0', 0, 0, 25500, '', 'no aplica', 'contado', '2024-01-18 14:21:18'),
(15, '2024-02-01 14:50:34', '2024-03-12 18:51:24', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2024-02-01 14:50:34'),
(16, '2024-02-09 16:04:28', '2024-02-19 13:44:13', 1, 1, 16, 'Barra 02', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'ubicación repetida', 'contado', '2024-02-09 16:04:28'),
(17, '2024-02-19 13:17:51', '2024-03-12 18:51:41', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2024-02-19 13:17:51'),
(18, '2024-02-19 13:23:45', '2024-02-19 13:24:31', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'el cliente canceló el pedido', 'contado', '2024-02-19 13:23:45'),
(19, '2024-02-19 13:29:58', '2024-03-12 18:51:49', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2024-02-19 13:29:58'),
(20, '2024-02-19 14:09:46', '2024-03-12 18:51:32', 1, 1, 16, 'Barra 02', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2024-02-19 14:09:46'),
(21, '2024-03-03 16:42:31', '2024-03-03 16:50:53', 1, 1, 2, 'Mesa 02', 0, 2, 'VISA', 'liquidado', 25210, 0, '0', 0, 0, 30000, '', 'no aplica', 'contado', '2024-03-03 16:42:31'),
(22, '2024-04-05 11:21:50', '2024-04-05 11:22:38', 1, 1, 2, 'Mesa 02', 0, 6, 'Daviplata', 'liquidado', 15000, 3, '25', 5438, 0, 16313, '', 'no aplica', 'contado', '2024-04-05 11:21:50'),
(23, '2024-04-23 10:52:00', '2024-04-30 15:33:18', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 6303, 0, '0', 0, 0, 7500, '', 'no aplica', 'contado', '2024-04-23 10:52:00'),
(24, '2024-04-23 10:56:28', '2024-04-30 15:33:28', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'liquidado', 46678, 3, '25', 13250, 0, 39750, '', 'no aplica', 'contado', '2024-04-23 10:56:28'),
(25, '2024-04-23 19:04:21', '2024-04-23 19:06:01', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 12605, 0, '0', 0, 0, 15000, '', 'no aplica', 'contado', '2024-04-23 19:04:21'),
(26, '2024-04-23 19:07:36', '2024-04-30 15:33:40', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2024-04-23 19:07:36'),
(27, '2024-05-08 19:45:39', '2024-05-08 19:49:12', 1, 1, 17, 'Caja 15', 0, 2, 'VISA', 'liquidado', 37563, 1, '10', 5626, 10, 50631, '', 'no aplica', 'contado', '2024-05-08 19:45:39'),
(28, '2024-05-20 13:56:34', '2024-05-20 14:02:26', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 12605, 0, '0', 0, 0, 15000, '', 'no aplica', 'contado', '2024-05-20 13:56:34'),
(29, '2024-05-20 14:01:13', '2024-10-20 16:05:41', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'liquidado', 15126, 0, '0', 0, 0, 18000, '', 'no aplica', 'contado', '2024-05-20 14:01:13'),
(30, '2024-08-31 19:25:51', '2024-08-31 19:32:06', 1, 1, 4, 'Mesa 04', 0, 5, 'Qr', 'liquidado', 32773, 99, '1.2820512820513', 542, 10, 41735, '', 'no aplica', 'contado', '2024-08-31 19:25:51'),
(31, '2024-08-31 19:43:39', '2024-08-31 19:44:03', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 41050, 0, '0', 0, 0, 52750, '', 'no aplica', 'contado', '2024-08-31 19:43:39'),
(32, '2024-08-31 19:44:17', '2024-08-31 19:44:34', 1, 1, 4, 'Mesa 04', 0, 1, 'efectivo', 'liquidado', 8403, 0, '0', 0, 0, 10000, '', 'no aplica', 'contado', '2024-08-31 19:44:17'),
(33, '2024-08-31 19:46:16', '2024-10-20 16:05:51', 1, 1, 17, 'Caja 15', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2024-08-31 19:46:16'),
(34, '2024-11-03 15:48:35', '2024-11-03 16:09:27', 8, 1, 2, 'Mesa 02', 0, 5, 'Qr', 'eliminado', 0, 3, '25', 0, 10, 0, '', 'el cliente canceló el pedido', 'contado', '2024-11-03 00:00:00'),
(35, '2024-11-03 16:11:26', '2024-11-03 16:11:37', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'órden de administración', 'contado', '2024-11-03 16:11:26'),
(36, '2024-11-03 16:12:39', '2024-11-03 16:18:34', 1, 1, 4, 'Mesa 04', 0, 1, 'efectivo', 'liquidado', 79832, 1, '10', 10298, 10, 92685, '', 'no aplica', 'contado', '2024-11-03 16:12:39'),
(37, '2024-11-03 16:24:34', '2024-11-03 16:24:43', 1, 1, 1, 'Mesa 01', 0, 3, 'MASTERCARD', 'liquidado', 15000, 0, '0', 0, 0, 21750, '', 'no aplica', 'contado', '2024-11-03 16:24:34'),
(38, '2024-11-03 16:24:46', '2024-11-03 16:25:03', 1, 1, 11, 'Barra 01', 0, 6, 'Daviplata', 'liquidado', 23148, 0, '0', 0, 0, 25000, '', 'no aplica', 'contado', '2024-11-03 16:24:46'),
(39, '2025-01-17 10:34:06', '2025-02-26 16:07:34', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 2521, 0, '0', 0, 0, 3000, '', 'no aplica', 'contado', '2025-01-17 10:34:06'),
(40, '2025-02-24 15:28:19', '2025-02-24 15:42:17', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 55000, 0, '0', 0, 0, 55000, '', 'no aplica', 'contado', '2025-02-24 15:28:19'),
(41, '2025-03-31 13:01:29', '2025-03-31 13:52:55', 1, 1, 11, 'Barra 01', 0, 5, 'Qr', 'liquidado', 23403, 0, '0', 0, 0, 31750, '', 'no aplica', 'contado', '2025-03-31 13:01:29'),
(42, '2025-03-31 13:01:43', '2025-04-04 12:59:53', 1, 1, 3, 'Mesa 03', 0, 4, 'Transferencia 01069040545', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2025-03-31 13:01:43'),
(43, '2025-03-31 13:33:12', '2025-03-31 13:50:30', 1, 1, 16, 'Barra 02', 0, 1, 'efectivo', 'liquidado', 42424, 0, '0', 0, 0, 51250, '', 'no aplica', 'contado', '2025-03-31 13:33:12'),
(44, '2025-03-31 13:36:14', '2025-03-31 13:45:11', 1, 1, 2, 'Mesa 02', 0, 4, 'Transferencia 01069040545', 'liquidado', 48358, 0, '0', 0, 0, 55000, '', 'no aplica', 'contado', '2025-03-31 13:36:14'),
(45, '2025-03-31 13:51:40', '2025-04-04 12:59:45', 1, 1, 16, 'Barra 02', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2025-03-31 13:51:40'),
(46, '2025-04-10 13:49:39', '2025-06-20 10:40:42', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 10084, 0, '0', 0, 0, 12000, '', 'no aplica', 'credito', '2025-04-30 00:00:00'),
(47, '2025-05-08 15:38:49', '2025-05-15 10:30:10', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 9664, 0, '0', 0, 0, 11500, '', 'no aplica', 'credito', '2025-05-22 00:00:00'),
(48, '2025-05-30 10:47:42', '2025-05-30 10:50:17', 1, 1, 4, 'Mesa 04', 0, 1, 'efectivo', 'liquidado', 28992, 0, '0', 0, 0, 34500, '', 'no aplica', 'contado', '2025-05-30 00:00:00'),
(49, '2025-06-20 10:34:34', '2025-06-20 10:38:39', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'liquidado', 64286, 0, '0', 0, 0, 76500, '', 'no aplica', 'contado', '2025-06-20 10:34:34'),
(50, '2025-06-20 11:34:40', '2025-06-20 11:35:57', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'el cliente canceló el pedido', 'contado', '2025-06-20 11:34:40'),
(51, '2025-06-20 11:39:25', '2025-07-14 09:01:05', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'liquidado', 27350, 0, '0', 0, 0, 30000, '', 'no aplica', 'contado', '2025-06-20 11:39:25'),
(52, '2025-06-20 11:44:46', '2025-07-14 09:01:30', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2025-06-20 11:44:46'),
(53, '2025-07-11 20:04:12', '2025-07-11 20:09:34', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 13866, 0, '0', 0, 0, 16500, '', 'no aplica', 'contado', '2025-07-11 20:04:12'),
(54, '2025-07-30 16:58:03', '2025-07-30 17:03:03', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 34073, 0, '0', 0, 0, 38000, '', 'no aplica', 'contado', '2025-07-30 16:58:03'),
(55, '2025-08-11 09:19:20', '2025-08-11 09:22:26', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'liquidado', 10084, 0, '0', 0, 0, 12000, '', 'no aplica', 'contado', '2025-08-11 09:19:20'),
(56, '2025-08-11 10:39:31', '2025-08-27 13:31:56', 1, 1, 1, 'Mesa 01', 0, 2, 'VISA', 'liquidado', 70960, 2, '50', 41625, 0, 41625, '', 'no aplica', 'contado', '2025-08-11 10:39:31'),
(57, '2025-08-11 10:39:49', '2025-08-27 13:14:02', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'liquidado', 30000, 0, '0', 0, 0, 43500, '', 'no aplica', 'contado', '2025-08-11 10:39:49'),
(58, '2025-08-27 13:46:50', '2025-08-27 13:46:58', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2025-08-27 13:46:50'),
(59, '2025-08-27 15:06:06', '2025-08-27 15:23:02', 1, 1, 11, 'Barra 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 6723, 0, '0', 0, 0, 8000, '', 'no aplica', 'contado', '2025-08-27 15:06:06'),
(60, '2025-08-27 16:25:23', '2025-08-27 22:39:21', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 8403, 0, '0', 0, 0, 10000, '', 'no aplica', 'contado', '2025-08-27 16:25:23'),
(61, '2025-08-27 22:43:04', '2025-08-27 23:04:55', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 20168, 0, '0', 0, 0, 24000, '', 'no aplica', 'contado', '2025-08-27 22:43:04'),
(62, '2025-08-28 11:10:16', '2025-08-28 11:11:16', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 15126, 0, '0', 0, 0, 18000, '', 'no aplica', 'contado', '2025-08-28 11:10:16'),
(63, '2025-08-28 11:14:20', '2025-08-28 11:14:33', 1, 1, 2, 'Mesa 02', 0, 4, 'Transferencia 01069040545', 'liquidado', 23148, 0, '0', 0, 0, 25000, '', 'no aplica', 'contado', '2025-08-28 11:14:20'),
(64, '2025-08-28 11:33:02', '2025-08-28 11:33:30', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2025-08-28 11:33:02'),
(65, '2025-08-28 14:57:39', '2025-08-28 20:26:03', 1, 1, 11, 'Barra 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 9282, 0, '0', 0, 0, 10000, '', 'no aplica', 'contado', '2025-08-28 14:57:39'),
(66, '2025-08-28 16:37:31', '2025-08-28 20:23:36', 1, 1, 17, 'Caja 15', 0, 1, 'efectivo', 'liquidado', 17647, 0, '0', 0, 0, 21000, '', 'no aplica', 'contado', '2025-08-28 16:37:31'),
(67, '2025-10-04 08:40:45', '2025-10-04 08:43:07', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 2521, 0, '0', 0, 0, 3000, '', 'no aplica', 'contado', '2025-10-04 08:40:45'),
(68, '2025-10-04 09:09:13', '2025-10-04 09:09:24', 1, 1, 11, 'Barra 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2025-10-04 09:09:13'),
(69, '2025-10-04 09:15:06', '2025-10-04 09:15:17', 1, 1, 16, 'Barra 02', 0, 1, 'efectivo', 'liquidado', 19328, 0, '0', 0, 0, 23000, '', 'no aplica', 'contado', '2025-10-04 09:15:06'),
(70, '2025-10-04 09:15:51', '2025-10-16 18:53:14', 1, 1, 3, 'Mesa 03', 0, 1, 'efectivo', 'eliminado', 0, 0, '0', 0, 0, 0, '', 'capacitación', 'contado', '2025-10-04 09:15:51'),
(71, '2025-10-04 09:16:04', '2025-10-04 16:08:31', 1, 1, 11, 'Barra 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2025-10-04 09:16:04'),
(72, '2025-12-03 20:08:52', '2025-12-15 07:17:52', 1, 1, 11, 'Barra 01', 0, 1, 'efectivo', 'liquidado', 45000, 0, '0', 0, 0, 65250, '', 'no aplica', 'contado', '2025-12-03 20:08:52'),
(73, '2025-12-05 10:16:00', '2025-12-15 07:18:29', 1, 1, 1, 'Mesa 01', 0, 2, 'VISA', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2025-12-05 10:16:00'),
(74, '2025-12-05 10:16:48', '2025-12-15 07:18:14', 1, 1, 2, 'Mesa 02', 0, 4, 'Transferencia 01069040545', 'liquidado', 13903, 0, '0', 0, 0, 15500, '', 'no aplica', 'contado', '2025-12-05 10:16:48'),
(75, '2025-12-15 07:18:58', '2025-12-15 07:19:07', 1, 1, 1, 'Mesa 01', 0, 1, 'efectivo', 'liquidado', 15126, 0, '0', 0, 0, 18000, '', 'no aplica', 'contado', '2025-12-15 07:18:58'),
(76, '2025-12-15 07:22:58', '2025-12-15 07:23:50', 1, 1, 1, 'Mesa 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 2521, 0, '0', 0, 0, 3000, '', 'no aplica', 'contado', '2025-12-15 07:22:58'),
(77, '2025-12-15 07:26:57', '2025-12-15 07:27:22', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'liquidado', 2521, 0, '0', 0, 0, 3000, '', 'no aplica', 'contado', '2025-12-15 07:26:57'),
(78, '2025-12-15 07:41:28', '2025-12-15 07:41:38', 1, 1, 4, 'Mesa 04', 0, 5, 'Qr', 'liquidado', 13866, 0, '0', 0, 0, 16500, '', 'no aplica', 'contado', '2025-12-15 07:41:28'),
(79, '2025-12-26 19:31:49', '2026-01-02 08:57:06', 1, 1, 11, 'Barra 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 27731, 0, '0', 0, 0, 33000, '', 'no aplica', 'contado', '2025-12-26 19:31:49'),
(80, '2026-01-02 08:57:19', '2026-01-02 08:57:28', 1, 1, 2, 'Mesa 02', 0, 1, 'efectivo', 'liquidado', 2521, 0, '0', 0, 0, 3000, '', 'no aplica', 'contado', '2026-01-02 08:57:19'),
(81, '2026-01-09 14:33:24', '2026-01-09 14:33:40', 1, 1, 11, 'Barra 01', 0, 4, 'Transferencia 01069040545', 'liquidado', 10924, 0, '0', 0, 0, 13000, '', 'no aplica', 'contado', '2026-01-09 14:33:24'),
(82, '2026-01-25 10:02:21', '2026-01-25 10:02:55', 1, 10, 18, 'Vendedor 1', 0, 1, 'efectivo', 'liquidado', 36975, 0, '0', 0, 0, 44000, '', 'no aplica', 'contado', '2026-01-25 10:02:21'),
(83, '2026-01-25 10:10:38', '2026-01-25 10:12:12', 1, 10, 18, 'Vendedor 1', 0, 1, 'efectivo', 'liquidado', 57143, 0, '0', 0, 0, 68000, '', 'no aplica', 'contado', '2026-01-25 10:10:38'),
(84, '2026-01-25 10:14:10', '2026-01-25 10:14:34', 1, 10, 20, 'Vendedor 3', 0, 4, 'Transferencia 01069040545', 'liquidado', 32773, 0, '0', 0, 0, 39000, '', 'no aplica', 'contado', '2026-01-25 10:14:10'),
(85, '2026-01-26 18:14:22', '2026-01-26 18:27:02', 1, 10, 18, 'Vendedor 1', 0, 5, 'Qr', 'liquidado', 196639, 0, '0', 0, 5, 243832, '', 'no aplica', 'contado', '2026-01-26 18:14:22'),
(86, '2026-01-26 19:00:08', '2026-01-26 19:05:12', 1, 10, 18, 'Vendedor 1', 0, 5, 'Qr', 'liquidado', 18487, 0, '0', 0, 0, 22000, '', 'no aplica', 'contado', '2026-01-26 00:00:00'),
(87, '2026-01-28 18:33:13', '2026-01-28 19:49:19', 1, 10, 18, 'Vendedor 1', 0, 5, 'Qr', 'liquidado', 8403, 0, '0', 0, 4000, 14000, '', 'no aplica', 'contado', '2026-01-28 18:33:13'),
(88, '2026-01-28 19:23:20', '0000-00-00 00:00:00', 1, 10, 22, 'Marron1', 0, 1, 'efectivo', 'ocupado', 0, 0, '0', 0, 0, 0, '', '', 'contado', '2026-01-28 19:23:20'),
(89, '2026-01-28 19:23:33', '2026-01-28 19:37:50', 1, 10, 21, 'Mesón 1', 0, 1, 'efectivo', 'eliminado', 0, 4, '100', 0, 0, 0, '', 'el cliente se fue sin pagar', 'contado', '2026-01-28 19:23:33'),
(90, '2026-01-28 19:27:31', '2026-01-28 19:43:35', 1, 10, 23, 'Mesón 2', 0, 5, 'Qr', 'liquidado', 35294, 0, '0', 0, 2000, 44000, '', 'no aplica', 'contado', '2026-01-28 19:27:31'),
(91, '2026-01-28 19:27:58', '2026-01-28 19:47:30', 1, 10, 26, 'Mesón 4', 0, 5, 'Qr', 'liquidado', 8403, 0, '0', 0, 5000, 15000, '', 'no aplica', 'contado', '2026-01-28 19:27:58'),
(92, '2026-01-28 19:28:52', '2026-01-28 19:40:26', 1, 10, 27, 'Marron3', 0, 1, 'efectivo', 'liquidado', 30403, 0, '0', 0, 0, 32000, 'Terminó azul la carne', 'no aplica', 'contado', '2026-01-28 19:28:52'),
(93, '2026-01-28 19:29:01', '2026-01-28 19:52:35', 1, 10, 20, 'Vendedor 3', 0, 1, 'efectivo', 'liquidado', 50420, 0, '0', 0, 10000, 70000, '', 'no aplica', 'contado', '2026-01-28 19:29:01'),
(94, '2026-01-28 19:55:41', '0000-00-00 00:00:00', 1, 10, 27, 'Marron3', 0, 1, 'efectivo', 'ocupado', 0, 0, '0', 0, 0, 0, '', '', 'contado', '2026-01-28 19:55:41'),
(95, '2026-01-28 20:57:41', '2026-01-28 21:03:19', 1, 10, 26, 'Mesón 4', 0, 5, 'Qr', 'liquidado', 86555, 0, '0', 0, 5000, 108000, 'Terminó medio', 'no aplica', 'contado', '2026-01-28 20:57:41');

--
-- Volcado de datos para la tabla `ventas_productos`
--

INSERT INTO `ventas_productos` (`id`, `fecha`, `usuario`, `venta_id`, `ubicacion_id`, `ubicacion`, `categoria_id`, `categoria`, `local`, `zona`, `producto_id`, `producto`, `precio_final`, `porcentaje_impuesto`, `estado`, `estado_zona_entregas`) VALUES
(1, '2023-11-26 08:42:32', 1, 1, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', ''),
(2, '2023-11-26 08:42:32', 1, 1, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', ''),
(3, '2023-11-26 08:42:32', 1, 1, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 5, 'Torta de zanahoria', 12000, 19, 'liquidado', ''),
(4, '2023-11-29 17:14:47', 1, 2, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(5, '2023-11-29 17:14:49', 1, 2, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(6, '2023-11-29 17:14:51', 1, 2, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(7, '2023-12-01 10:02:33', 1, 3, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(8, '2023-12-01 10:02:34', 1, 3, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(9, '2023-12-01 10:11:19', 1, 4, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(10, '2023-12-01 10:11:20', 1, 4, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(11, '2023-12-01 10:27:44', 1, 4, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(12, '2023-12-01 10:27:50', 1, 4, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(13, '2023-12-01 11:12:50', 1, 4, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'entregado'),
(14, '2023-12-01 11:12:50', 1, 4, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'entregado'),
(15, '2023-12-01 11:28:36', 1, 4, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4000, 19, 'liquidado', 'entregado'),
(16, '2023-12-01 11:28:36', 1, 4, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'entregado'),
(17, '2023-12-01 11:28:36', 1, 4, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'entregado'),
(18, '2023-12-01 11:28:36', 1, 4, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'entregado'),
(19, '2023-12-01 11:31:15', 1, 5, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'entregado'),
(20, '2023-12-01 11:31:15', 1, 5, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'entregado'),
(21, '2023-12-01 11:31:15', 1, 5, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'entregado'),
(22, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'entregado'),
(23, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'entregado'),
(24, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'entregado'),
(25, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'entregado'),
(26, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'entregado'),
(27, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(28, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(29, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(30, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(31, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(32, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(33, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(34, '2023-12-02 16:36:14', 1, 6, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'entregado'),
(37, '2023-12-16 07:44:23', 1, 8, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(38, '2023-12-16 07:44:23', 1, 8, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(43, '2023-12-29 13:57:36', 9, 10, 15, 'Caja 2', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'confirmado'),
(44, '2023-12-29 13:57:36', 9, 10, 15, 'Caja 2', 9, 'Arepas', 8, 1, 20, 'Matoci ', 5000, 0, 'liquidado', 'confirmado'),
(45, '2023-12-29 14:02:39', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(46, '2023-12-29 14:02:41', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(47, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(48, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(49, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(50, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(51, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(52, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(53, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(54, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(55, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(56, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(57, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(58, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(59, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(60, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(61, '2023-12-29 14:02:46', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 21, 'Aborrajada', 5000, 0, 'liquidado', 'pedido'),
(62, '2023-12-29 14:02:49', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 20, 'Matoci ', 5000, 0, 'liquidado', 'pedido'),
(63, '2023-12-29 14:02:49', 9, 11, 14, 'Caja 1', 9, 'Arepas', 8, 1, 20, 'Matoci ', 5000, 0, 'liquidado', 'pedido'),
(64, '2024-01-18 14:04:38', 1, 12, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(65, '2024-01-18 14:04:39', 1, 12, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(66, '2024-01-18 14:04:45', 1, 12, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(67, '2024-01-18 14:04:46', 1, 12, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'pedido'),
(68, '2024-01-18 14:21:32', 1, 14, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(69, '2024-01-18 14:21:37', 1, 14, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(70, '2024-01-19 11:29:02', 1, 14, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(71, '2024-01-19 11:29:03', 1, 14, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(72, '2024-01-19 11:29:08', 1, 14, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(73, '2024-02-01 14:37:15', 1, 13, 11, 'Venta personalizada', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(74, '2024-02-01 14:37:18', 1, 13, 11, 'Venta personalizada', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(90, '2024-03-03 16:47:28', 1, 21, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'entregado'),
(91, '2024-03-03 16:47:28', 1, 21, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'entregado'),
(92, '2024-03-03 16:47:28', 1, 21, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'entregado'),
(93, '2024-03-03 16:47:28', 1, 21, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 5, 'Torta de zanahoria', 12000, 19, 'liquidado', 'entregado'),
(94, '2024-04-05 11:22:09', 1, 22, 2, 'Mesa 02', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(95, '2024-04-23 10:56:33', 1, 24, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(96, '2024-04-23 10:56:35', 1, 24, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'pedido'),
(97, '2024-04-23 10:56:43', 1, 23, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(98, '2024-04-23 10:56:43', 1, 24, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(99, '2024-04-23 10:56:44', 1, 24, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'pedido'),
(100, '2024-04-23 10:56:51', 1, 23, 11, 'Barra 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(101, '2024-04-23 10:56:57', 1, 24, 2, 'Mesa 02', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', 'pedido'),
(102, '2024-04-23 19:04:32', 1, 25, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(103, '2024-04-23 19:04:33', 1, 25, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(104, '2024-04-30 15:33:35', 1, 26, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(105, '2024-04-30 15:33:36', 1, 26, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(106, '2024-05-08 19:47:37', 1, 27, 17, 'Caja 15', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'entregado'),
(107, '2024-05-08 19:47:37', 1, 27, 17, 'Caja 15', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'entregado'),
(108, '2024-05-08 19:47:37', 1, 27, 17, 'Caja 15', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'entregado'),
(109, '2024-05-08 19:47:37', 1, 27, 17, 'Caja 15', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'entregado'),
(111, '2024-05-20 14:00:46', 1, 28, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'confirmado'),
(112, '2024-05-20 14:00:46', 1, 28, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'confirmado'),
(113, '2024-05-20 14:00:46', 1, 28, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'confirmado'),
(114, '2024-05-20 14:01:22', 1, 29, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'confirmado'),
(115, '2024-05-20 14:01:22', 1, 29, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'confirmado'),
(116, '2024-05-20 14:01:22', 1, 29, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'entregado'),
(117, '2024-08-31 19:26:43', 1, 30, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(118, '2024-08-31 19:26:52', 1, 30, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(119, '2024-08-31 19:26:52', 1, 30, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(120, '2024-08-31 19:27:29', 1, 30, 4, 'Mesa 04', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(123, '2024-08-31 19:27:36', 1, 30, 4, 'Mesa 04', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'pedido'),
(124, '2024-08-31 19:43:42', 1, 31, 1, 'Mesa 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(125, '2024-08-31 19:43:46', 1, 31, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(126, '2024-08-31 19:43:47', 1, 31, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 6, 'Palito de queso', 6000, 19, 'liquidado', 'pedido'),
(127, '2024-08-31 19:43:51', 1, 31, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(128, '2024-08-31 19:43:52', 1, 31, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(129, '2024-08-31 19:44:20', 1, 32, 4, 'Mesa 04', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(136, '2024-11-03 16:12:43', 1, 36, 4, 'Mesa 04', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(137, '2024-11-03 16:12:44', 1, 36, 4, 'Mesa 04', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', 'pedido'),
(138, '2024-11-03 16:12:46', 1, 36, 4, 'Mesa 04', 2, 'Reposteria', 1, 1, 5, 'Torta de zanahoria', 12000, 19, 'liquidado', 'pedido'),
(139, '2024-11-03 16:12:51', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(140, '2024-11-03 16:12:54', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(141, '2024-11-03 16:12:54', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(142, '2024-11-03 16:12:54', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(143, '2024-11-03 16:12:54', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(144, '2024-11-03 16:12:54', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(145, '2024-11-03 16:13:13', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(146, '2024-11-03 16:13:13', 1, 36, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(147, '2024-11-03 16:24:36', 1, 37, 1, 'Mesa 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(148, '2024-11-03 16:24:51', 1, 38, 11, 'Barra 01', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', 'pedido'),
(149, '2025-01-17 10:34:12', 1, 39, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(151, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(152, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(153, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(154, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(155, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(156, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(157, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(158, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(159, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(160, '2025-02-24 15:41:58', 1, 40, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(165, '2025-03-31 13:34:39', 1, 43, 16, 'Barra 02', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(166, '2025-03-31 13:34:39', 1, 43, 16, 'Barra 02', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(167, '2025-03-31 13:34:39', 1, 43, 16, 'Barra 02', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', 'pedido'),
(168, '2025-03-31 13:35:26', 1, 43, 16, 'Barra 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(169, '2025-03-31 13:35:32', 1, 43, 16, 'Barra 02', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(170, '2025-03-31 13:36:18', 1, 44, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(171, '2025-03-31 13:36:32', 1, 44, 2, 'Mesa 02', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', 'pedido'),
(172, '2025-03-31 13:37:16', 1, 44, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(174, '2025-03-31 13:37:33', 1, 44, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(175, '2025-03-31 13:37:33', 1, 44, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(176, '2025-03-31 13:37:33', 1, 44, 2, 'Mesa 02', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(177, '2025-03-31 13:39:30', 1, 43, 16, 'Barra 02', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(178, '2025-03-31 13:51:52', 1, 41, 11, 'Barra 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(179, '2025-03-31 13:52:00', 1, 41, 11, 'Barra 01', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'pedido'),
(180, '2025-03-31 13:52:11', 1, 41, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(181, '2025-06-20 10:39:23', 1, 46, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'entregado'),
(182, '2025-05-08 15:38:51', 1, 47, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(183, '2025-05-08 15:38:52', 1, 47, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(184, '2025-05-30 10:48:02', 1, 48, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(185, '2025-05-30 10:48:08', 1, 48, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(186, '2025-05-30 10:48:08', 1, 48, 4, 'Mesa 04', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(187, '2025-05-30 10:48:21', 1, 48, 4, 'Mesa 04', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(188, '2025-05-30 10:48:22', 1, 48, 4, 'Mesa 04', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(189, '2025-06-20 10:35:19', 1, 49, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(190, '2025-06-20 10:35:21', 1, 49, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'pedido'),
(191, '2025-06-20 10:35:28', 1, 49, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(192, '2025-06-20 10:35:30', 1, 49, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'pedido'),
(193, '2025-06-20 10:35:34', 1, 49, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', 'pedido'),
(194, '2025-06-20 10:35:38', 1, 49, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(195, '2025-06-20 10:35:50', 1, 49, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'pedido'),
(196, '2025-06-20 10:35:52', 1, 49, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', 'pedido'),
(197, '2025-06-20 10:35:54', 1, 49, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'pedido'),
(198, '2025-06-20 10:36:01', 1, 49, 3, 'Mesa 03', 2, 'Reposteria', 1, 1, 5, 'Torta de zanahoria', 12000, 19, 'liquidado', 'pedido'),
(199, '2025-06-20 10:36:05', 1, 49, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'pedido'),
(200, '2025-06-20 10:36:05', 1, 49, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'pedido'),
(202, '2025-06-20 10:39:23', 1, 46, 11, 'Barra 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'entregado'),
(203, '2025-06-20 10:39:23', 1, 46, 11, 'Barra 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'entregado'),
(205, '2025-06-20 11:40:57', 1, 51, 3, 'Mesa 03', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', 'entregado'),
(206, '2025-06-20 11:40:57', 1, 51, 3, 'Mesa 03', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', 'entregado'),
(207, '2025-07-11 20:07:21', 1, 53, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', 'confirmado'),
(208, '2025-07-11 20:07:21', 1, 53, 1, 'Mesa 01', 2, 'Reposteria', 1, 1, 5, 'Torta de zanahoria', 12000, 19, 'liquidado', 'confirmado'),
(209, '2025-07-30 17:02:08', 1, 54, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', 'confirmado'),
(210, '2025-07-30 17:02:08', 1, 54, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', 'confirmado'),
(212, '2025-07-30 17:02:08', 1, 54, 11, 'Barra 01', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', 'confirmado'),
(213, '2025-08-11 09:19:33', 1, 55, 3, 'Mesa 03', 2, 'Reposteria', 1, 1, 5, 'Torta de zanahoria', 12000, 19, 'liquidado', 'pedido'),
(214, '2025-08-11 10:39:55', 1, 57, 2, 'Mesa 02', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(215, '2025-08-11 10:39:55', 1, 57, 2, 'Mesa 02', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', 'pedido'),
(217, '2025-08-27 13:23:41', 1, 56, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', ''),
(218, '2025-08-27 13:23:51', 1, 56, 1, 'Mesa 01', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', ''),
(219, '2025-08-27 13:28:36', 1, 56, 1, 'Mesa 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', ''),
(220, '2025-08-27 13:31:50', 1, 56, 1, 'Mesa 01', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', ''),
(221, '2025-08-27 13:31:52', 1, 56, 1, 'Mesa 01', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', ''),
(222, '2025-08-27 13:46:52', 1, 58, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(223, '2025-08-27 13:46:53', 1, 58, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(226, '2025-08-27 15:12:27', 1, 59, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(227, '2025-08-27 15:22:47', 1, 59, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(233, '2025-08-27 22:31:38', 1, 60, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(234, '2025-08-27 22:31:38', 1, 60, 11, 'Barra 01', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', ''),
(236, '2025-08-27 22:53:54', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(237, '2025-08-27 22:53:54', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(238, '2025-08-27 23:00:49', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(239, '2025-08-27 23:00:51', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(240, '2025-08-27 23:01:58', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(241, '2025-08-27 23:02:00', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(242, '2025-08-27 23:02:01', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(243, '2025-08-27 23:02:09', 1, 61, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(244, '2025-08-28 11:10:38', 1, 62, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(245, '2025-08-28 11:10:41', 1, 62, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(246, '2025-08-28 11:10:44', 1, 62, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(247, '2025-08-28 11:14:22', 1, 63, 2, 'Mesa 02', 4, 'Horneados ', 1, 1, 22, 'pizza jyq', 25000, 8, 'liquidado', ''),
(248, '2025-08-28 11:33:10', 1, 64, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(249, '2025-08-28 11:33:13', 1, 64, 3, 'Mesa 03', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(259, '2025-08-28 16:37:18', 1, 65, 11, '', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', ''),
(260, '2025-08-28 16:37:21', 1, 65, 11, '', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', ''),
(261, '2025-08-28 16:37:33', 1, 66, 17, 'Caja 15', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(262, '2025-08-28 16:37:34', 1, 66, 17, 'Caja 15', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(263, '2025-08-28 16:37:40', 1, 66, 17, 'Caja 15', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(264, '2025-08-28 19:00:28', 1, 66, 17, 'Caja 15', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(265, '2025-10-04 08:40:47', 1, 67, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(266, '2025-10-04 09:09:15', 1, 68, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(267, '2025-10-04 09:09:16', 1, 68, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(268, '2025-10-04 09:15:09', 1, 69, 16, 'Barra 02', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(269, '2025-10-04 09:15:10', 1, 69, 16, 'Barra 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(270, '2025-10-04 09:15:10', 1, 69, 16, 'Barra 02', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(275, '2025-10-04 16:08:13', 1, 71, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(276, '2025-10-04 16:08:15', 1, 71, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(277, '2025-12-03 20:13:24', 1, 72, 11, 'Barra 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', ''),
(279, '2025-12-05 10:16:17', 1, 73, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(280, '2025-12-05 10:16:17', 1, 73, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(281, '2025-12-05 10:16:57', 1, 74, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 24, 'Arepa de chocolo', 5500, 0, 'liquidado', ''),
(282, '2025-12-05 10:16:57', 1, 74, 2, 'Mesa 02', 2, 'Reposteria', 1, 1, 4, 'Croissant', 10000, 19, 'liquidado', ''),
(283, '2025-12-15 07:17:34', 1, 72, 11, 'Barra 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', ''),
(284, '2025-12-15 07:17:36', 1, 72, 11, 'Barra 01', 10, 'Hamburguesas', 1, 1, 23, 'Hamburguesa Julio', 21750, 45, 'liquidado', ''),
(285, '2025-12-15 07:19:00', 1, 75, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(286, '2025-12-15 07:19:01', 1, 75, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(287, '2025-12-15 07:19:01', 1, 75, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(288, '2025-12-15 07:23:05', 1, 76, 1, 'Mesa 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(290, '2025-12-15 07:27:16', 1, 77, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(291, '2025-12-15 07:41:30', 1, 78, 4, 'Mesa 04', 1, 'Cafés', 1, 2, 3, 'Americano', 4500, 19, 'liquidado', ''),
(292, '2025-12-15 07:41:31', 1, 78, 4, 'Mesa 04', 1, 'Cafés', 1, 2, 1, 'Capuchino', 7000, 19, 'liquidado', ''),
(293, '2025-12-15 07:41:32', 1, 78, 4, 'Mesa 04', 1, 'Cafés', 1, 2, 2, 'Latte frio', 5000, 19, 'liquidado', ''),
(296, '2025-12-26 19:32:15', 1, 79, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(297, '2025-12-26 19:34:48', 1, 79, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(298, '2025-12-26 19:34:49', 1, 79, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(299, '2025-12-26 19:34:51', 1, 79, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(300, '2026-01-02 08:57:21', 1, 80, 2, 'Mesa 02', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(301, '2026-01-09 14:33:27', 1, 81, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', ''),
(302, '2026-01-09 14:33:28', 1, 81, 11, 'Barra 01', 3, 'Bebidas', 1, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(303, '2026-01-25 10:02:44', 1, 82, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 30, 'Cerdo Premium (250g)', 24000, 19, 'liquidado', ''),
(304, '2026-01-25 10:02:46', 1, 82, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 32, 'Chorizo de Cerdo', 20000, 19, 'liquidado', ''),
(306, '2026-01-25 10:11:19', 1, 83, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 30, 'Cerdo Premium (250g)', 24000, 19, 'liquidado', ''),
(307, '2026-01-25 10:11:21', 1, 83, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 26, 'Churrasco (350g)', 34000, 19, 'liquidado', ''),
(308, '2026-01-25 10:11:48', 1, 83, 18, 'Vendedor 1', 13, 'Bebidas Parrilla', 10, 2, 36, 'Cerveza', 10000, 19, 'liquidado', ''),
(309, '2026-01-25 10:14:13', 1, 84, 20, 'Vendedor 3', 13, 'Bebidas Parrilla', 10, 2, 35, 'Gaseosas / Jugos Hit', 5000, 19, 'liquidado', ''),
(310, '2026-01-25 10:14:18', 1, 84, 20, 'Vendedor 3', 11, 'Especiales (Carnes)', 10, 1, 26, 'Churrasco (350g)', 34000, 19, 'liquidado', ''),
(311, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 3, 'Bebidas', 10, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(312, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 3, 'Bebidas', 10, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(313, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 3, 'Bebidas', 10, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(314, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 3, 'Bebidas', 10, 2, 9, 'Coca Cola', 5000, 19, 'liquidado', ''),
(315, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 32, 'Chorizo de Cerdo', 20000, 19, 'liquidado', ''),
(317, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 28, 'Filete de Pollo (350g)', 32000, 19, 'liquidado', ''),
(318, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 27, 'Costillas de Cerdo (600g)', 34000, 19, 'liquidado', ''),
(319, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 27, 'Costillas de Cerdo (600g)', 34000, 19, 'liquidado', ''),
(321, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 27, 'Costillas de Cerdo (600g)', 34000, 19, 'liquidado', ''),
(322, '2026-01-26 18:17:57', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 26, 'Churrasco (350g)', 34000, 19, 'liquidado', ''),
(323, '2026-01-26 18:20:54', 1, 85, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 29, 'Solomo de Res (250g)', 26000, 19, 'liquidado', ''),
(324, '2026-01-26 19:01:23', 1, 86, 18, 'Vendedor 1', 11, 'Especiales (Carnes)', 10, 1, 31, 'Chuzos de Pollo (250g)', 22000, 19, 'liquidado', ''),
(325, '2026-01-28 18:33:16', 1, 87, 18, 'Vendedor 1', 13, 'Bebidas Parrilla', 10, 2, 36, 'Cerveza', 10000, 19, 'liquidado', ''),
(331, '2026-01-28 19:28:00', 1, 90, 23, 'Mesón 2', 11, 'Especiales (Carnes)', 10, 1, 31, 'Chuzos de Pollo (250g)', 22000, 19, 'liquidado', ''),
(332, '2026-01-28 19:28:00', 1, 90, 23, 'Mesón 2', 11, 'Especiales (Carnes)', 10, 1, 32, 'Chorizo de Cerdo', 20000, 19, 'liquidado', ''),
(333, '2026-01-28 19:28:27', 1, 91, 26, 'Mesón 4', 3, 'Bebidas', 10, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(334, '2026-01-28 19:29:35', 1, 92, 27, 'Marron3', 13, 'Bebidas Parrilla', 10, 2, 36, 'Cerveza', 10000, 19, 'liquidado', ''),
(335, '2026-01-28 19:29:36', 1, 93, 20, 'Vendedor 3', 11, 'Especiales (Carnes)', 10, 1, 25, 'Punta de Anca (350g)', 34000, 19, 'liquidado', ''),
(336, '2026-01-28 19:29:36', 1, 93, 20, 'Vendedor 3', 11, 'Especiales (Carnes)', 10, 1, 29, 'Solomo de Res (250g)', 26000, 19, 'liquidado', ''),
(337, '2026-01-28 19:29:35', 1, 92, 27, 'Marron3', 11, 'Especiales (Carnes)', 10, 6, 37, 'Carne de conejo', 22000, 0, 'liquidado', ''),
(338, '2026-01-28 19:55:44', 1, 94, 27, 'Marron3', 11, 'Especiales (Carnes)', 10, 1, 30, 'Cerdo Premium (250g)', 24000, 19, 'pedido', ''),
(339, '2026-01-28 19:55:45', 1, 94, 27, 'Marron3', 11, 'Especiales (Carnes)', 10, 1, 26, 'Churrasco (350g)', 34000, 19, 'pedido', ''),
(340, '2026-01-28 19:55:52', 1, 94, 27, 'Marron3', 13, 'Bebidas Parrilla', 10, 2, 36, 'Cerveza', 10000, 19, 'pedido', ''),
(341, '2026-01-28 19:55:55', 1, 94, 27, 'Marron3', 13, 'Bebidas Parrilla', 10, 2, 35, 'Gaseosas / Jugos Hit', 5000, 19, 'pedido', ''),
(342, '2026-01-28 20:58:53', 1, 95, 26, 'Mesón 4', 11, 'Especiales (Carnes)', 10, 1, 30, 'Cerdo Premium (250g)', 24000, 19, 'liquidado', ''),
(343, '2026-01-28 20:58:53', 1, 95, 26, 'Mesón 4', 11, 'Especiales (Carnes)', 10, 1, 26, 'Churrasco (350g)', 34000, 19, 'liquidado', ''),
(344, '2026-01-28 20:58:53', 1, 95, 26, 'Mesón 4', 11, 'Especiales (Carnes)', 10, 1, 28, 'Filete de Pollo (350g)', 32000, 19, 'liquidado', ''),
(345, '2026-01-28 21:00:51', 1, 95, 26, 'Mesón 4', 3, 'Bebidas', 10, 2, 8, 'Cerveza corona', 10000, 19, 'liquidado', ''),
(346, '2026-01-28 21:00:51', 1, 95, 26, 'Mesón 4', 3, 'Bebidas', 10, 2, 7, 'Botella de agua', 3000, 19, 'liquidado', '');

--
-- Volcado de datos para la tabla `zonas_entregas`
--

INSERT INTO `zonas_entregas` (`id`, `fecha`, `usuario`, `zona`) VALUES
(1, '2022-01-25 15:14:45', 1, 'Cocina'),
(2, '2022-01-25 15:14:49', 1, 'Barra'),
(5, '2023-12-29 13:46:46', 9, 'Barra alimentos'),
(6, '2026-01-28 19:04:14', 1, 'Parrilla');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

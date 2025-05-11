-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-05-2025 a las 00:11:46
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbventago`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alerta`
--

CREATE TABLE `alerta` (
  `id` int(11) NOT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `unidad` enum('Unidad','Kilogramo','Libra') DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL CHECK (`valor` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `nit_negocio`, `nombre`, `descripcion`) VALUES
(2, 1, 'Frutas', ''),
(4, 1, 'Verduras', 'njsddsd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL CHECK (`total` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `id_proveedor`, `nit_negocio`, `fecha_hora`, `total`) VALUES
(2, 2, 1, '2025-05-03 16:01:48', 50000.00),
(3, 2, 1, '2025-05-03 16:02:18', 40000.00),
(4, 3, 1, '2025-05-03 16:04:20', 10000.00),
(6, 3, 1, '2025-05-10 11:40:28', 10000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra_producto`
--

CREATE TABLE `compra_producto` (
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL CHECK (`cantidad` >= 0),
  `precio_compra` decimal(10,2) DEFAULT NULL CHECK (`precio_compra` >= 0),
  `precio_venta` decimal(10,2) DEFAULT NULL CHECK (`precio_venta` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra_producto`
--

INSERT INTO `compra_producto` (`id_compra`, `id_producto`, `cantidad`, `precio_compra`, `precio_venta`) VALUES
(2, 1, 20.00, 2000.00, 3000.00),
(3, 1, 20.00, 2000.00, 3000.00),
(4, 1, 10.00, 1000.00, 2000.00),
(6, 3, 10.00, 1000.00, 2000.00);

--
-- Disparadores `compra_producto`
--
DELIMITER $$
CREATE TRIGGER `after_compra_producto_insert` AFTER INSERT ON `compra_producto` FOR EACH ROW BEGIN
    UPDATE producto
    SET 
        precio_actual = NEW.precio_venta,
        stock_actual = stock_actual + NEW.cantidad
    WHERE id = NEW.id_producto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `negocio`
--

CREATE TABLE `negocio` (
  `nit` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `negocio`
--

INSERT INTO `negocio` (`nit`, `nombre`, `contacto`, `direccion`) VALUES
(1, 'colmena aristides', '32344442', 'mz 39 lt 15'),
(5, 'TisterLiga', '3823377', 'MC BLQ 5 APTO 101'),
(6, 'Zeus', '54234223', 'MC BLQ 20 APTO 34'),
(7, 'atomo', '65343223', 'MC B BLOQ 32 APTO 101'),
(8, 'Fruver Keiven', '842433', 'MC BLQ 20 APTO 34'),
(9, 'kjadfdfs', '845235454', 'nsacdd'),
(10, 'jfkasdfs', '5235342', 'njasdfsas'),
(11, 'jkjakffsdfj', '772345234', 'ma,sfasfsdfsd'),
(12, 'kjlfasfsdf', '535234524', 'kafasfsbakmx'),
(13, 'nasnckascd', '73452345', 'na,naasfads'),
(14, 'ajflasdjfl', '31341237', 'na,snf,sfas'),
(15, 'jahksdfdj', '2345354', 'jafasdfsj'),
(16, 'jasflasjfkds', '472384584', 'asdfnajsnasd'),
(17, 'nkjngskdjfg', '73452345', 'nsafnskfjsad'),
(18, 'asdfasfd', '5234534', '4523n'),
(19, 'adsfsdfj', '4242423', 'nfsd3333');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `precio_actual` decimal(10,2) DEFAULT NULL CHECK (`precio_actual` >= 0),
  `unidad` enum('Unidad','Kilogramo','Libra') DEFAULT NULL,
  `stock_actual` decimal(10,2) DEFAULT NULL CHECK (`stock_actual` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nit_negocio`, `id_categoria`, `nombre`, `precio_actual`, `unidad`, `stock_actual`) VALUES
(1, 1, 2, 'Manzana', 3000.00, 'Unidad', 20.00),
(3, 1, 2, 'Mango', 2000.00, 'Unidad', 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id` int(11) NOT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id`, `nit_negocio`, `nombre`, `contacto`, `correo`) VALUES
(2, 1, 'Keiver', '12344333', 'k@correo'),
(3, 1, 'Alex', '744244433', 'alex@correo'),
(5, 1, 'Juan', '542347', 'ck@correo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `contrasena` varchar(100) DEFAULT NULL,
  `rol` enum('Administrador','Vendedor') DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nit_negocio`, `nombre`, `contrasena`, `rol`, `correo`) VALUES
(1, 1, 'admin', '1234', 'Administrador', 'admin@correo'),
(2, 1, 'Juan Mata', '4321', 'Vendedor', 'juan@correo'),
(4, 1, 'Jose Lopez', '1234', 'Vendedor', 'jlopez@correo'),
(6, 5, 'Liga', '1111', 'Administrador', 'liga@correo'),
(7, 6, 'odin', '4321', 'Administrador', 'odin@correo'),
(8, 7, 'atom', '3241', 'Administrador', 'atom@correo'),
(9, 8, 'keiven', '1234', 'Administrador', 'keiven@correo'),
(10, 9, 'hector', '1234', 'Administrador', 'hector@correo'),
(11, 10, 'labran', '1111', 'Administrador', 'labra@correo'),
(12, 11, 'labro', '1111', 'Administrador', 'la@correo'),
(13, 12, 'luis', '1111', 'Administrador', 'luis@correo'),
(14, 13, 'jose', '1111', 'Administrador', 'jose@correo'),
(15, 14, 'pedro', '1111', 'Administrador', 'pedro@correo'),
(16, 15, 'juan', '1111', 'Administrador', 'juan@correo'),
(17, 16, 'lucas', '1111', 'Administrador', 'lucas@correo'),
(18, 17, 'andres', '1111', 'Administrador', 'andres@correo'),
(19, 18, 'lol', '1111', 'Administrador', 'c@correo'),
(20, 19, 'lola', '1111', 'Administrador', 'l@correo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `nit_negocio` int(11) DEFAULT NULL,
  `id_vendedor` int(11) DEFAULT NULL,
  `cedula_cliente` varchar(50) DEFAULT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL CHECK (`total` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE `venta_producto` (
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL CHECK (`cantidad` >= 0),
  `precio` decimal(10,2) DEFAULT NULL CHECK (`precio` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `venta_producto`
--
DELIMITER $$
CREATE TRIGGER `after_venta_producto_insert` AFTER INSERT ON `venta_producto` FOR EACH ROW BEGIN
   UPDATE producto
    SET stock_actual = stock_actual - NEW.cantidad
    WHERE id = NEW.id_producto;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alerta`
--
ALTER TABLE `alerta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nit_negocio` (`nit_negocio`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nit_negocio` (`nit_negocio`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rut_proveedor` (`id_proveedor`),
  ADD KEY `nit_negocio` (`nit_negocio`);

--
-- Indices de la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
  ADD PRIMARY KEY (`id_compra`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `negocio`
--
ALTER TABLE `negocio`
  ADD PRIMARY KEY (`nit`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nit_negocio` (`nit_negocio`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nit_negocio` (`nit_negocio`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nit_negocio` (`nit_negocio`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nit_negocio` (`nit_negocio`),
  ADD KEY `id_vendedor` (`id_vendedor`);

--
-- Indices de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD PRIMARY KEY (`id_venta`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alerta`
--
ALTER TABLE `alerta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `negocio`
--
ALTER TABLE `negocio`
  MODIFY `nit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alerta`
--
ALTER TABLE `alerta`
  ADD CONSTRAINT `alerta_ibfk_1` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`);

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id`),
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`);

--
-- Filtros para la tabla `compra_producto`
--
ALTER TABLE `compra_producto`
  ADD CONSTRAINT `compra_producto_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id`),
  ADD CONSTRAINT `compra_producto_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`nit_negocio`) REFERENCES `negocio` (`nit`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_vendedor`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id`),
  ADD CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

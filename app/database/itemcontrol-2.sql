-- phpMyAdmin SQL Dump
-- version 5.2.1
-- Servidor: localhost

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Base de datos: `itemcontrol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
    `id` int(11) NOT NULL,
    `nombre` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `categorias` (`id`, `nombre`)
VALUES (4, 'Aseo'),
    (2, 'Bebidas'),
    (6, 'Dulces'),
    (3, 'Enlatados'),
    (5, 'Higiene'),
    (1, 'Lácteos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_solicitudes`
--

CREATE TABLE `inventario_solicitudes` (
    `id` int(11) NOT NULL,
    `nombre` varchar(100) NOT NULL,
    `descripcion` text DEFAULT NULL,
    `fecha_caducidad` date DEFAULT NULL,
    `categoria` varchar(50) DEFAULT NULL,
    `stock` int(11) DEFAULT 0,
    `precio` decimal(10, 2) DEFAULT NULL,
    `creado_por` varchar(50) DEFAULT NULL,
    `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
    `id` int(11) NOT NULL,
    `nombre` varchar(100) NOT NULL,
    `descripcion` text DEFAULT NULL,
    `fecha_caducidad` date DEFAULT NULL,
    `categoria` varchar(50) DEFAULT NULL,
    `stock` int(11) DEFAULT 0,
    `precio` decimal(10, 2) DEFAULT NULL,
    `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `productos` (
        `id`,
        `nombre`,
        `descripcion`,
        `fecha_caducidad`,
        `categoria`,
        `stock`,
        `precio`,
        `creado_en`
    )
VALUES (
        7,
        'PIlas',
        'latas',
        '2025-05-04',
        'Enlatados',
        123,
        450.40,
        '2025-06-04 23:34:46'
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
-- ¡AQUÍ ESTÁN LOS CAMBIOS IMPORTANTES!
--

CREATE TABLE `usuarios` (
    `id` int(11) NOT NULL,
    `username` varchar(50) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `rol` enum('gerente', 'abarrotero') NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = activo, 0 = inactivo',
    `codigo_recuperacion` varchar(6) DEFAULT NULL,
    `expiracion_codigo` datetime DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
-- He agregado correos falsos para que no falle la inserción
--

INSERT INTO
    `usuarios` (
        `id`,
        `username`,
        `email`,
        `password`,
        `rol`,
        `status`
    )
VALUES (
        9,
        'admin',
        'admin@tienda.com',
        '$2y$10$/ndGjcWyFAKsc5MUkcvgwenYuBGC/jMt6yR2kiKdo2GGRdcR88TYi',
        'gerente',
        1
    ),
    (
        10,
        'vendedor',
        'vendedor@tienda.com',
        '$2y$10$lXSZg/S.5R.MKtBBrDvyzulW14pnuo37J6nOslNAK5pYg5/zVvBXa',
        'abarrotero',
        1
    ),
    (
        14,
        'brau',
        'brau@tienda.com',
        '$2y$10$nxmO5eiRayl.q1mnD1fpt.D/AYlAGPw88G.vvVrF6yNGKeY4p7lrW',
        'abarrotero',
        1
    ),
    (
        15,
        'Rogelio ESTUCHE',
        'rogelio1@tienda.com',
        '$2y$10$2UYgXjA85chmE/lGm2hg8.yFAcR/9hT2qehLzv5cl30/Lb6iufLC.',
        'gerente',
        0
    ),
    (
        16,
        'rogelio',
        'rogelio2@tienda.com',
        '$2y$10$bxq.uglB4g9.WTWLs/63luEDSdBWeQwKZXAdtWZoCviVYQxSRBHFy',
        'abarrotero',
        0
    );

--
-- Índices para tablas volcadas
--

ALTER TABLE `categorias`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `nombre` (`nombre`);

ALTER TABLE `inventario_solicitudes` ADD PRIMARY KEY (`id`);

ALTER TABLE `productos` ADD PRIMARY KEY (`id`);

ALTER TABLE `usuarios`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`username`),
ADD UNIQUE KEY `email` (`email`);
-- Nuevo índice único para email

--
-- AUTO_INCREMENT de las tablas volcadas
--

ALTER TABLE `categorias`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

ALTER TABLE `inventario_solicitudes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 8;

ALTER TABLE `productos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 8;

ALTER TABLE `usuarios`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 17;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;
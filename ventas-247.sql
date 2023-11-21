CREATE TABLE `categoria`  (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `categoria` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_categoria`)
);

CREATE TABLE `detalle_ingreso`  (
  `id_detalle_ingreso` int NOT NULL AUTO_INCREMENT,
  `id_ingreso` int NULL,
  `id_producto` int NULL,
  `cantidad` int NULL,
  `precio_compra` decimal(11, 2) NULL,
  `precio_venta` decimal(11, 2) NULL,
  PRIMARY KEY (`id_detalle_ingreso`)
);

CREATE TABLE `detalle_venta`  (
  `id_detalle_venta` int NOT NULL AUTO_INCREMENT,
  `id_venta` int NULL,
  `id_producto` int NULL,
  `cantidad` int NULL,
  `precio` decimal(11, 2) NULL,
  `descuento` decimal(11, 2) NULL,
  PRIMARY KEY (`id_detalle_venta`)
);

CREATE TABLE `ingreso`  (
  `id_ingreso` int NOT NULL AUTO_INCREMENT,
  `id_proveedor` int NULL,
  `tipo_comprobante` varchar(20) NULL,
  `nun_comprobante` varchar(10) NULL,
  `fecha_hora` datetime NULL,
  `impuesto` decimal(10, 2) NULL,
  `estado` varchar(255) NULL,
  PRIMARY KEY (`id_ingreso`)
);

CREATE TABLE `persona`  (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `tipo_persona` varchar(20) NULL,
  `nombre` varchar(100) NULL,
  `tipo_documento` varchar(20) NULL,
  `num_documento` varchar(15) NULL,
  `direccion` varchar(70) NULL,
  `telefono` varbinary(15) NULL,
  `email` varchar(50) NULL,
  PRIMARY KEY (`id_persona`)
);

CREATE TABLE `productos`  (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `id_categoria` int NOT NULL,
  `codigo` varchar(50) NULL,
  `nombre` varchar(100) NULL,
  `stock` int NULL,
  `descripcion` varchar(512) NULL,
  `imagen` varchar(50) NULL,
  `estado` varchar(20) NULL,
  PRIMARY KEY (`id_producto`)
);

CREATE TABLE `venta`  (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NULL,
  `tipo_comprobante` varchar(20) NULL,
  `num_comprobante` varchar(10) NULL,
  `fecha_hora` datetime NULL,
  `impuesto` decimal(4, 2) NULL,
  `total_venta` decimal(11, 2) NULL,
  `estado` varchar(20) NULL,
  PRIMARY KEY (`id_venta`)
);

ALTER TABLE `productos` ADD CONSTRAINT `fk_productos_productos_1` FOREIGN KEY (`id_categoria`) REFERENCES `productos` (`id_categoria`);


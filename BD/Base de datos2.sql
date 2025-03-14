create database ACC;
use ACC;

-- drop database ACC;
-- drop table Usuario;

/*
USUARIO
CLIENTE
COMPRADOR
PROVEEDOR
PRODUCCION
VENDEDOR
DISTRIBUIDOR
ADMINISTRADOR
*/

-- COMPRA PRODUCCION DISTRIBUCION
CREATE TABLE roles (
id_rol INT NOT NULL PRIMARY KEY,
roles VARCHAR(20) NOT NULL
);

CREATE TABLE usuario (
id_usuario INT NOT NULL PRIMARY KEY,
nombre_usuario VARCHAR(50) NOT NULL,
correo TEXT NOT NULL,
contraseña VARCHAR(25) NOT NULL,
id_rol INT NOT NULL, 
CONSTRAINT fk_usuario_roles FOREIGN KEY (id_rol) REFERENCES Roles(id_rol)
);

CREATE TABLE persona (
id_persona INT NOT NULL PRIMARY KEY,
id_usuario INT NOT NULL,
nom_persona VARCHAR(50) NOT NULL,
apellido_paterno VARCHAR(20) NOT NULL,
apellido_materno VARCHAR(20) NOT NULL,
rfc  VARCHAR(13) NOT NULL,
codigo_postal VARCHAR(5),
calle VARCHAR(20),
num_int TINYINT,
num_ext TINYINT,
colonia VARCHAR(50),
ciudad VARCHAR(20),
telefono VARCHAR(10) NOT NULL,
CONSTRAINT fk_usuario FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
);


-- Inventario
-- decimal
-- Insercion preedefinida

create table articulos(
id_articulo INT NOT NULL PRIMARY KEY,
nombre_articulo VARCHAR(70) NOT NULL,
descripcion TEXT NOT NULL,
categoria TEXT NOT NULL,
precio NUMERIC(16,2),
costo NUMERIC(16,2),
existencias INT NOT NULL DEFAULT 0,
fecha_registro DATETIME NOT NULL
);


-- COMPRA
-- MATERIA PRIMA
create table solicitud_compra(
id_solicitud INT NOT NULL PRIMARY KEY,
id_comprador_usuario int not null, -- referencia a la tabla usuario agregar constrain
id_proveedor_usuario int not null, -- referencia a la tabla usuario
descripcion TEXT NOT NULL,
total NUMERIC(16,2), -- sumatoria de todos los detalles
fecha_registro DATETIME NOT NULL,
estatus ENUM('generada', 'cotizada', 'comprado') DEFAULT 'generada' NOT NULL,
CONSTRAINT fk_id_comprador_usuario FOREIGN KEY (id_comprador_usuario) REFERENCES usuario(id_usuario),
CONSTRAINT fk_id_proveedor_usuario FOREIGN KEY (id_proveedor_usuario) REFERENCES usuario(id_usuario)
);

create table solicitud_compra_detalle(
id_solicitud_detalle INT NOT NULL PRIMARY KEY,
id_solicitud int not null, -- referencia a la tabla solicitud_compra agregar constrain
id_articulo INT NOT NULL,
cantidad INT NOT NULL,
precio_unitario NUMERIC(16,2),
subtotal NUMERIC(16,2), -- qutar posiblemente
total NUMERIC(16,2), -- Cantidad por precio unitario
fecha_registro DATETIME NOT NULL,
CONSTRAINT fk_id_solicitud FOREIGN KEY (id_solicitud) REFERENCES solicitud_compra(id_solicitud),
CONSTRAINT fk_id_solicitud_articulo FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo)
);


-- PRODUCCIÓN

-- PRODUCTO TERMINADO
create table pedido(
id_pedido VARCHAR(15) NOT NULL PRIMARY KEY,
id_cliente INT NOT NULL,
estatus ENUM('Generado', 'En preparacion','A enviar','En distribucion', 'En camino','Entregado') DEFAULT 'Generado',
fecha_registro DATETIME NOT NULL,
CONSTRAINT fk_id_cliente_pedi FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario)
);

create table producto(
id_producto INT NOT NULL PRIMARY KEY,
id_pedido VARCHAR(15) NOT NULL,
descripcion TEXT NOT NULL,
categoria TEXT NOT NULL,
precio_unitario FLOAT NOT NULL,
impuestos FLOAT NOT NULL,
cantidad INT NOT NULL,
personalizacion ENUM('icono', 'imagen', 'texto') NOT NULL,
id_cliente INT NOT NULL,
fecha DATETIME NOT NULL,
CONSTRAINT fk_id_cliente_producto FOREIGN KEY (id_cliente) REFERENCES Usuario(id_usuario),
CONSTRAINT fk_id_pedido FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
);

ALTER TABLE producto ADD nombre_producto VARCHAR(70) NOT NULL;

create table producto_consumibles(
id_producto_consumibles INT NOT NULL PRIMARY KEY,
descripcion TEXT NOT NULL,
id_articulo INT NOT NULL,
id_producto_t INT NOT NULL,
CONSTRAINT fk_id_articulo FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo),
CONSTRAINT fk_id_producto_t FOREIGN KEY (id_producto_t) REFERENCES producto(id_producto)
);



-- DISTRIBUCIÓN

create table pedido_bitacora(
id_pedido_bitacora INT NOT NULL PRIMARY KEY,
id_pedido VARCHAR(15) NOT NULL,
id_usuario INT NOT NULL,
estatus_pedido TEXT NOT NULL,
descripcion TEXT NOT NULL,
fecha_registro DATETIME NOT NULL,
CONSTRAINT fk_id_pedido_dis FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido),
CONSTRAINT fk_id_usuario_dis FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);


-- ALTER TABLE Pedido ADD fecha_entrega DATETIME NOT NULL;

INSERT INTO roles VALUES
(1,'Administrador'),
(2,'Cliente'),
(3,'Proveedor'),
(4,'Comprador'),
(5,'Vendedor'),
(6,'Producción'),
(7,'Distribuidor'),
(8,'Responsable stock');
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
nombre_art VARCHAR(70) NOT NULL,
descripcion TEXT NOT NULL,
categoria TEXT NOT NULL,
precio NUMERIC(16,2),
costo NUMERIC(16,2),
existencias int not DEFAULT 0,
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
CONSTRAINT fk_id_prov_art FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
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
CONSTRAINT fk_id_prov_art FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
);


-- PRODUCCIÓN

-- PRODUCTO TERMINADO
create table producto_terminado(
id_producto_t INT NOT NULL PRIMARY KEY,
descripcion TEXT NOT NULL,
categoria TEXT NOT NULL,
precio_unitario FLOAT NOT NULL,
impuestos FLOAT NOT NULL,
cantidad INT NOT NULL,
personalizacion ENUM('icono', 'imagen', 'texto') NOT NULL,
id_cliente INT NOT NULL,
fecha DATETIME NOT NULL,
CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);

ALTER TABLE producto_terminado ADD nombre_prod VARCHAR(70) NOT NULL;

create table producto_consumido(
id_producto_c INT NOT NULL PRIMARY KEY,
descripcion TEXT NOT NULL,
id_articulo INT NOT NULL,
id_producto_t INT NOT NULL,
CONSTRAINT fk_id_articulo FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo),
CONSTRAINT fk_id_producto_t FOREIGN KEY (id_producto_t) REFERENCES producto_terminado(id_producto_t)
);

create table pedido(
id_pedido VARCHAR(15) NOT NULL PRIMARY KEY,
id_cliente INT NOT NULL,
id_producto_t INT NOT NULL,
estatus ENUM('Enviado', 'En Camino','Entregado') DEFAULT 'Enviado',
fecha DATETIME NOT NULL,
CONSTRAINT fk_id_cliente_pedi FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
CONSTRAINT fk_id_producto_pedi FOREIGN KEY (id_producto_t) REFERENCES producto_terminado(id_producto_t)
);

-- DISTRIBUCIÓN

create table distribucion(
id_distribucion INT NOT NULL PRIMARY KEY,
id_pedido VARCHAR(15) NOT NULL,
id_usuario INT NOT NULL,
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
(8,'Responsables de control de stock');
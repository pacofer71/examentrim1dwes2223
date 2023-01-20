create table proveedores(
    id int auto_increment primary key,
    email varchar(100) unique not null,
    admin enum("SI", "NO") default "NO",
    password varchar(250) not null
);
create table articulos(
    id int auto_increment primary key,
    nombre varchar(100) unique not null,
    precio decimal(5,2) unsigned not null,
    imagen varchar(150) not null default '/img/default.png',
    stock int unsigned,
    enVenta enum("SI", "NO") default "SI", 
    proveedor_id int not null,
    fecha timestamp DEFAULT CURRENT_TIMESTAMP,
    constraint prov_arts foreign key(proveedor_id) references proveedores(id) on delete cascade on update cascade
);

-- 
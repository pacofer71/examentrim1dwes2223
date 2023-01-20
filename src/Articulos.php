<?php

namespace App;

use \PDO;
use \PDOException;

class Articulos extends Conexion
{
    private int $id;
    private string $nombre;
    private float $precio;
    private ?string $imagen;
    private int $stock;
    private string $enVenta;
    private int $proveedor_id;

    public function __construct()
    {
        parent::__construct();
    }

    //_________________________________________________ CRUD _________________________________________________________________________
    public function create()
    {
        $q = "insert into articulos(nombre, precio, imagen, stock, enVenta, proveedor_id) values(:n, :p, :i, :s, :ev, :pi)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':p' => $this->precio,
                ':i' => $this->imagen ?? '/img/default.png',
                ':s' => $this->stock,
                ':ev' => $this->enVenta,
                ':pi' => $this->proveedor_id,

            ]);
        } catch (PDOException $ex) {
            die("Error en crear " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function readAll(?int $id = null, ?string $enVenta = null)
    {
        $q = ($id == null) ? "select articulos.*, email, admin from proveedores, articulos where proveedor_id=proveedores.id order by email, nombre" :
            "select articulos.*, email, admin from proveedores, articulos where proveedor_id=proveedores.id AND articulos.id=:id";
        if ($enVenta != null) {
            $q = "select articulos.*, email, admin from proveedores, articulos where proveedor_id=proveedores.id AND enVenta='SI' order by email, nombre ";
        }
        parent::crearConexion();
        $stmt = parent::$conexion->prepare($q);
        try {
            if ($id == null)
                $stmt->execute();
            else {
                $stmt->execute([':id' => $id]);
            }
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
        parent::$conexion = null;
        return ($id == null) ? $stmt->fetchAll(PDO::FETCH_OBJ) : $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update(int $id)
    {
        $q = "update articulos set nombre=:n, precio=:p, imagen=:i, stock=:s, enVenta=:ev, proveedor_id=:pi where id=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':n' => $this->nombre,
                ':p' => $this->precio,
                ':i' => $this->imagen ?? '/img/default.png',
                ':s' => $this->stock,
                ':ev' => $this->enVenta,
                ':pi' => $this->proveedor_id,
                ':id' => $id

            ]);
        } catch (PDOException $ex) {
            die("Error en update " . $ex->getMessage());
        }
        parent::$conexion = null;
    }

    public static function delete($id)
    {
        parent::crearConexion();
        $q = "delete from articulos where id=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':id'=>$id
            ]);
        } catch (PDOException $ex) {
            die("Error en Borrar" . $ex->getMessage());
        }
        parent::$conexion = null;

    }


    //_________________________________________________ OTROS METODOS ________________________________________________________________
    public static function crearArticulos(int $cant)
    {
        if (self::hayArticulos()) return;

        //parent::crearConexion();

        $faker = \Faker\Factory::create('es_ES');
        $idP = Proveedores::devolverIds();
        for ($i = 0; $i < $cant; $i++) {
            (new Articulos)->setNombre(ucfirst($faker->unique()->word()))
                ->setPrecio($faker->randomFloat(2, 10, 999))
                ->setStock($faker->numberBetween(1, 50))
                ->setEnVenta($faker->randomElement(['SI', 'NO']))
                ->setProveedor_id($faker->randomElement($idP))
                ->create();
        }
    }

    private static function hayArticulos(): bool
    {
        parent::crearConexion();
        $q = "select id from articulos";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayArticulos " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }

    /**
     * Comprobamos si existe nombre articulo
     * @param string $n nombre
     * @param ?int $id id de articulo
     * @return bool nombre existe o no
     */

    public static function existeNombre(string $n, ?int $id = null): bool
    {
        parent::crearConexion();
        $q = ($id == null) ? "select id from articulos where nombre=:n" : "select id from articulos where nombre=:n AND id!=:id";
        $op = ($id == null) ? [':n' => $n] : [':n' => $n, ':id' => $id];
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute($op);
        } catch (PDOException $ex) {
            die("Error en existeNombre " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();

    }

    //_________________________________________________ SETTERS ______________________________________________________________________


    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of precio
     *
     * @return  self
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Set the value of imagen
     *
     * @return  self
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set the value of stock
     *
     * @return  self
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Set the value of enVenta
     *
     * @return  self
     */
    public function setEnVenta($enVenta)
    {
        $this->enVenta = $enVenta;

        return $this;
    }

    /**
     * Set the value of proveedor_id
     *
     * @return  self
     */
    public function setProveedor_id($proveedor_id)
    {
        $this->proveedor_id = $proveedor_id;

        return $this;
    }
}

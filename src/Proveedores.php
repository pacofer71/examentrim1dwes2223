<?php

namespace App;

use \PDO;
use \PDOException;

class Proveedores extends Conexion
{
    private int $id;
    private string $email;
    private string $password;
    private string $admin;

    public function __construct()
    {
        parent::__construct();
    }

    //___________________________________________ CRUD _______________________________________________________
    public function create()
    {
        $q = "insert into proveedores(email, password, admin) values(:e, :p, :a)";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ':e' => $this->email,
                ':p' => $this->password,
                ':a' => $this->admin
            ]);
        } catch (PDOException $ex) {
            die("Error en crear " . $ex->getMessage());
        }
        parent::$conexion = null;
    }
    public static function read(?string $email = null)
    {
        parent::crearConexion();
        $q = ($email == null) ? "select id, email from proveedores" : "select id from proveedores where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            if ($email == null)
                $stmt->execute();
            else
                $stmt->execute([':e' => $email]);
        } catch (PDOException $ex) {
            die("Error en read() " . $ex->getMessage());
        }
        parent::$conexion = null;
        return ($email == null) ? $stmt->fetchAll(PDO::FETCH_OBJ) : $stmt->fetch(PDO::FETCH_OBJ);
    }

    //___________________________________________ OTROS METODOS ______________________________________________
    public static function crearProveedores(int $cant)
    {
        if (self::hayProveedores()) return;
        $faker = \Faker\Factory::create("es_ES");

        for ($i = 0; $i < $cant; $i++) {
            (new Proveedores)->setEmail($faker->unique()->email())
                ->setPassword("secret0")
                ->setAdmin($faker->randomElement(["SI", "NO"]))
                ->create();
        }
    }
    private static function hayProveedores(): bool
    {
        parent::crearConexion();
        $q = "select id from proveedores";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute();
        } catch (PDOException $ex) {
            die("Error en hayProveedores " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->rowCount();
    }
    public static function esAdmin($email)
    {
        parent::crearConexion();
        $q = "select admin from proveedores where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':e' => $email]);
        } catch (PDOException $ex) {
            die("Error en esAdmin " . $ex->getMessage());
        }
        parent::$conexion = null;
        return (($stmt->fetch(PDO::FETCH_OBJ))->admin == "SI");
    }
    /**
     * @param string $e email
     * @param string $p password
     * 
     * @return bool 
     */

    public static function isUSerValid($e, $p): bool
    {
        parent::crearConexion();
        $q = "select password from proveedores where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([':e' => $e]);
        } catch (PDOException $ex) {
            die("Error en isUserValid " . $ex->getMessage());
        }
        parent::$conexion = null;

        if (!$stmt->rowCount()) return false;

        $pass = ($stmt->fetch(PDO::FETCH_ASSOC))['password'];
        return password_verify($p, $pass);
    }

    public static function devolverIds(?string $email = null): array
    {
        parent::crearConexion();
        $q = ($email == null) ? "select id from proveedores" : "select id from proveedores where email=:e";
        $stmt = parent::$conexion->prepare($q);
        try {
            if ($email == null)
                $stmt->execute();
            else
                $stmt->execute([':e' => $email]);
        } catch (PDOException $ex) {
            die("Error en devolverIds " . $ex->getMessage());
        }
        parent::$conexion = null;
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }


    //___________________________________________ SETTERS _____________________________________________________


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
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Set the value of admin
     *
     * @return  self
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }
}

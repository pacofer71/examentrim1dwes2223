<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_POST['id'])) {
    header("Location:../login.php");
    die();
}
$email = $_SESSION['email'];
$id=$_POST['id'];

require __DIR__ . "/../../vendor/autoload.php";

use App\{Articulos, Proveedores};

$admin = Proveedores::esAdmin($email);
if (!$articulo = Articulos::readAll($id)) {
    header("Location:index.php");
    die();
}
if (!$admin) {
    $proveedor_id = Proveedores::devolverIds($email)[0];
    if ($articulo->proveedor_id != $proveedor_id) {
        $_SESSION['mensaje'] = "Sólo puedes borrar tus artículos!!!";
        $_SESSION['icono'] = 'error';
        header("Location:index.php");
        die();
    }
}
Articulos::delete($id);
if(basename($articulo->imagen)!="default.png"){
    unlink(__DIR__."/..".$articulo->imagen);
}
$_SESSION['mensaje']="Artículo Borrado con Éxito";
header("Location:index.php");
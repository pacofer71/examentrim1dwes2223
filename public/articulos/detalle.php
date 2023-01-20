<?php
if(!isset($_GET['id'])){
    header("Location:index.php");
    die();
}
$id=$_GET['id'];

session_start();
require __DIR__ . "/../../vendor/autoload.php";

use App\Articulos;
$articulo = Articulos::readAll($id);
// echo "<pre>";
// var_dump($articulo);
// echo "</pre>";


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Inicio</title>
</head>

<body style="background-color:#b2dfdb">
    <?php
    require __DIR__ . "/../../layouts/navbar.php";
    ?>
    <div class="container">
        <div class="card mx-auto mt-4" style="width: 34rem;">
            <img src="<?php echo "./..".$articulo->imagen ?>" class="card-img-top" alt="no se">
            <div class="card-body" style="background-color:#d2dfdb">
                <h5 class="card-title"><b>Nombre: </b><?php echo $articulo->nombre; ?></h5>
                <h6 class="card-subtitle my-2"><b>Proveedor: </b><i><?php echo $articulo->email; ?></i></h6>
                <p class="card-text"><b>Precio: </b><?php echo $articulo->precio. " €"; ?></p>
                <p class="card-text"><b>Stock: </b><?php echo $articulo->stock; ?></p>
                <p class="card-text"><b>Estado: </b>Este artículo <span class="text-info"><?php echo $articulo->enVenta ?></span> está en venta</p>
                <a href="index.php" class="btn btn-primary"><i class="fas fa-backward"></i> Volver</a>
            </div>
        </div>
    </div>
</body>

</html>
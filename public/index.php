<?php
    session_start();
    require __DIR__."/../vendor/autoload.php";
    use App\{Proveedores, Articulos};
    Proveedores::crearProveedores(10);
    Articulos::crearArticulos(100);
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
    require __DIR__."/../layouts/navbar.php";
    ?>
    <div class="container my-8 px-4 py-4 text-center">
        <img src="./img/portada.jpeg" class="img-thumbnail" width="950rem" height="950rem">
    </div>
</body>
</html>
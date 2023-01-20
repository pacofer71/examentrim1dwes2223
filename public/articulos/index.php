<?php
session_start();
$logeado = false;
if (isset($_SESSION['email'])) {
    // header("Location:../index.php");
    // die();
    $logeado = true;
}
require __DIR__ . "/../../vendor/autoload.php";

use App\Articulos;

$articulos = ($logeado) ? Articulos::readAll() : Articulos::readAll(enVenta: "SI");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
            crossorigin="anonymous"></script>

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
          integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- SweetAlert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Articulos</title>
</head>

<body style="background-color:#b2dfdb">
<?php
require __DIR__ . "/../../layouts/navbar.php";
?>
<h5 class="text-center my-4">Listado de Articulos</h5>
<div class="container">
    <?php
    if ($logeado) {
        echo <<<TXT
        <div class="d-flex flex-row-reverse">
            <a href="nuevo.php" class="btn btn-success mb-2"><i class="fas fa-add"></i> Nuevo</a>
        </div>
        TXT;
    }
    ?>


    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Info</th>
            <th scope="col">Nombre</th>
            <th scope="col">Precio</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($articulos as $item) {
            $colorAdmin = ($item->admin == 'SI') ? "text-success" : "text-primary";
            $enVenta = ($item->enVenta == 'NO') ? "style='text-decoration:line-through; color:red;'" : "";
            $activo = ($logeado) ? "" : "disabled";
            $iconoAdmin=($item->admin == 'SI') ? "<i class='fa-solid fa-gears text-danger'></i>" : "<i class='fa-solid fa-user'></i>";
            echo <<<TXT
                    <tr>
                    <th scope="row">
                    <a href="detalle.php?id={$item->id}" class="btn btn-info btn-sm"><i class="fas fa-info"></i></a>
                    </th>
                    <td $enVenta>{$item->nombre}</td>
                    <td>{$item->precio} €</td>
                    <td class="$colorAdmin"><b>{$item->email}</b> [ $iconoAdmin ]</td>
                    <td>
                    <form name='a' action='borrar.php' class='form-inline' method='POST'>
                    <input type="hidden" name='id' value='{$item->id}' />
                    <a href="update.php?id={$item->id}" class="btn btn-sm btn-warning $activo"><i class='fa fa-edit'></i></a>
                    <button type='submit' class="btn btn-sm btn-danger $activo" onclick="return confirm('¿Borrar Artículo?')"><i class="fas fa-trash"></i></button>
                    </form>
                    </td>
                    </tr>
                TXT;
        }
        ?>

        </tbody>
    </table>

</div>
<?php
$icono = 'success';
if (isset($_SESSION['icono'])) {
    $icono = 'error';
    unset($_SESSION['icono']);
}
if (isset($_SESSION['mensaje'])) {
    echo <<<TXT
            <script>
            Swal.fire({
                icon: '$icono',
                title: '{$_SESSION['mensaje']}',
                showConfirmButton: false,
                timer: 1500
              })
              </script>
            TXT;
    unset($_SESSION['mensaje']);
}
?>


</body>

</html>
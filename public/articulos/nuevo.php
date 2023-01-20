<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location:../login.php");
    die();
}
$email = $_SESSION['email'];

require __DIR__ . "/../../vendor/autoload.php";

use App\{Articulos, Tools, Proveedores};

$admin = Proveedores::esAdmin($email);
if ($admin) {
    $listaProv = Proveedores::read();
}


function mostrarErrores($nombre)
{
    if (isset($_SESSION[$nombre])) {
        echo "<p class='text-danger mt-2'><b>{$_SESSION[$nombre]}</b></p>";
        unset($_SESSION[$nombre]);
    }
}
//nos traemos proveedor_id


if (isset($_POST['btn'])) {
    $nombre = trim($_POST['nombre']);
    $precio = (int)trim($_POST['precio']);
    $stock = (int)trim($_POST['stock']);
    $enVenta = (isset($_POST['enVenta'])) ? "SI" : "NO";
    $error = false;
    $proveedor_id = ($admin) ? $_POST['proveedor_id'] : Proveedores::read($email)->id;

    if (strlen($nombre) < 2) {
        $error = true;
        $_SESSION['nombre'] = "*** El campo nombre debe coantener al menos dos caracteres.";
    }
    if (Articulos::existeNombre($nombre)) {
        $error = true;
        $_SESSION['nombre'] = "*** YA existe un artículo con ese nombre.";
    }
    if ($precio <= 0) {
        $error = true;
        $_SESSION['precio'] = "*** El precio debe ser mayor que cero.";
    }
    if ($precio <= 0) {
        $error = true;
        $_SESSION['stock'] = "*** El stock debe ser mayor que cero.";
    }
    if ($error) {
        header("Location:nuevo.php");
        die();
    }
    //Procesamos la imagen
    $nom_img = "/img/default.png";
    if ($_FILES['imagen']['error'] == 0) {
        if (!in_array($_FILES['imagen']['type'], Tools::getImagesType())) {
            $_SESSION['imagen'] = "*** Se esperaba un fichero de Imagen.";
            header("Location:nuevo.php");
            die();
        }
        $nom_img = "/img/" . uniqid() . "-" . $_FILES['imagen']['name'];
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . "/.." . $nom_img)) {
            $txt = "pero No se pudo guardar la imagen.";
            $nom_img = "/img/default.png";
        } else {
            $txt = ".";
        }
    }
    (new Articulos)->setNombre($nombre)
        ->setEnVenta($enVenta)
        ->setImagen($nom_img)
        ->setPrecio($precio)
        ->setStock($stock)
        ->setProveedor_id($proveedor_id)
        ->create();
    $_SESSION['mensaje'] = "Articulo Guardado $txt";
    header("Location:index.php");
} else {


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
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

        <!-- Fontawesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- SweetAlert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Articulos Crear</title>
    </head>

    <body style="background-color:#b2dfdb">
        <?php
        require __DIR__ . "/../../layouts/navbar.php";
        ?>
        <h5 class="text-center my-4">Nuevo Artículo<?php echo ($admin) ? " (Administrador)" : "" ?></h5>
        <div class="container">
            <form name="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="text-light rounded mx-auto px-4 py-4 bg-secondary" style="width:44rem" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Nombre" name="nombre">
                        <?php
                        mostrarErrores("nombre");
                        ?>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" placeholder="Precio (€)" name="precio" step='0.01' min='0.00' max='999.99'>
                        <?php
                        mostrarErrores("precio");
                        ?>
                    </div>
                </div>
                <?php
                if ($admin){
                    echo "<div class='mt-4'>";
                echo "<select name='proveedor_id' class='form-control'>";
                foreach ($listaProv as $item) {
                    $selected=($item->email==$email) ? "selected" : "";
                    echo "<option value='{$item->id}' $selected>{$item->email}</option>";
                }
                echo "</select>";
                echo "</div>";
            }
                ?>
                <div class="row g-3 mt-3">
                    <div class="col">
                        <input type="number" class="form-control" placeholder="Stock" name="stock" min=1>
                        <?php
                        mostrarErrores("stock");
                        ?>
                    </div>
                    <div class="col">
                        <input type="file" class="form-control" name="imagen" accept="image/*" id="imgart">
                    </div>
                </div>
                <div class="row g-3 my-4">
                    <div class="col">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="env" name="enVenta">
                            <label class="form-check-label" for="env">En venta</label>
                        </div>

                    </div>
                    <div class="col">
                        <img src="../img/noimage.png" id="logo" class="img-thumbnail" style="width:14rem; height:14rem" />
                    </div>
                    <?php
                    mostrarErrores("imagen");
                    ?>
                </div>
                <div class="d-flex flex-row-reverse">
                    <button type="submit" class="btn btn-primary" name="btn"><i class="fas fa-save"></i>Guardar</button>
                    &nbsp;&nbsp;
                    <button type="reset" class="btn btn-warning">
                        <i class="fas fa-paintbrush"></i> Limpiar
                    </button>
                </div>

            </form>
        </div>
        <script>
            //Cambiar imagen
            document.getElementById("imgart").addEventListener('change', cambiarImagen);

            function cambiarImagen(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("logo").setAttribute('src', event.target.result)
                };
                reader.readAsDataURL(file);
            }
        </script>
    </body>
    </body>

    </html>
<?php } ?>
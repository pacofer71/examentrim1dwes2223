<?php
session_start();

require __DIR__ . "/../vendor/autoload.php";

use App\Proveedores;

function mostrarError($nombre)
{
    if (isset($_SESSION[$nombre])) {
        echo "<p class='text-danger my-2'>{$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    }
}

function error()
{
    header("Location:{$_SERVER['PHP_SELF']}");
    die();
}
if (isset($_POST['btnLogin'])) {
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email'] = "*** Se esparaba un email.";
        error();
    }
    if (strlen($pass) == 0) {
        $_SESSION['pass'] = "*** Password NO puede estar vacio";
        error();
    }
    if (!Proveedores::isUSerValid($email, $pass)) {
        if (!isset($_COOKIE['contador'])) {
            setcookie("contador", 1, time() + 60 * 60);
            //echo $_COOKIE['contador']. "Existe <br>";
            //die();
        } else {
            $cont = $_COOKIE["contador"];
            if ($cont == 2) {
                setcookie("contador", 1, time() - 200);
                setcookie("bloqueo", "valor", time()+60);
                //echo "<br>Bloqueo Activado!!!!<br>";
                //die();
            } else {
                setcookie("contador", ++$cont , time() + 60 * 60);
                //echo $_COOKIE['contador'];
                //die();
            }
        }

        $_SESSION['validar'] = "*** Email o password erroneos!!!";
        error();
    }
    $_SESSION['email'] = $email;
    setcookie("contador", 1, time()-1000);
    header("Location:./articulos/");
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
        <!-- Fontawesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- SweetAlert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Inicio</title>
    </head>

    <body style="background-color:#b2dfdb">
        <form name='login' method='POST' action='<?php echo $_SERVER['PHP_SELF'] ?>'>
            <section class="vh-100 gradient-custom">
                <div class="container py-5 h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                            <div class="card bg-dark text-white" style="border-radius: 1rem;">
                                <div class="card-body p-5 text-center">

                                    <div class="mb-md-5 mt-md-4 pb-5">

                                        <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                                        <p class="text-white-50 mb-5">Please enter your email and password!</p>
                                        <div class="form-outline form-white mb-4">
                                            <input type="email" name="email" id="n" class="form-control form-control-lg" required />
                                            <?php
                                            mostrarError("email");
                                            ?>
                                            <label class="form-label" for="n">@Email</label>
                                        </div>

                                        <div class="form-outline form-white mb-4">
                                            <input type="password" name="pass" id="p" class="form-control form-control-lg" />
                                            <?php
                                            mostrarError("pass");
                                            ?>
                                            <label class="form-label" for="p">Password</label>
                                        </div>
                                        <?php
                                        mostrarError("validar");
                                        $botonActivo=(isset($_COOKIE['bloqueo'])) ? "disabled" : "";
                                        ?>


                                        <button class="btn btn-outline-light btn-lg px-5" type="submit" name="btnLogin" <?php echo $botonActivo; ?>>
                                            <i class="fa-solid fa-right-to-bracket"></i> Login
                                        </button>

                                        <?php
                                        mostrarError("errVal");
                                        if(isset($_COOKIE['bloqueo'])){
                                            echo "<p class='text-danger'><i class='fa-solid fa-triangle-exclamation'></i> Demasiados intentos de Login, espere 60s</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </body>
    </body>

    </html>
<?php } ?>
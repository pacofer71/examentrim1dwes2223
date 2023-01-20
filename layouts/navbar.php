<?php
   if(str_contains($_SERVER['REQUEST_URI'], 'articulos')){
        $rutaLogout="logout.php";
        $rutaInicio="./../index.php";
        $rutaArticulos="index.php";
        $rutaLogin="./../login.php";
    }else{
        $rutaLogout="./articulos/logout.php";
        $rutaInicio="index.php";
        $rutaArticulos="./articulos/";
        $rutaLogin="login.php";
    }
    $nombre=(!isset($_SESSION['email'])) ? "<a href='$rutaLogin' class='btn btn-primary' type='submit'>Login</a>" : "<input class='form-control me-2' type='text' readonly value='{$_SESSION['email']}'>".
    "<a href='$rutaLogout' class='btn btn-danger'>Salir</a>";
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid ">
        <a href="<?php echo $rutaInicio ?>" class="navbar-brand"><i class="fas fa-home"></i> INICIO</a>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="<?php echo $rutaArticulos ?>" class="nav-link active" aria-current="page" href="#">
                        <i class="fa-solid fa-gauge-high"></i> Articulos</a>
                </li>
                
            </ul>
        </div>

        <div class="d-flex" role="search">
            <?php echo $nombre ?>
        </div>
    </div>

</nav>
<?php
include "funciones.php";
require "conexionbbdd.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
       
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" href="./css/index.css">
    </head>

    <body>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light m-2">
                <a class="navbar-brand" href="#"><img class="logo" src="./images/logo.png"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Todas las zapatillas</a>
                        </li>
                        <?php menuAdmin();?>
                        <?php if($_SESSION["usuario"]!="root@gmail.com"){?>
                        <li class="nav-item">
                            <a class="nav-link" href="vercarrito.php">Ver carrito</a>
                        </li>
                        <?php }?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <form action="modificarusuario2.php" method="post">
            <table class="table table-hover table-dark">
            <th>Modificar</th>
                <?php
                $consultaColumnas = "SHOW COLUMNS FROM usuarios";
                $arrayColumnas = array();
                try {
                    $resultado = $db->prepare($consultaColumnas);
                    $resultado->execute();
                    while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                        array_push($arrayColumnas, $columnas["Field"]);
                    }

                    for ($i = 0; $i < count($arrayColumnas); $i++) { ?>
                        <th><?php print $arrayColumnas[$i]; ?></th>

                <?php
                    }
                    $consulta="SELECT * from usuarios";
                    $resultado = $db->prepare($consulta);
                    $resultado->execute();
                    while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
                        for ($i = 0; $i < count($arrayColumnas); $i++){
                            if($i==0){
                                if($columnas["nombre"]!="Root") print "<tr><td><input type='radio' name='modificaruser' value='".$columnas[$arrayColumnas[$i]]."'></td><td>".$columnas[$arrayColumnas[$i]]."</td>";
                                else print "<tr><td></td><td>".$columnas[$arrayColumnas[$i]]."</td>";
                            }
                                 else print "<td>".$columnas[$arrayColumnas[$i]]."</td>";
                        }
                        
                    }
                } catch (PDOException $e) {
                    print $e->getMessage();
                }
                ?>
            </table>
            <input type="submit" class="btn btn-primary" value="Modificar" name="modificar">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
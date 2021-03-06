<?php
require "conexionbbdd.php";
include "funciones.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if (isset($_POST["eliminar"])) {
        if (isset($_POST["lista"])) {
            $lista = $_POST["lista"];
            foreach ($lista as $valor) {
                $eliminar = "DELETE FROM zapatillas where id=?";
                $consulta="SELECT * from zapatillas where id=?";
                try {
                    $resultado = $db->prepare($consulta);
                    $resultado->execute([$valor]);
                    if ($resultado){
                        $cont=0;
                        $insertar="INSERT INTO zapatillaseliminadas(modelo,imagen,precio,idcategoria) values(";
                        while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
                            $insertar.="'".$columnas["modelo"]."','".$columnas["imagen"]."','".$columnas["precio"]."','".$columnas["idcategoria"]."')";
                        }
                        $resultado2=$db->prepare($insertar);
                        $resultado2->execute();
                        if($resultado2->rowCount()>0){
                            $resultado2=$db->prepare($eliminar);
                            $resultado2->execute([$valor]); 
                        }
                    }
                    else print "Error";
                } catch (PDOException $e) {
                    print $e->getMessage();
                };
            }
        }else header("Location:eliminarzapatillas.php?error='error'");
    }
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
                        <?php menuAdmin(); ?>
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
            <form action="eliminarzapatillas.php" method="post">
                <table class="table table-hover table-dark">
                    <th>Eliminar</th>
                    <?php
                    $consultaColumnas = "SHOW COLUMNS FROM zapatillas";
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
                        $consulta = "SELECT * from zapatillas";
                        $resultado = $db->prepare($consulta);
                        $resultado->execute();
                        while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                            for ($i = 0; $i < count($arrayColumnas); $i++) {
                                if ($i == 0) print "<tr><td><input type='checkbox' name='lista[]' value='" . $columnas[$arrayColumnas[$i]] . "'></td><td>" . $columnas[$arrayColumnas[$i]] . "</td>";
                                else print "<td>" . $columnas[$arrayColumnas[$i]] . "</td>";
                            }
                        }
                        if(isset($_REQUEST["error"])) print "<p style='color:red;'>No has seleccionado ninguna zapatilla</p>";
                    } catch (PDOException $e) {
                        print $e->getMessage();
                    }
                    ?>
                </table>
                <input type="submit" class="btn btn-primary" value="Borrar" name="eliminar">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
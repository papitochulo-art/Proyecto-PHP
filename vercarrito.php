<?php
require "conexionbbdd.php";
include "funciones.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if (isset($_POST["borrar"])) {
        if (isset($_POST["borrarcarrito"])) {
            $borrarcarrito = $_POST["borrarcarrito"];
            foreach ($borrarcarrito as $valor) {
                $eliminar = "DELETE FROM carrito where id=?";
                try {
                    $resultado = $db->prepare($eliminar);
                    $resultado->execute([$valor]);
                    if($resultado) header("Location:vercarrito.php");
                    else print "Error";
                } catch (PDOException $e) {
                    print $e->getMessage();
                };
            }
        }
    }
    else if(isset($_POST["pagar"])) header("Location:finalizarcompra.php");
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
    <form action="vercarrito.php" method="POST">
            <table class="table table-hover table-dark">
            <th>Eliminar</th>
            <th>Modelo</th>
            <th>Imagen</th>
            <th>Precio</th>
            <th>Talla</th>
            <th>Color</th>
            <?php
             $buscariduser="SELECT id from usuarios where email=?";
             $resultado = $db->prepare($buscariduser);
             $resultado->execute([$_SESSION["usuario"]]);
             while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
             $iduser=$columnas["id"];
             }
            $mostrarcarrito="SELECT id,modelo,imagen,precio,talla,color from carrito where idusuario=?";
            $resultado=$db->prepare($mostrarcarrito);
            $resultado->execute([$iduser]);
            while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){?>
            <tr>
            <td><input type="checkbox" name="borrarcarrito[]" value="<?php print $columnas["id"]; ?>"></td>
            <td><?php print $columnas["modelo"]?></td>
            <td><img src="./images/zapatillas/<?php print $columnas["imagen"]?>"></td>
            <td><?php print $columnas["precio"]?></td>
            <td><?php print $columnas["talla"]?></td>
            <td><?php print $columnas["color"]?></td>
            </tr>

<?php
            }


?>
            
            </table>
            <input type="submit" value="Borrar" name="borrar">
            <input type="submit" value="Pagar" name="pagar">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
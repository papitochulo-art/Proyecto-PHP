<?php
require "conexionbbdd.php";
include "funciones.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if (isset($_POST["anhadir"])) {
        $idtalla;
        $consultaid = "SELECT stock from tallas where color=? and talla=? and idzapatilla=?";
        $resultado = $db->prepare($consultaid);
        $resultado->execute([$_POST["color"], $_POST["talla"], $_REQUEST["id"]]);
        if ($resultado->rowCount() > 0) {
            $stock;
            while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $stock = $columnas["stock"];
            }
            if ($stock > 0) {
                $buscardatoszapas = "SELECT modelo,imagen,precio from zapatillas where id=?";
                $resultado = $db->prepare($buscardatoszapas);
                $resultado->execute([$_POST["id"]]);
                while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
                    $modelo=$columnas["modelo"];
                    $imagen=$columnas["imagen"];
                    $precio=$columnas["precio"];
                }
                $buscariduser="SELECT id from usuarios where email=?";
                $resultado = $db->prepare($buscariduser);
                $resultado->execute([$_SESSION["usuario"]]);
                while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
                $iduser=$columnas["id"];
                }
                $insertar="INSERT INTO carrito (modelo,imagen,precio,talla,color,idusuario) values(?,?,?,?,?,?)";
                $resultado = $db->prepare($insertar);
                $resultado->execute([$modelo,$imagen,$precio,$_POST["talla"],$_POST["color"],$iduser]);
                if($resultado) header("Location:vercarrito.php");
                else print "Error"; 
            }else print "No hay tallas";
            }else print "No hay tallas para este color";
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

            <form action="carrito.php" method="post">
                <input type="text" class="oculto" name="id" value="<?php print $_REQUEST["id"] ?>">
                <label>Elija una talla: </label>
                <?php
                $consultatallas = "SELECT talla from tallas where idzapatilla=? order by talla asc";
                $resultado = $db->prepare($consultatallas);
                $resultado->execute([$_REQUEST["id"]]);
                print "<select name='talla'>";
                while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    print "<option value=" . $columnas["talla"] . ">" . $columnas["talla"] . "</option>";
                }
                print "</select>";
                print "<label>Elija un color: </label>";
                $consultacolor = "SELECT distinct color from tallas where idzapatilla=?";
                $resultado = $db->prepare($consultacolor);
                $resultado->execute([$_REQUEST["id"]]);
                print "<select name='color'>";
                while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    print "<option value=" . $columnas["color"] . ">" . $columnas["color"] . "</option>";
                }
                print "</select>";
                ?>
                <input type="submit" value="Añadir al carrito" name="anhadir">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
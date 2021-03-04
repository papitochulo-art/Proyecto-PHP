<?php
require "conexionbbdd.php";
include "funciones.php";
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
            <form action="ordenar.php" method="post">
            Ordenar por: 
            <select name="ordenarpor">
            <option value="order by modelo asc">Modelo Asc (por defecto)</option>
            <option value="order by modelo desc">Modelo Desc</option>
            <option value="order by precio asc">Precio Asc</option>
            <option value="order by precio desc">Precio Desc</option>
            </select>
            Filtrar por marca: 
                <?php
                $consulta="SELECT id,marca from categorias";
                $resultado=$db->prepare($consulta);
                $resultado->execute();
                print "<select name='filtrarpormarca'><option value='todas'>Todas</option>";
                while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
                    print "<option value=".$columnas["id"].">".$columnas["marca"]."</option>";
                }
                print "</select>";
                ?>
                    Filtrar por talla:
             <select name="talla">
             <option value="todas">Todas</option>  
                 <?php
                 for($i=38;$i<=48;$i++) print "<option value=".$i.">".$i."</option>";
                 ?>
             </select> 
            <input type="submit" value="Filtrar" name="ordenar">
            </form>
            <?php
            if($_REQUEST["filtrarpormarca"]=="todas" && $_REQUEST["talla"]=="todas"){
            $consulta = "SELECT z.*,c.imagenmarca from zapatillas as z,categorias as c where z.idcategoria=c.id ".$_REQUEST["ordenarpor"];
            mostrarZapasIndex($consulta);
            }
            elseif($_REQUEST["talla"]!="todas" && $_REQUEST["filtrarpormarca"]!="todas"){
                $imagenmarca = "SELECT imagenmarca from categorias where id=" . $_REQUEST["filtrarpormarca"];
                $resultado = $db->prepare($imagenmarca);
                $resultado->execute();
                while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) $imagen = $columnas["imagenmarca"];
                $consulta = "SELECT z.*,c.imagenmarca from zapatillas as z,categorias as c,tallas as t where t.talla=".$_POST["talla"]." and c.imagenmarca='".$imagen."' and t.idzapatilla=z.id  and z.idcategoria=c.id ".$_REQUEST["ordenarpor"];
                mostrarZapasIndex($consulta);
            }
            elseif($_REQUEST["filtrarpormarca"]!="todas"){
                $imagenmarca = "SELECT imagenmarca from categorias where id=" . $_REQUEST["filtrarpormarca"];
                $resultado = $db->prepare($imagenmarca);
                $resultado->execute();
                while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) $imagen = $columnas["imagenmarca"];
                $consulta = "SELECT z.*,c.imagenmarca from zapatillas as z,categorias as c where z.idcategoria=" . $_REQUEST["filtrarpormarca"] . " and c.imagenmarca='" . $imagen . "'".$_REQUEST["ordenarpor"];
                mostrarZapasIndex($consulta);
            }

            elseif($_REQUEST["talla"]!="todas"){
                $consulta = "SELECT z.*,c.imagenmarca from zapatillas as z,categorias as c,tallas as t where t.talla=".$_POST["talla"]." and t.idzapatilla=z.id  and z.idcategoria=c.id ".$_REQUEST["ordenarpor"];
                mostrarZapasIndex($consulta);
            }

                ?>

                    </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
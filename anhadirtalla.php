<?php
require "conexionbbdd.php";
include "funciones.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if(isset($_POST["anhadir"])){
        $insertar="INSERT into tallas (talla,color,stock,idzapatilla) values(?,?,?,?)";
        if(($_POST["talla"]>=38 && $_POST["talla"]<=46)&&preg_match("/^[A-Za-z\/]{1,30}$/",$_POST["color"])&&is_numeric($_POST["stock"])&&$_POST["stock"]>0){
try{
    $tallarepetida=false;
    if(comprobarTablaVacia("tallas")>0){
    $tallasexistentes="SELECT talla from tallas where idzapatilla=?";
    $resultado = $db->prepare($tallasexistentes);
    if($_POST["idzapatilla"]>=10) $resultado->execute([substr($_POST["idzapatilla"],0,2)]);
    else $resultado->execute([substr($_POST["idzapatilla"],0,1)]);
    while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)) if($columnas["talla"]==$_POST["talla"]) $tallarepetida=true;
    }
    if($tallarepetida==false){
    $resultado = $db->prepare($insertar);
    if($_POST["idzapatilla"]>=10)
    $resultado->execute([$_POST["talla"],$_POST["color"],$_POST["stock"],substr($_POST["idzapatilla"],0,2)]);
    else 
    $resultado->execute([$_POST["talla"],$_POST["color"],$_POST["stock"],substr($_POST["idzapatilla"],0,1)]);
    if($resultado) header("Location:index.php");
    else print "Error";
    }else header("Location:anhadirtalla.php?errortalla='error'");
}
catch (PDOException $e) {
    print $e->getMessage();
}
        }
        else header("Location:anhadirtalla.php?error='error'");
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
            <form action="anhadirtalla.php" method="post">
                <?php
                $consultaColumnas = "SHOW COLUMNS FROM tallas";
                $arrayColumnas = array();
                try {
                    $resultado = $db->prepare($consultaColumnas);
                    $resultado->execute();
                    while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                        if($columnas["Field"]!="id" && $columnas["Field"]!="talla")
                        array_push($arrayColumnas, $columnas["Field"]);
                    }
                    print "<label>talla</label>";
                    print "<select name='talla'>";
                    for($i=38;$i<=46;$i++) print "<option value=".$i.">".$i."</option>";
                    print "</select>";
                    for ($i = 0; $i < count($arrayColumnas); $i++) { ?>
                        <div class="form-group">
                            <label><?php print $arrayColumnas[$i];?></label>
                            <?php if($arrayColumnas[$i]=="idzapatilla"){
                                $consultaCategorias="SELECT id,modelo from zapatillas";
                                $resultado2=$db->prepare($consultaCategorias);
                                $resultado2->execute();
                                print "<select name='idzapatilla'>";
                                while($columnas=$resultado2->fetch(PDO::FETCH_ASSOC)) 
                                print "<option>".$columnas["id"]." - ".$columnas["modelo"]."</option>";
                                print "</select>";
                            }
                            else print "<input type='text' class='form-control' name=".$arrayColumnas[$i].">";
                            ?>
                        </div>
                <?php
                    }
                } catch (PDOException $e) {
                    print $e->getMessage();
                }
                if(isset($_REQUEST["error"])){
                  echo "<p style='color:red';>Datos incorrectos</p>";
                }
                else if(isset($_REQUEST["errortalla"])) echo "<p style='color:red';>Talla ya existente</p>";
                ?>
            <input type="submit" class="btn btn-primary" value="Añadir talla" name="anhadir">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
<?php
require "conexionbbdd.php";
include "funciones.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if(isset($_POST["anhadir"])){
        if(preg_match("/^([a-zA-Z0-9]{0,50}\s?){1,10}$/",$_POST["modelo"])&&is_numeric($_POST["precio"])){
try{
    if(is_uploaded_file($_FILES["imagen"]["tmp_name"])){
        $directorio="images/zapatillas/";
        $nombre=$_FILES["imagen"]["name"];
        $directoriofinal=$directorio.$nombre;
        move_uploaded_file($_FILES["imagen"]["tmp_name"],$directoriofinal);
    }
    $registrorepetido=false;
    $consulta="SELECT * FROM zapatillas";
    $resultado = $db->prepare($consulta);
    $resultado->execute();
    while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
        if($columnas["modelo"]==$_POST["modelo"]) $registrorepetido=true;
    }
    $insertar="INSERT into zapatillas (modelo,imagen,precio,idcategoria) values(?,?,?,?)";
    if(!$registrorepetido){
    $resultado = $db->prepare($insertar);
    $resultado->execute([$_POST["modelo"],$_FILES["imagen"]["name"],$_POST["precio"],substr($_POST["idcategoria"],0,1)]);
    if($resultado) header("Location:index.php");
    else print "Error";
    }
    else print "Registro duplicado";
}catch (PDOException $e) {
    print $e->getMessage();
}
        }else header("Location:anhadirzapatilla.php?error='error'");
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
            <form action="anhadirzapatilla.php" method="post" enctype="multipart/form-data">
                <?php
                $consultaColumnas = "SHOW COLUMNS FROM zapatillas";
                $arrayColumnas = array();
                try {
                    $resultado = $db->prepare($consultaColumnas);
                    $resultado->execute();
                    while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                        if($columnas["Field"]!="id")
                        array_push($arrayColumnas, $columnas["Field"]);
                    }

                    for ($i = 0; $i < count($arrayColumnas); $i++) { ?>
                        <div class="form-group">
                            <label><?php print $arrayColumnas[$i];?></label>
                            <?php if($arrayColumnas[$i]=="imagen")
                            print "<input type='file' class='form-control' name='".$arrayColumnas[$i]."'>";
                            else if($arrayColumnas[$i]=="idcategoria"){
                                $consultaCategorias="SELECT id,marca from categorias";
                                $resultado2=$db->prepare($consultaCategorias);
                                $resultado2->execute();
                                print "<select name='idcategoria'>";
                                while($columnas=$resultado2->fetch(PDO::FETCH_ASSOC)) 
                                print "<option>".$columnas["id"]." - ".$columnas["marca"]."</option>";
                                print "</select>";
                            }
                            else
                            print "<input type='text' class='form-control' name=".$arrayColumnas[$i].">";
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
                ?>
                        <input type="submit" class="btn btn-primary" value="Añadir zapatilla" name="anhadir">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
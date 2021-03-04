<?php
include "funciones.php";
require "conexionbbdd.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if (isset($_POST["cambiar"])) {
        $consultaColumnas = "SHOW COLUMNS FROM usuarios";
        $arrayColumnas = array();
        if(preg_match("/^[a-zA-Z0-9]{1,10}@gmail.com$/",$_POST["email"])&& preg_match("/^([a-zA-Z0-9]{3,}\s?){1,3}$/",$_POST["nombre"])){
        try {
            $resultado = $db->prepare($consultaColumnas);
            $resultado->execute();
            while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                    array_push($arrayColumnas, $columnas["Field"]);
            }
            $actualizar = "UPDATE usuarios set ";
            for ($i = 0; $i < count($arrayColumnas); $i++) {
                if ($i == count($arrayColumnas) - 1) $actualizar .= "," . $arrayColumnas[$i] . "='" . $_POST[$arrayColumnas[$i]] . "' WHERE id=" . $_POST["id"];
                elseif ($i == 1) $actualizar .= $arrayColumnas[$i] . "='" . $_POST[$arrayColumnas[$i]] . "'";
                elseif ($i != 0) $actualizar .= "," . $arrayColumnas[$i] . "='" . $_POST[$arrayColumnas[$i]] . "'";
            }
            print $actualizar;
            $resultado = $db->prepare($actualizar);
            $resultado->execute();
            if ($resultado) header("Location:index.php");
            else print "Error";
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }else header("Location:modificarusuario2.php?error='error'");
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
            <form action="modificarusuario2.php" method="post" class="formulario">
                <?php
                if(isset($_REQUEST["modificaruser"])){
                    $iduser = $_REQUEST["modificaruser"];
                    print "<input class='oculto' type='text' name='id' value='" . $iduser . "'>";
                    $consultaColumnas = "SHOW COLUMNS FROM usuarios";
                    $arrayColumnas = array();
                    try {
                        $resultado = $db->prepare($consultaColumnas);
                        $resultado->execute();
                        while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                            if ($columnas["Field"] != "id") array_push($arrayColumnas, $columnas["Field"]);
                        }
                        $consultazapatillas = "SELECT * from usuarios where id=?";
                        $resultado = $db->prepare($consultazapatillas);
                        $resultado->execute([$iduser]);
                        if ($resultado->rowCount() > 0) {
                            while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                for ($i = 0; $i < count($arrayColumnas); $i++) {
                ?>
                                    <div class="form-group">
                                        <label><?php print $arrayColumnas[$i]; ?></label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" name="<?php print $arrayColumnas[$i]; ?>" value='<?php print $columnas[$arrayColumnas[$i]] ?>'>
                                    </div>
                <?php
                                }
                            }
                        }
                    } catch (PDOException $e) {
                        print $e->getMessage();
                    }
                }
                if(isset($_REQUEST["error"])){
                    echo "<p style='color:red';>Datos incorrectos</p>";
                  }
                ?>
                <input type="submit" value="Modificar" name="cambiar">
            </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
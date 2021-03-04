<?php
require "conexionbbdd.php";
include "funciones.php";
$db = conectarbbdd();
session_start();
if (!isset($_SESSION["usuario"])) header("Location:login.php");
else {
    if(isset($_POST["si"])){
        if(preg_match("/^[A-Z]{3}-[0-9]{3}$/",$_POST["tarjeta"])){
        $buscariduser="SELECT nombre,id from usuarios where email=?";
        try{
        $resultado = $db->prepare($buscariduser);
        $resultado->execute([$_SESSION["usuario"]]);
        while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
        $iduser=$columnas["id"];
        $nombreuser=$columnas["nombre"];
        }
        $buscartarjeta="SELECT tarjeta from usuarios where id=?";
        $resultado = $db->prepare($buscartarjeta);
        $resultado->execute([$iduser]);
        while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
            if($columnas["tarjeta"]==""){
            $comprobartarjetarepetida="SELECT * from usuarios where tarjeta=?";
            $resultado2 = $db->prepare($comprobartarjetarepetida);
            $resultado2->execute([$_POST["tarjeta"]]);
            if($resultado2->rowCount()==0){
            $insertartarjeta="UPDATE usuarios set tarjeta=? where id=?";
            $resultado3 = $db->prepare($insertartarjeta);
            $resultado3->execute([$_POST["tarjeta"],$iduser]);}
            else header("Location:finalizarcompra.php?tarjetarepe='error'");
            }
        }
            
        $comprobartarjeta="SELECT * from usuarios where tarjeta=? and id=?";
        $resultadoc = $db->prepare($comprobartarjeta);
        $resultadoc->execute([$_POST["tarjeta"],$iduser]);
        if($resultadoc->rowCount()>0){
        $colorytalla="SELECT * from carrito where idusuario=?";
        $resultado = $db->prepare($colorytalla);
        $resultado->execute([$iduser]);
        while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
        $idzapa="SELECT id from zapatillas where modelo=?";
        $resultado2 = $db->prepare($idzapa);
        $resultado2->execute([$columnas["modelo"]]);
        while($columnas2=$resultado2->fetch(PDO::FETCH_ASSOC)) $idzapan=$columnas2["id"];
        $idtalla="SELECT t.id from tallas as t where t.talla=? and t.color=? and t.idzapatilla=?";
        $resultado3 = $db->prepare($idtalla);
        $resultado3->execute([$columnas["talla"],$columnas["color"],$idzapan]);
        while($columnas3=$resultado3->fetch(PDO::FETCH_ASSOC)){
        $comprobarstock="SELECT stock from tallas where id=?";
        $resultado4=$db->prepare($comprobarstock);
        $resultado4->execute([$columnas3["id"]]);
        while($columnas4=$resultado4->fetch(PDO::FETCH_ASSOC)) $stock=$columnas4["stock"];
        $cantidadzapas="SELECT count(*) as nzapas from carrito where idusuario=? and modelo=?";
        $resultado5 = $db->prepare($cantidadzapas);
        $resultado5->execute([$iduser,$columnas["modelo"]]);
        while($columnas5=$resultado5->fetch(PDO::FETCH_ASSOC)) $nzapas=$columnas5["nzapas"];
        if($stock>=$nzapas){
            $actualizarstock="UPDATE tallas SET stock=stock-1 where id=?";
            $resultado6=$db->prepare($actualizarstock);
            $resultado6->execute([$columnas3["id"]]);
            $modelo="SELECT modelo from zapatillas where id=?";
            $resultado7 = $db->prepare($modelo);
            $resultado7->execute([$idzapan]);
            while($columnas6=$resultado7->fetch(PDO::FETCH_ASSOC)) $modelozapa=$columnas["modelo"];
            $colorytalla2="SELECT talla,color from tallas where id=?";
            $resultado8 = $db->prepare($colorytalla2);
            $resultado8->execute([$columnas3["id"]]);
            while($columnas=$resultado8->fetch(PDO::FETCH_ASSOC)){ 
            $insertar="INSERT INTO ventas (fecha,modelo,talla,usuario,color) values(?,?,?,?,?)";
            $resultado2=$db->prepare($insertar);
            $resultado2->execute([date("d-m-Y H:i:s"),$modelozapa,$columnas["talla"],$nombreuser,$columnas["color"]]);}
        }else header("Location:finalizarcompra.php?errorstock=No hay stock del modelo ".$columnas["modelo"]."");
    }
        }
        print "Compra realizada con éxito<br><a class='enlace' href='index.php'>Seguir comprando</a>";
        $borrarcarrito="DELETE FROM carrito where idusuario=?";
        $resultado=$db->prepare($borrarcarrito);
        $resultado->execute([$iduser]);
    }else header("Location:finalizarcompra.php?errort='error'");
        }catch(PDOException $e){print $e->getMessage();}
    }else header("Location:finalizarcompra.php?error='error'");
    
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
            <form action="finalizarcompra.php" method="post">
        <?php
         $buscariduser="SELECT id from usuarios where email=?";
         $resultado = $db->prepare($buscariduser);
         $resultado->execute([$_SESSION["usuario"]]);
         while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
         $iduser=$columnas["id"];
         }
        $preciototal="SELECT sum(precio) as preciototal from carrito where idusuario=?";
        $resultado = $db->prepare($preciototal);
        $resultado->execute([$iduser]);
        while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)) $preciofinal=$columnas["preciototal"];
?>
    <p>Precio a pagar: <?php print $preciofinal;?> euros</p>
    <p>¿Desea finalizar la compra?</p>
    Número de tarjeta <input type="text" name="tarjeta" placeholder="AAA-111">
    <input type="submit" value="Si" name="si">
    <input type="submit" value="No" name="no">
    <?php
    if(isset($_REQUEST["error"])) print "<p style='color:red;'>Formato tarjeta incorrecto</p>";
    else if(isset($_REQUEST["tarjetarepe"])) print "<p style='color:red;'>Tarjeta ya existente</p>";
    else if(isset($_REQUEST["errort"])) print "<p style='color:red;'>Tarjeta no valida</p>";
    else if(isset($_REQUEST["errorstock"])) print "<p style='color:red;'>".$_REQUEST["errorstock"]."</p>"; 
    ?>
    </form>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
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
                        <?php menuAdmin(); ?>
                        <?php if ($_SESSION["usuario"] != "root@gmail.com") { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="vercarrito.php">Ver carrito</a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
            <div class='card-group col-12 col-md-12'>
            <div class='card m-2 tarjeta'>
            Usuarios que más compran
            <canvas id="myChart" width="400" height="400"></canvas>
            </div>
            <div class='card m-2 tarjeta'>
            Modelos más vendidos
            <canvas id="myChart2" width="400" height="400"></canvas>
            </div>
            <div class='card m-2 tarjeta'>
            Colores más vendidos
            <canvas id="myChart3" width="400" height="400"></canvas>
            </div>
            </div>
            <?php
                $consultapedidos="SELECT distinct modelo from ventas";
                $resultado=$db->prepare($consultapedidos);
                $resultado->execute();
                $modelosvendidos=array();
                $modelosnombre="";
                while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
                    $modelosnombre.="'".$columnas["modelo"]."',";
                    array_push($modelosvendidos,$columnas["modelo"]);
                }
                $numerozapasvendidas="";
                for($i=0;$i<count($modelosvendidos);$i++){
                    $consulta2="SELECT count(modelo) as numero from ventas where modelo=?";
                    $resultado=$db->prepare($consulta2);
                    $resultado->execute([$modelosvendidos[$i]]);
                    while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
                        $numerozapasvendidas.=$columnas["numero"].",";
                    }
                } 

                $consultausuarios="SELECT distinct usuario from ventas";
                $resultado=$db->prepare($consultausuarios);
                $resultado->execute();
                $usuarios=array();
                $usuariosnombre="";
                while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
                    $usuariosnombre.="'".$columnas["usuario"]."',";
                    array_push($usuarios,$columnas["usuario"]);
                }
                
                $pedidosusuarios="";
                for($i=0;$i<count($usuarios);$i++){
                    $consulta2="SELECT count(usuario) as numero from ventas where usuario=?";
                    $resultado=$db->prepare($consulta2);
                    $resultado->execute([$usuarios[$i]]);
                    while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
                        $pedidosusuarios.=$columnas["numero"].",";
                    }
                } 

                $consultacolores="SELECT distinct color from ventas";
                $resultado=$db->prepare($consultacolores);
                $resultado->execute();
                $colores=array();
                $coloresnombre="";
                while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){ 
                    $coloresnombre.="'".$columnas["color"]."',";
                    array_push($colores,$columnas["color"]);
                }
                $pedidoscolores="";
                for($i=0;$i<count($colores);$i++){
                    $consulta2="SELECT count(color) as numero from ventas where color=?";
                    $resultado=$db->prepare($consulta2);
                    $resultado->execute([$colores[$i]]);
                    while($columnas=$resultado->fetch(PDO::FETCH_ASSOC)){
                        $pedidoscolores.=$columnas["numero"].",";
                    }
                } 
            ?>

<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php print $usuariosnombre; ?>],
    datasets: [{
      label: 'Modelos más vendidos',
      data: [<?php print $pedidosusuarios; ?>],
      backgroundColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        "rgb(0, 255, 0,1)",
        "rgb(0, 255, 255,1)",
        'rgba(75, 192, 192, 1)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        "rgb(0, 255, 0,1)",
        "rgb(0, 255, 255,1)",
        'rgba(75, 192, 192, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
   	//cutoutPercentage: 40,
    responsive: false,

  }
});

var ctx = document.getElementById("myChart2");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php print $modelosnombre; ?>],
    datasets: [{
      label: 'Modelos más vendidos',
      data: [<?php print $numerozapasvendidas; ?>],
      backgroundColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        "rgb(0, 255, 0,1)",
        "rgb(0, 255, 255,1)",
        'rgba(75, 192, 192, 1)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        "rgb(0, 255, 0,1)",
        "rgb(0, 255, 255,1)",
        'rgba(75, 192, 192, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
   	//cutoutPercentage: 40,
    responsive: false,

  }
});


var ctx = document.getElementById("myChart3");
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: [<?php print $coloresnombre; ?>],
    datasets: [{
      label: 'Modelos más vendidos',
      data: [<?php print $pedidoscolores; ?>],
      backgroundColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        "rgb(0, 255, 0,1)",
        "rgb(0, 255, 255,1)",
        'rgba(75, 192, 192, 1)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        "rgb(0, 255, 0,1)",
        "rgb(0, 255, 255,1)",
        'rgba(75, 192, 192, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
   	//cutoutPercentage: 40,
    responsive: false,

  }
});


</script>
    </body>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<?php
}
?>

    </html>
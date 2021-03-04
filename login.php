<?php
  include "funciones.php";
  require "conexionbbdd.php";
  $db=conectarbbdd();
if(isset($_POST["iniciar"])){
    $passwordcodificada=hash("md5",$_POST["password"]);
    $consulta="SELECT * from usuarios where email=? and password=?";
    $resultado=$db->prepare($consulta);
    $resultado->execute([$_POST["correo"],$passwordcodificada]);
    if($resultado->rowCount()>0){
      session_start();
      $_SESSION["usuario"]=$_POST["correo"];
      header("Location:index.php");
    }
    else header("Location:login.php?error='error'");

}else{

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/login.css">

    <title>ZapasArroyo</title>
  </head>
  <body>
  

  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('images/jordan.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <h3>Bienvenido a <strong>ZapasArroyo</strong></h3>
            <p class="mb-4">Debe de iniciar sesión con su correo y contraseña para poder comprar en nuestra tienda</p>
            <form action="login.php" method="post">
              <div class="form-group first">
                <label for="username">Correo electrónico</label>
                <input type="text" class="form-control" name="correo" placeholder="tu-email@gmail.com" id="username">
              </div>
              <div class="form-group last mb-3">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" name="password" placeholder="Tu contraseña" id="password">
              </div>
              <?php
                if(isset($_REQUEST["error"])){
                  echo "<p style='color:red';>Datos incorrectos</p>";
                }
              ?>
            <a href="registrar.php">Registrarse</a>
              <input type="submit" value="Iniciar sesion" name="iniciar" class="btn btn-block btn-primary">

            </form>

          </div>
        </div>
      </div>
    </div>

    
  </div>
    
    

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
  <?php
}
  ?>
</html>
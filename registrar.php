<?php
if(isset($_POST["iniciar"])){
  require "conexionbbdd.php";
    if(preg_match("/^[a-zA-Z0-9]{1,15}@gmail.com$/",$_POST["correo"]) && strlen($_POST["password"])>8 && preg_match("/^([a-zA-Z0-9]{3,}\s?){1,3}$/",$_POST["nombre"])){
    $passwordcod=hash("md5",$_POST["password"]);
    $db=conectarbbdd();
    $comprobarcorreorepetido="SELECT * from usuarios where email=?";
    $resultado=$db->prepare($comprobarcorreorepetido);
    $resultado->execute([$_POST["correo"]]);
    if($resultado->rowCount()==0){
    $consulta="INSERT INTO usuarios(nombre,password,email) values(?,?,?)";
    $resultado2=$db->prepare($consulta);
    $resultado2->execute([$_POST["nombre"],$passwordcod,$_POST["correo"]]);
    if($resultado2) header("Location:login.php");
    else header("Location:login.php?error='error'");

  }else header("Location:registrar.php?errorrepetido='error'");
} else header("Location:registrar.php?error='error'");

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
            <form action="registrar.php" method="post">
            <div class="form-group first">
                <label for="username">Nombre de usuario</label>
                <input type="text" class="form-control" name="nombre" placeholder="tu-usuario" id="username">
              </div>
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
                else if(isset($_REQUEST["errorrepetido"])){
                  echo "<p style='color:red';>Usuario ya existente</p>";
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
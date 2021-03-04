<?php
function menuAdmin(){
    if ($_SESSION["usuario"] == "root@gmail.com") {
        print "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          Zapatillas </a><div class='dropdown-menu' aria-labelledby='navbarDropdown'>
          <a class='dropdown-item' href='anhadirzapatilla.php'>Añadir</a>
          <a class='dropdown-item' href='eliminarzapatillas.php'>Eliminar</a>
          <a class='dropdown-item' href='zapatillaseliminadas.php'>Ver zapatillas eliminadas</a>
        </div>
      </li>";

        print "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          Usuarios </a><div class='dropdown-menu' aria-labelledby='navbarDropdown'>
          <a class='dropdown-item' href='eliminarusuario.php'>Eliminar</a>
          <a class='dropdown-item' href='modificarusuario.php'>Modificar</a>
          <a class='dropdown-item' href='usuarioseliminados.php'>Ver usuarios eliminados</a>
        </div>
      </li>";

        print "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
          Tallas </a><div class='dropdown-menu' aria-labelledby='navbarDropdown'>
          <a class='dropdown-item' href='anhadirtalla.php'>Añadir</a>
          <a class='dropdown-item' href='eliminartalla.php'>Eliminar</a>
          <a class='dropdown-item' href='modificartalla.php'>Modificar</a>
        </div>
      </li>";
      
      print "<li class='nav-item'><a class='nav-link' href='estadisticaspedidos.php'>Ver estadisticas pedidos</a></li>"
      ;
    }
}

function mostrarZapasIndex($consulta){
  $db = conectarbbdd();
  $consultaColumnas = "SHOW COLUMNS FROM zapatillas";
  $arrayColumnas = array();
  try {
    $resultado = $db->prepare($consultaColumnas);
    $resultado->execute();
    while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
        if ($columnas["Field"] != "idcategoria" && $columnas["Field"] != "id" && $columnas["Field"] != "imagen") array_push($arrayColumnas, $columnas["Field"]);
    }
    $resultado = $db->prepare($consulta);
    $resultado->execute();
    $cont = 0;
    $idzapa="";
    while ($columnas = $resultado->fetch(PDO::FETCH_ASSOC)) {
        if ($cont == 0) print "<div class='card-group col-12 col-md-12'>";
        elseif ($cont == 3) {
            $cont = 0;
            print "</div><div class='card-group col-12 col-md-12'>";
        }
        $idzapa=$columnas["id"];

?>
        <div class='card m-2 tarjeta'>
            <?php if ($_SESSION["usuario"] == "root@gmail.com") print "<a href='modificarzapa.php?id=" . $columnas["id"] . "'>Editar zapatilla</a>"; ?>
            <img class='card-img-top fototarjeta' src='./images/zapatillas/<?php print $columnas["imagen"] ?>' alt='Card image cap'>
            <div class='card-body'>
                <img class="marca" src="./images/marcas/<?php print $columnas["imagenmarca"] ?>">
                <?php
                for ($i = 0; $i < count($arrayColumnas); $i++) {
                    if ($arrayColumnas[$i] == "precio") print "<p class='card-text colorp'>" . $arrayColumnas[$i] . ": " . $columnas[$arrayColumnas[$i]] . " euros</p>";
                    else print "<p class='card-text colorp'>" . $arrayColumnas[$i] . ": " . $columnas[$arrayColumnas[$i]] . " </p>";
                }
                ?>
            </div>
    <?php
        $consultatalla = "SELECT talla,stock from tallas where idzapatilla=? and stock>0 order by talla asc";
        $resultadotallas = $db->prepare($consultatalla);
        $resultadotallas->execute([$columnas["id"]]);
        if ($resultadotallas->rowCount() > 0) {
            print "Tallas: <select id='talla'>";
            while ($columnastallas = $resultadotallas->fetch(PDO::FETCH_ASSOC)) {
                print "<option>" . $columnastallas["talla"] . "</option>";
            }
            print "</select>";
        if ($_SESSION["usuario"] != "root@gmail.com") print "<a class='' href='carrito.php?id=" . $columnas["id"] ."'>Añadir al carrito</a>";
      } else print "<p class='card-text colorp'>No hay tallas</p>";
      $cont++;
        print "</div>";
    }
} catch (PDOException $e) {
    print $e->getMessage();
}
}

function comprobarTablaVacia($tabla){
  $db = conectarbbdd();
  $consulta="SELECT * from ".$tabla;
  $resultado=$db->prepare($consulta);
  $resultado->execute();
  return $resultado->rowCount();
}

?>
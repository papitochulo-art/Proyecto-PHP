<?php
function conectarbbdd(){
    try{
    $conexion=new PDO("mysql:host=localhost;dbname=proyectophp","root","");
    $conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    return $conexion;
    }catch(PDOException $e){
        print "Error al conectar con la bbdd";
        print "Error: ".$e->getMessage();
    }
}


?>
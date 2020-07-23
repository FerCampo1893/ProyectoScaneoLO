<?php
include 'conexion.php';
$usuLogin=$_POST['usuario'];
$usuClave=$_POST['password'];

//echo $usuLogin;
//echo $usuClave;
//$usuLogin="admin";
//$usuClave="123";

$sentencia=$conexion->prepare("SELECT * FROM secuser WHERE usuLogin=? AND usuClave=?");
$sentencia->bind_param('ss',$usuLogin,$usuClave);
$sentencia->execute();

$resultado = $sentencia->get_result();
if ($fila = $resultado->fetch_assoc()) {
         echo json_encode($fila,JSON_UNESCAPED_UNICODE);     
}
$sentencia->close();
$conexion->close();
?>
<?php
include 'conexion.php';
$usuLogin=$_POST['usuario'];
$usuClave=$_POST['password'];

echo $usuLogin;
echo $usuClave;
//$usuLogin="admin";
//$usuclave="123";


function get_result( $stmt ) {
    $arrResult = array();
    $stmt->store_result();
    for ( $i = 0; $i < $stmt->num_rows; $i++ ) {
        $metadata = $stmt->result_metadata();
        $arrParams = array();
        while ( $field = $metadata->fetch_field() ) {
            $arrParams[] = &$arrResult[ $i ][ $field->name ];
        }
        call_user_func_array( array( $stmt, 'bind_result' ), $arrParams );
        $stmt->fetch();
    }
    return $arrResult;
}


$sentencia=$conexion->prepare("SELECT * FROM secuser WHERE usuLogin=? AND usuClave=?");
$sentencia->bind_param('ss',$usuLogin,$usuclave);
$sentencia->execute();

$resultado = $sentencia->get_result();
if ($fila = $resultado->fetch_assoc()) {
         echo json_encode($fila,JSON_UNESCAPED_UNICODE);     
}
$sentencia->close();
$conexion->close();
?>
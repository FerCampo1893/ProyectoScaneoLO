<?php
include "config_scanealo.php";
include "utils_scanealo.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['prdcodigo']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * from producto  where prdcodigo=:prdcodigo");
      $sql->bindValue(':prdcodigo', $_GET['prdcodigo']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
 else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM producto`");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO producto
          (prdNombre, prdCodBarrasQr, prdPrecioCompra, prdPrecioVenta, prdMarca, prdEstado, prdExistencia, prdFechaCrea, usuario)
          VALUES
          (:prdNombre, :prdCodBarrasQr, :prdPrecioCompra, :prdPrecioVenta, :prdMarca, :prdEstado, :prdExistencia, :prdFechaCrea, :usuario)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['prdcodigo'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$codigo = $_GET['prdCodigo'];
  $statement = $dbConn->prepare("DELETE FROM producto where prdcodigo=:prdcodigo");
  $statement->bindValue(':prdcodigo', $codigo);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postCodigo = $input['prdCodigo'];
    $fields = getParams($input);

    $sql = "
          UPDATE producto
          SET $fields
          WHERE prdcodigo='$postCodigo'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");

?>

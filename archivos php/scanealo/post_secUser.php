<?php
include "config_scanealo.php";
include "utils_scanealo.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['usuCodigo']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * from secuser where usuCodigo=:usuCodigo");
      $sql->bindValue(':usuCodigo', $_GET['usuCodigo']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
 else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM secuser`");
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
    $sql = "INSERT INTO secuser
          (usuLogin,usuClave,usuNombre,usuApellido,usuEmail,usuEstado,usuFechaCrea)
          VALUES
          (:usuLogin, :usuClave, :usuNombre, :usuApellido, :usuEmail, :usuEstado, :usuFechaCrea)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['usuCodigo'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$codigo = $_GET['usuCodigo'];
  $statement = $dbConn->prepare("DELETE FROM secuser where usuCodigo=:usuCodigo");
  $statement->bindValue(':usuCodigo', $codigo);
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
          UPDATE secuser
          SET $fields
          WHERE usuCodigo='$postCodigo'
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

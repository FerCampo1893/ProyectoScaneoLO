<?php
include "config_scanealo.php";
include "utils_scanealo.php";

$dbConn =  connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['ajunumero']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * from invajuste where ajuNumero=:ajunumero");
      $sql->bindValue(':ajunumero', $_GET['ajunumero']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
	  }
 else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM invajuste`");
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
    $sql = "INSERT INTO invAjuste
          (ajuFecha, ajuTipo, ajuEstado, usuario,equipo)
          VALUES
          (:ajuFecha, :ajuTipo, :ajuEstado, :usuario, :equipo)";
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['ajunumero'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
	 }
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
	$codigo = $_GET['ajuNumero'];
  $statement = $dbConn->prepare("DELETE FROM  invAjuste where ajuNumero=:ajucodigo");
  $statement->bindValue(':ajuNumero', $codigo);
  $statement->execute();
	header("HTTP/1.1 200 OK");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postCodigo = $input['ajuNumero'];
    $fields = getParams($input);

    $sql = "
          UPDATE invAjuste
          SET $fields
          WHERE ajuNumero='$postCodigo'
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

<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Allow: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}
//GET ALL
$app->get('/tipos', function(Request $request, Response $response){
    $sql = "SELECT * FROM TIPOS";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $tipos = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($tipos);
      }else {
        echo json_encode("No existen tipos en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

// GET Recuperar usuario por ID 
$app->get('/tipos/{id}', function(Request $request, Response $response){
  $id_tipo = $request->getAttribute('id');
  $sql = "SELECT * FROM TIPOS WHERE ID = $id_tipo";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $tipo = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($tipo);
    }else {
      echo json_encode("No existen tipos en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// POST Crear nuevo usuario
$app->post('/tipos', function(Request $request, Response $response){
    $nombre = $request->getParam('nombre');
  
  $sql = "INSERT INTO TIPOS (NOMBRE) VALUES 
          (:nombre)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->execute();
    echo json_encode("Nuevo espacio guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT Modificar usuario
$app->put('/tipos/modificar/{id}', function(Request $request, Response $response){
   $id_tipo = $request->getAttribute('id');
   $nombre = $request->getParam('nombre');
  
  $sql = "UPDATE TIPOS SET
	  NOMBRE = :nombre
        WHERE id = $id_tipo";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->execute();
    echo json_encode("Espacio modificado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 
// DELETE borar cliente 
$app->delete('/tipos/delete/{id}', function(Request $request, Response $response){
   $id_tipo = $request->getAttribute('id');
   $sql = "DELETE FROM TIPOS WHERE ID = $id_tipo";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Tipo eliminado.");  
    }else {
      echo json_encode("No existe tipo con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->run();
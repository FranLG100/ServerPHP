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
$app->get('/centros', function(Request $request, Response $response){
    $sql = "SELECT * FROM CENTROS";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($clientes);
      }else {
        echo json_encode("No existen centros en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

// GET Recuperar usuario por ID 
$app->get('/centros/{id}', function(Request $request, Response $response){
  $id_centro = $request->getAttribute('id');
  $sql = "SELECT * FROM CENTROS WHERE ID = $id_centro";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $centro = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($centro);
    }else {
      echo json_encode("No existen centros en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// POST Crear nuevo usuario
$app->post('/centros', function(Request $request, Response $response){
    $cif = $request->getParam('cif');
	$nombre = $request->getParam('nombre');
    $telefono = $request->getParam('telefono');
    $email = $request->getParam('email');
    $direccion = $request->getParam('direccion');
  
  $sql = "INSERT INTO CENTROS (CIF,NOMBRE, TLF, EMAIL, DIRECCION, ACTIVO) VALUES 
          (:cif, :nombre, :telefono, :email, :direccion, 1)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
	$resultado->bindParam(':cif', $cif);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':telefono', $telefono);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->execute();
    echo json_encode("Nuevo centro guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT Modificar usuario
$app->put('/centros/modificar/{id}', function(Request $request, Response $response){
   $id_centro = $request->getAttribute('id');
	$cif = $request->getAttribute('cif');
   $nombre = $request->getParam('nombre');
   $telefono = $request->getParam('telefono');
   $email = $request->getParam('email');
   $direccion = $request->getParam('direccion');
   $activo = $request->getParam('activo'); 
  
  $sql = "UPDATE CENTROS SET
          CIF =:cif,
		  NOMBRE = :nombre,
          TLF = :telefono,
          EMAIL = :email,
          DIRECCION = :direccion,
          ACTIVO = :activo
        WHERE id = $id_centro";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
	  $resultado->bindParam(':cif', $cif);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':telefono', $telefono);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->bindParam(':activo', $activo);
    $resultado->execute();
    echo json_encode("Centro modificado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 
// DELETE borar cliente 
$app->delete('/centros/delete/{id}', function(Request $request, Response $response){
   $id_centro = $request->getAttribute('id');
   $sql = "DELETE FROM CENTROS WHERE ID = $id_centro";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Centro eliminado.");  
    }else {
      echo json_encode("No existe centro con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->run();
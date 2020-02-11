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
$app->get('/historicos', function(Request $request, Response $response){
    $sql = "SELECT * FROM HISTORICOS";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $reservas = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($reservas);
      }else {
        echo json_encode("No existen reservas en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

// GET Recuperar reserva por ID 
$app->get('/historicos/{id}', function(Request $request, Response $response){
  $id_reserva = $request->getAttribute('id');
  $sql = "SELECT * FROM HISTORICOS WHERE ID = $id_reserva";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $reserva = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($reserva);
    }else {
      echo json_encode("No existen reservas en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// POST Crear nueva reserva
$app->post('/historicos', function(Request $request, Response $response){
    $usuario = $request->getParam('usuario');
    $espacio = $request->getParam('espacio');
    $fecha = $request->getParam('fecha');
    $hora = $request->getParam('hora');
  
  $sql = "INSERT INTO HISTORICOS (USUARIO,ESPACIO,FECHA,HORA) VALUES 
          (:usuario,:espacio,:fecha,:hora)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':usuario', $usuario);
    $resultado->bindParam(':espacio', $espacio);
    $resultado->bindParam(':fecha', $fecha);
    $resultado->bindParam(':hora', $hora);
    $resultado->execute();
    echo json_encode("Nueva reserva guardada.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


// DELETE borrar cliente 
$app->delete('/historicos/delete/{id}', function(Request $request, Response $response){
   $id_reserva = $request->getAttribute('id');
   $sql = "DELETE FROM HISTORICOS WHERE ID = $id_reserva";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Reserva eliminada.");  
    }else {
      echo json_encode("No existe reserva con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->run();
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
$app->get('/reservas', function(Request $request, Response $response){
    $sql = "SELECT * FROM RESERVAS ORDER BY ID DESC";
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


//GET ALL
$app->get('/festivos', function(Request $request, Response $response){
    $sql = "SELECT * FROM FESTIVOS";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $reservas = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($reservas);
      }else {
        echo json_encode("No existen festivos en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  });

//GET ALL
$app->get('/reservasall', function(Request $request, Response $response){
    $sql = 'SELECT R.ID AS "ID", U.ID AS "ID_USUARIO", U.DNI AS "DNI", CONCAT(U.NOMBRE," ",U.P_APELLIDO," ",U.S_APELLIDO) AS "NOMBRE", E.ID AS "ID_ESPACIO", E.NOMBRE AS "ESPACIO", R.FECHA AS "FECHA", R.HORA AS "HORA"  
    FROM USUARIOS U JOIN RESERVAS R ON U.ID=R.USUARIO JOIN ESPACIOS E ON E.ID=R.ESPACIO ORDER BY ID DESC';
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

//Todos los datos de los festivos
$app->get('/festivosall', function(Request $request, Response $response){
    $sql = 'SELECT R.ID AS "ID", E.ID AS "ID_ESPACIO", E.NOMBRE AS "ESPACIO", R.FECHA AS "FECHA", R.HORA AS "HORA"  
    FROM FESTIVOS R JOIN ESPACIOS E ON E.ID=R.ESPACIO';
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $reservas = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($reservas);
      }else {
        echo json_encode("No existen festivos en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  });

// GET Recuperar reserva por ID 
$app->get('/reservas/{id}', function(Request $request, Response $response){
  $id_reserva = $request->getAttribute('id');
  $sql = "SELECT * FROM RESERVAS WHERE ID = $id_reserva";
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
$app->post('/reservas', function(Request $request, Response $response){
    $usuario = $request->getParam('usuario');
    $espacio = $request->getParam('espacio');
    $fecha = $request->getParam('fecha');
    $hora = $request->getParam('hora');
  
  $sql = "INSERT INTO RESERVAS (USUARIO,ESPACIO,FECHA,HORA) VALUES 
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


// POST Crear nueva reserva festiva
$app->post('/festivos', function(Request $request, Response $response){
    $espacio = $request->getParam('espacio');
    $fecha = $request->getParam('fecha');
    $hora = $request->getParam('hora');
  
  $sql = "INSERT INTO FESTIVOS (ESPACIO,FECHA,HORA) VALUES 
          (:espacio,:fecha,:hora)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':espacio', $espacio);
    $resultado->bindParam(':fecha', $fecha);
    $resultado->bindParam(':hora', $hora);
    $resultado->execute();
    echo json_encode("Nuevo festivo guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// GET Recuperar reserva por ID 
$app->get('/reservasall/{id}', function(Request $request, Response $response){
  $id_reserva = $request->getAttribute('id');
  $sql = 'SELECT R.ID AS "ID", U.ID AS "ID_USUARIO", U.DNI AS "DNI", CONCAT(U.NOMBRE," ",U.P_APELLIDO," ",U.S_APELLIDO) AS "NOMBRE", E.ID AS "ID_ESPACIO", E.NOMBRE AS "ESPACIO", R.FECHA AS "FECHA", R.HORA AS "HORA"  
    FROM USUARIOS U JOIN RESERVAS R ON U.ID=R.USUARIO JOIN ESPACIOS E ON E.ID=R.ESPACIO WHERE R.ID=' . $id_reserva;
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

// PUT Modificar reserva
$app->put('/reservas/modificar/{id}', function(Request $request, Response $response){
   $id_reserva = $request->getAttribute('id');
   $usuario = $request->getParam('usuario');
    $espacio = $request->getParam('espacio');
    $fecha = $request->getParam('fecha');
    $hora = $request->getParam('hora');
  
  $sql = "UPDATE RESERVAS SET
	  USUARIO = :usuario,
          ESPACIO = :espacio,
          FECHA = :fecha,
	  HORA = :hora 
        WHERE id = $id_reserva";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':usuario', $usuario);
    $resultado->bindParam(':espacio', $espacio);
    $resultado->bindParam(':fecha', $fecha);
    $resultado->bindParam(':hora', $hora);
    $resultado->execute();
    echo json_encode("Reserva modificada.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 


// PUT Modificar festivo
$app->put('/festivos/modificar/{id}', function(Request $request, Response $response){
   $id_festivo = $request->getAttribute('id');
    $espacio = $request->getParam('espacio');
    $fecha = $request->getParam('fecha');
    $hora = $request->getParam('hora');
  
  $sql = "UPDATE FESTIVOS SET
          ESPACIO = :espacio,
          FECHA = :fecha,
	  HORA = :hora 
        WHERE id = $id_reserva";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':espacio', $espacio);
    $resultado->bindParam(':fecha', $fecha);
    $resultado->bindParam(':hora', $hora);
    $resultado->execute();
    echo json_encode("Festivo modificada.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// DELETE borrar cliente 
$app->delete('/reservas/delete/{id}', function(Request $request, Response $response){
   $id_reserva = $request->getAttribute('id');
   $sql = "DELETE FROM RESERVAS WHERE ID = $id_reserva";
     
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

// DELETE borrar festivo
$app->delete('/festivos/delete/{id}', function(Request $request, Response $response){
   $id_reserva = $request->getAttribute('id');
   $sql = "DELETE FROM FESTIVOS WHERE ID = $id_reserva";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Festivo eliminada.");  
    }else {
      echo json_encode("No existe festivo con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

$app->run();
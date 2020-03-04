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
$app->get('/espacios', function(Request $request, Response $response){
    $sql = "SELECT * FROM ESPACIOS WHERE ACTIVO=1";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $espacios = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($espacios);
      }else {
        echo json_encode("No existen espacios en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

//GET ALL
$app->get('/espaciosall', function(Request $request, Response $response){
    $sql = "SELECT E.ID AS 'ID', E.NOMBRE AS 'NOMBRE', E.PRECIO AS 'PRECIO', E.RECARGO AS 'RECARGO', T.ID AS 'ID_TIPO', T.NOMBRE AS 'TIPO', C.NOMBRE AS 'CENTRO' FROM ESPACIOS E JOIN TIPOS T ON E.TIPO=T.ID JOIN CENTROS C ON C.ID=E.CENTRO";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $espacios = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($espacios);
      }else {
        echo json_encode("No existen espacios en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 


// GET Recuperar usuario por ID 
$app->get('/espacios/{id}', function(Request $request, Response $response){
  $id_espacio = $request->getAttribute('id');
  $sql = "SELECT * FROM ESPACIOS WHERE ID = $id_espacio";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $espacio = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($espacio);
    }else {
      echo json_encode("No existen espacios en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// GET Recuperar usuario por ID 
$app->get('/reservas/{id}', function(Request $request, Response $response){
  $id_espacio = $request->getAttribute('id');
  $sql = "SELECT * FROM RESERVAS WHERE ESPACIO=$id_espacio";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $espacio = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($espacio);
    }else {
      echo json_encode("No existen espacios en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->get('/reservasall/{id}', function(Request $request, Response $response){
	$id_espacio = $request->getAttribute('id');
	$sql = "SELECT R.ID AS 'ID', CONCAT(U.NOMBRE,' ',U.P_APELLIDO,' ',U.S_APELLIDO) AS 'CLIENTE', E.NOMBRE AS 'ESPACIO', R.FECHA AS 'FECHA', R.HORA AS 'HORA' FROM USUARIOS U JOIN RESERVAS R ON R.USUARIO=U.ID JOIN ESPACIOS E ON E.ID=R.ESPACIO WHERE E.ID=$id_espacio";
	try{$db = new db();$db = $db->conectDB();
		$resultado = $db->query($sql);if ($resultado->rowCount() > 0){
			$espacio = $resultado->fetchAll(PDO::FETCH_OBJ);
			echo json_encode($espacio);
		}else {
			echo json_encode("No existen espacios en la BBDD con este ID.");
		}$resultado = null;$db = null;
	   }catch(PDOException $e){
		echo '{"error" : {"text":'.$e->getMessage().'}';
	}
});

// GET Recuperar usuario por ID 
$app->get('/espaciosall/{id}', function(Request $request, Response $response){
  $id_espacio = $request->getAttribute('id');
  $sql = "SELECT E.ID AS 'ID', E.NOMBRE AS 'NOMBRE', E.PRECIO AS 'PRECIO', E.RECARGO AS 'RECARGO', T.ID AS 'ID_TIPO', T.NOMBRE AS 'TIPO', C.NOMBRE AS 'CENTRO' FROM ESPACIOS E JOIN TIPOS T ON E.TIPO=T.ID JOIN CENTROS C ON C.ID=E.CENTRO WHERE E.ID=$id_espacio";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $espacio = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($espacio);
    }else {
      echo json_encode("No existen espacios en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 


// POST Crear nuevo usuario
$app->post('/espacios', function(Request $request, Response $response){
    $nombre = $request->getParam('nombre');
    $precio = $request->getParam('precio');
	$recargo = $request->getParam('recargo');
    $tipo = $request->getParam('tipo');
	$centro = $request->getParam('centro');
  
  $sql = "INSERT INTO ESPACIOS (NOMBRE, PRECIO, RECARGO, TIPO, CENTRO, ACTIVO) VALUES 
          (:nombre, :precio,:recargo, :tipo, :centro, 1)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':precio', $precio);
	  $resultado->bindParam(':recargo', $recargo);
    $resultado->bindParam(':tipo', $tipo);
	  $resultado->bindParam(':centro', $centro);
    $resultado->execute();
    echo json_encode("Nuevo espacio guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// PUT Modificar usuario
$app->put('/espacios/modificar/{id}', function(Request $request, Response $response){
   $id_espacio = $request->getAttribute('id');
   $nombre = $request->getParam('nombre');
   $precio = $request->getParam('precio');
	//$recargo = $request->getParam('recargo');
   $tipo = $request->getParam('tipo');
	//$centro = $request->getParam('centro');
	//$activo = $request->getParam('activo'); 
  
  $sql = "UPDATE ESPACIOS SET
	  NOMBRE = :nombre,
          PRECIO = :precio,
          TIPO = :tipo,
		  CENTRO = 1,
		  ACTIVO = 1
        WHERE id = $id_espacio";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':precio', $precio);
	  //$resultado->bindParam(':recargo', $recargo);
    $resultado->bindParam(':tipo', $tipo);
	  //$resultado->bindParam(':activo', $activo);
	  //$resultado->bindParam(':centro', $centro);
    $resultado->execute();
    echo json_encode("Espacio modificado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// PUT Modificar usuario
$app->put('/espacios/activar/{id}', function(Request $request, Response $response){
   $id_espacio = $request->getAttribute('id'); 
  
  $sql = "UPDATE ESPACIOS SET ACTIVO = 1 WHERE id = $id_espacio";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Espacio modificado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 
// DELETE borar cliente 
$app->delete('/espacios/delete/{id}', function(Request $request, Response $response){
   $id_espacio = $request->getAttribute('id');
   $sql = "UPDATE ESPACIOS SET ACTIVO=0 WHERE ID = $id_espacio";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Espacio eliminado.");  
    }else {
      echo json_encode("No existe espacio con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->run();
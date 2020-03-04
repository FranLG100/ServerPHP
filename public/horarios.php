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


// GET Recuperar usuario por ID 
$app->get('/horarios/{id}', function(Request $request, Response $response){
  $id_espacio = $request->getAttribute('id');
  $sql = "SELECT * FROM HORARIOS WHERE ESPACIO = $id_espacio";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $espacio = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($espacio);
    }else {
      echo json_encode("No existen espacios/horarios en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// POST Crear nuevo horario
$app->post('/horarios', function(Request $request, Response $response){
    $espacio = $request->getParam('espacio');
    $dias = $request->getParam('dias');
    $horamin = $request->getParam('horamin');
    $horamax = $request->getParam('horamax');
 
  $sql = "INSERT INTO HORARIOS (ESPACIO, DIA, HORA, DISPONIBLE) VALUES 
          (:espacio, :dia, :hora, 1)";

  try{
    $db = new db();
    $db = $db->conectDB();

for($i=0;$i<count($dias);$i++){
    for($j=$horamin;$j<=$horamax;$j++){
		$prueba = gmdate("H:i:s", $j*3600);
		
    	$resultado = $db->prepare($sql);
    	$resultado->bindParam(':espacio', $espacio);
    	$resultado->bindParam(':dia', $dias[$i]);
    	$resultado->bindParam(':hora', $prueba);
    	$resultado->execute();
    	echo json_encode("Nuevo horario guardado.");
	}
} 
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


// POST Crear nuevo horario
$app->post('/horarios/post', function(Request $request, Response $response){
	
	$espacio = $request->getParam('espacio');
    $dias = $request->getParam('dias');
    $horamin = $request->getParam('horamin');
    $horamax = $request->getParam('horamax');
 
  $sql = "INSERT INTO HORARIOS (ESPACIO, DIA, HORA, DISPONIBLE) VALUES 
          (:espacio, :dia, :hora, 1) ON DUPLICATE KEY UPDATE DISPONIBLE=1;";

  try{
    $db = new db();
    $db = $db->conectDB();

for($i=0;$i<count($dias);$i++){
    for($j=$horamin;$j<=$horamax;$j++){
		$prueba = gmdate("H:i:s", $j*3600);
		
    	$resultado = $db->prepare($sql);
    	$resultado->bindParam(':espacio', $espacio);
    	$resultado->bindParam(':dia', $dias[$i]);
    	$resultado->bindParam(':hora', $prueba);
    	$resultado->execute();
    	echo json_encode("Nuevo horario guardado.");
	}
} 
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
	
	//SEGUNDA PARTE
    $json = $request->getBody();
    $data = json_decode($json);
	
	$sql = "UPDATE HORARIOS SET DISPONIBLE=0 WHERE ESPACIO = :espacio AND HORA = :hora AND DIA = :dia";

	
	try{
    $db = new db();
    $db = $db->conectDB();
		
	for($i=0;$i<count($data->borrado);$i++){
		$var=$data->borrado[$i];
		echo $data->borrado[$i]->dia . "\n";
		for($j=0;$j<count($var->horas);$j++){
			$aux=$var->horas[$j];
			$prueba = gmdate("H:i:s", $aux*3600);
			
			$resultado = $db->prepare($sql);
			$resultado->bindParam(':espacio', $espacio);
			$resultado->bindParam(':dia', $data->borrado[$i]->dia);
			$resultado->bindParam(':hora', $prueba);
			$resultado->execute();
			//echo $var->horas[$j] . "\n";
		}
	}$resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
	
});

$app->post('/horarios/delete', function(Request $request, Response $response){
    $espacio = $request->getParam('espacio');
    $dia = $request->getParam('dia');
  
  $sql = "DELETE FROM HORARIOS WHERE ESPACIO=:espacio AND DIA=:dia";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':espacio', $espacio);
    $resultado->bindParam(':dia', $dia);
    $resultado->execute();
    echo json_encode("Nuevo espacio guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


$app->post('/horarios/habilitar', function(Request $request, Response $response){
    $espacio = $request->getParam('espacio');
    $dia = $request->getParam('dia');
	$hora = $request->getParam('hora');
  
  $sql = "UPDATE HORARIOS SET DISPONIBLE=1 WHERE ESPACIO=:espacio AND DIA=:dia AND HORA=:hora ";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
    $resultado->bindParam(':espacio', $espacio);
    $resultado->bindParam(':dia', $dia);
	$resultado->bindParam(':hora', $hora);
    $resultado->execute();
    echo json_encode("Espacio habilitado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

$app->run();
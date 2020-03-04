<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;
use \Firebase\JWT\JWT;

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;
//Probar lo e abajo, pero poniendo el puerto :8443
//header('Access-Control-Allow-Origin: https://reservas.rota.salesianas.com');
//header('Access-Control-Allow-Origin: http://localhost:3000');
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
//header("Access-Control-Allow-Origin: {$_SERVER['HTTP_REFERER']}");
//header("Access-Control-Allow-Origin: {$_SERVER['SERVER_NAME']}");
//header("Access-Control-Allow-Origin: {$_SERVER['SERVER_ADDR']}");
//header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
//header('Access-Control-Allow-Origin: *');
//header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
		header("Allow: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		//if($method == "OPTIONS") {
		//	die();
		//}

//LOGIN
session_start(['name'=>'SalesianasRota']);
//
//GET ALL
$app->get('/usuarios', function(Request $request, Response $response){
    $sql = "SELECT ID,DNI,NOMBRE,P_APELLIDO,S_APELLIDO,DIRECCION,EMAIL,TELEFONO,ADMIN,CENTRO,ACTIVO FROM USUARIOS";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($clientes) . $_SESSION['usuario'];
      }else {
        echo json_encode("No existen clientes en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

$app->get('/usuariosall', function(Request $request, Response $response){
    $sql = "SELECT U.ID,U.DNI,U.NOMBRE,U.P_APELLIDO,U.S_APELLIDO,U.DIRECCION,U.EMAIL,U.TELEFONO,U.ADMIN,C.NOMBRE AS 'CENTRO',U.ACTIVO FROM USUARIOS U JOIN CENTROS C ON U.CENTRO=C.ID";
    try{
      $db = new db();
      $db = $db->conectDB();
      $resultado = $db->query($sql);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll(PDO::FETCH_OBJ);
        echo json_encode($clientes) . $_SESSION['usuario'];
      }else {
        echo json_encode("No existen clientes en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
  }); 

// GET Recuperar usuario por ID 
$app->get('/usuarios/{id}', function(Request $request, Response $response){
  $id_cliente = $request->getAttribute('id');
  $sql = "SELECT ID,DNI,NOMBRE,P_APELLIDO,S_APELLIDO,DIRECCION,EMAIL,TELEFONO,ADMIN,CENTRO,ACTIVO FROM USUARIOS WHERE ID = $id_cliente";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($cliente);
    }else {
      echo json_encode("No existen usuario en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

// GET Recuperar usuario por ID 
$app->get('/usuariosall/{id}', function(Request $request, Response $response){
  $id_cliente = $request->getAttribute('id');
  $sql = "SELECT U.ID,U.DNI,U.NOMBRE,U.P_APELLIDO,U.S_APELLIDO,U.DIRECCION,U.EMAIL,U.TELEFONO,U.ADMIN,C.NOMBRE AS 'CENTRO',U.ACTIVO FROM USUARIOS U JOIN CENTROS C ON U.CENTRO=C.ID WHERE U.ID = $id_cliente";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($cliente);
    }else {
      echo json_encode("No existen usuario en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


// GET Recuperar usuario logeado
$app->get('/logged', function(Request $request, Response $response){
  $email=$_SESSION['usuario'];
  $sql = "SELECT * FROM USUARIOS WHERE EMAIL = '" . $email . "'";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($cliente);
    }else {
      echo json_encode("No esta correctamente logeado.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->get('/cookie', function(Request $request, Response $response){
  $email=$_COOKIE["cookem"];
	echo 'email: ' . $email;
  $sql = "SELECT * FROM USUARIOS WHERE EMAIL = '" . $email . "'";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($cliente);
    }else {
      echo json_encode("No esta correctamente logeado.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});  


$app->post('/usuarios/logged', function(Request $request, Response $response){
	session_start(['name'=>'SalesianasRota']);
	echo $_SESSION['usuario'] . 'es una prueba';
  $email=$_SESSION['usuario'];
  $sql = "SELECT * FROM USUARIOS WHERE EMAIL = '" . $email . "'";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($cliente) . "<br>";
    }else {
      echo json_encode("No esta correctamente logeado.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->post('/logout', function(Request $request, Response $response){
  session_destroy();
  setcookie('cookem', '', 0, '/');
  echo "Cepeda, has matado a la sesión :( ";
}); 

// POST Crear nuevo usuario
$app->post('/usuarios', function(Request $request, Response $response){
    $dni = $request->getParam('dni');
    $nombre = $request->getParam('nombre');
    $papellido = $request->getParam('papellido');
    $sapellido = $request->getParam('sapellido');
    $telefono = $request->getParam('telefono');
    $email = $request->getParam('email');
    $direccion = $request->getParam('direccion');
    $pass = $request->getParam('pass'); 
  
	#PARTE MAIL
	require_once "PHPMailer/PHPMailer.php";
	require_once "PHPMailer/SMTP.php";
	require_once "PHPMailer/Exception.php";
	
	$enviador = new PHPMailer();
	
	$enviador->IsSMTP();
	//$enviador->SMTPDebug = SMTP::DEBUG_SERVER;
	
	$enviador->Host="smtp.gmail.com";
	$enviador->SMTPAuth=true;
	$enviador->Username="fralg100@gmail.com";
	$enviador->Password="canoncien100";
	$enviador->Port=587;
	$enviador->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	//$enviador->SMTPSecure="tls";
	
	//$enviador->isHTML(true);
	$enviador->setFrom("fralg100@gmail.com","Francisco Lorente");
	$enviador->addAddress($email, $papellido);
	$enviador->Subject= "Mail de pruebas";
	$enviador->Body="Se ha registrado con exito en nuestra pagina de reservas.";
	
	if (!$enviador->send()) {
    	echo 'Mailer Error: '. $enviador->ErrorInfo;
	} else {
		echo 'Message sent!';
	}
	#FIN PARTE MAIL

  $pass_hash=password_hash($pass, PASSWORD_DEFAULT);
	
  $sql = "INSERT INTO USUARIOS (DNI,NOMBRE, P_APELLIDO, S_APELLIDO, TELEFONO, EMAIL, DIRECCION, PASS, ADMIN,CENTRO, ACTIVO) VALUES 
          (:dni, :nombre, :papellido, :sapellido, :telefono, :email, :direccion, :pass, 0, 0, 1)";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
	$resultado->bindParam(':dni', $dni);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':papellido', $papellido);
	$resultado->bindParam(':sapellido', $sapellido);
    $resultado->bindParam(':telefono', $telefono);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->bindParam(':pass', $pass_hash);
    $resultado->execute();
    echo json_encode("Nuevo usuario guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});



// POST Comprobar login 
$app->post('/usuarios/login', function(Request $request, Response $response){
  $id_cliente = $request->getAttribute('id');
	$email = $request->getParam('email'); 
	$pass = $request->getParam('pass'); 
  $sql = "SELECT PASS FROM USUARIOS WHERE EMAIL = '" . $email . "'";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      echo json_encode($cliente);
		if(password_verify($pass, $cliente[0]->PASS)){
	  		echo "Pass correcto";
//LOGIN
			$_SESSION['usuario']=$email;
			echo $_SESSION['usuario'] . ' m de SESION';
			//setcookie('cookem', $email,0,'/', NULL);
			setcookie('cookem', $email,0,'/');
			echo $_COOKIE['cookem'] . ' es el email de la cookie';
//
		}
		else
			echo "Pass INCORRECTO";
    }else {
      echo json_encode("No existen usuario en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

// PUT Modificar usuario
$app->put('/usuarios/modificar/{id}', function(Request $request, Response $response){
   $id_cliente = $request->getAttribute('id');
	$dni = $request->getParam('dni');
   $nombre = $request->getParam('nombre');
   $papellido = $request->getParam('papellido');
	$sapellido = $request->getParam('sapellido');
   $telefono = $request->getParam('telefono');
   $email = $request->getParam('email');
   $direccion = $request->getParam('direccion');
   $pass = $request->getParam('pass');
	//$centro = $request->getParam('centro');
	$activo = $request->getParam('activo');
	$admin = $request->getParam('admin'); 
  
	$pass_hash=password_hash($pass, PASSWORD_DEFAULT);
	
  $sql = "UPDATE USUARIOS SET
          DNI =:dni,
		  NOMBRE = :nombre,
          P_APELLIDO = :papellido,
		  S_APELLIDO = :sapellido,
          TELEFONO = :telefono,
          EMAIL = :email,
          DIRECCION = :direccion,
          PASS = :pass,
		  ADMIN = :admin,
		  CENTRO = 1,
		  ACTIVO = :activo
        WHERE id = $id_cliente";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
	  $resultado->bindParam(':dni', $dni);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':papellido', $papellido);
	  $resultado->bindParam(':sapellido', $sapellido);
    $resultado->bindParam(':telefono', $telefono);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->bindParam(':pass', $pass_hash);
	  $resultado->bindParam(':admin', $admin);
	  //$resultado->bindParam(':centro', $centro);
	  $resultado->bindParam(':activo', $activo);
    $resultado->execute();
    echo json_encode("Usuario modificado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 
// DELETE borar cliente 
$app->delete('/usuarios/delete/{id}', function(Request $request, Response $response){
   $id_cliente = $request->getAttribute('id');
   $sql = "DELETE FROM USUARIOS WHERE ID = $id_cliente";
     
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->prepare($sql);
     $resultado->execute();
    if ($resultado->rowCount() > 0) {
      echo json_encode("Usuario eliminado.");  
    }else {
      echo json_encode("No existe cliente con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
}); 

$app->post('/pruebas', function(Request $request, Response $response){
	require_once './php-jwt-master/src/JWT.php';
	 $time = time(); //Fecha y hora actual en segundos
        $key = "CepedaCalvo";
        $token = array(
            'iat' => $time, // Tiempo que inició el token
            'exp' => $time + (60 * 60), // Tiempo que expirará el token (+1 hora)                                            
			'Mensaje' =>'CepedaCalvo',//Informacion de usuario
        );
        $jwt = JWT::encode($token, $key);//Codificamos el Token
		$decoded=JWT::decode($jwt, $key, array('HS256'));
		print_r($jwt);
		echo '<br><br>';
        print_r($decoded);//Mostramos el Tocken Decodificado
}); 

$app->post('/token', function(Request $request, Response $response){
	$id_cliente = $request->getAttribute('id');
	$email = $request->getParam('email'); 
	$pass = $request->getParam('pass'); 
  $sql = "SELECT * FROM USUARIOS WHERE EMAIL = '" . $email . "'";
  try{
    $db = new db();
    $db = $db->conectDB();
    $resultado = $db->query($sql);
    if ($resultado->rowCount() > 0){
      $cliente = $resultado->fetchAll(PDO::FETCH_OBJ);
      //echo json_encode($cliente);
		if(password_verify($pass, $cliente[0]->PASS)){
	  		//echo "Pass correcto";
		//LOGIN//
			require_once './php-jwt-master/src/JWT.php';
	 		$time = time(); //Fecha y hora actual en segundos
        		$key = "CepedaCalvo";
        		/*$token = array(
            			'iat' => $time, // Tiempo que inició el token
            			'exp' => $time + (60 * 60), // Tiempo que expirará el token (+1 hora)
						'id' => $cliente[0]->ID,
						'dni' =>$cliente[0]->DNI,
						'email' => $cliente[0]->EMAIL,
						'pass' =>$cliente[0]->PASS,//Informacion de usuario
						'admin' => $cliente[0]->ADMIN
        		);*/
				$token=[
					'iat' => $time, // Tiempo que inició el token
            		'exp' => $time + (60 * 60), // Tiempo que expirará el token (+1 hora)
					'id' => $cliente[0]->ID,
					'dni' =>$cliente[0]->DNI,
					'email' => $cliente[0]->EMAIL,
					'pass' =>$cliente[0]->PASS,//Informacion de usuario
					'admin' => $cliente[0]->ADMIN,
					'centro' => $cliente[0]->CENTRO
				];
       			$jwt = JWT::encode($token, $key);//Codificamos el Token
			$decoded=JWT::decode($jwt, $key, array('HS256'));
			//print_r($jwt);
			$envio=['permiso'=>$jwt];
			echo json_encode($envio);
			//echo '<br><br>';
       	 	//print_r($decoded);//Mostramos el Tocken Decodificado
		//
		}
		else
			echo "Pass INCORRECTO";
    }else {
      echo json_encode("No existen usuario en la BBDD con este ID.");
    }
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

$app->run();
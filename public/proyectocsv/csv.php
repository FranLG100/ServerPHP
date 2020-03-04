<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require '../../src/config/db.php';

$app = new \Slim\App;

header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Allow: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}

$app->get('/csv', function(Request $req, Response $res) {
	$sql = "SELECT * FROM pruebascsv";
	$meta = "SHOW columns FROM pruebascsv";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'Fichero' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1] . ";" . $valor[2]);
				//echo $valor[0] . "," . $valor[1] . "," . $valor[2] . "\n";
			}
		  fclose($handle);
        //echo var_dump($clientes);
      }else {
        echo json_encode("No existen centros en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	//echo var_dump($upload);
	//echo var_dump($upload);
	$subida=$upload["csv"]->file;
	//$handle = fopen($fichero, 'w') or die('Cannot open file:  '.$my_file);
	//fwrite($handle, $data);
    echo $data;
	
	
  
  //$sql = "INSERT INTO CENTROS (CIF,NOMBRE, TLF, EMAIL, DIRECCION, ACTIVO) VALUES (:cif, :nombre, :telefono, :email, :direccion, 1)";
  try{
    $db = new db();
    $db = $db->conectDBCsv();
   /* $resultado = $db->prepare($sql);
	$resultado->bindParam(':cif', $cif);
    $resultado->bindParam(':nombre', $nombre);
    $resultado->bindParam(':telefono', $telefono);
    $resultado->bindParam(':email', $email);
    $resultado->bindParam(':direccion', $direccion);
    $resultado->execute();*/
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
    //$item1 = mysqli_real_escape_string($connect, $data[0]);  
    //$item2 = mysqli_real_escape_string($connect, $data[1]);
    //$item3 = mysqli_real_escape_string($connect, $data[2]);
    $sql = "INSERT into pruebascsv(id, nombre,apellido) values('$data[0]','$data[1]','$data[2]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nuevo centro guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


$app->get('/csv/classes', function(Request $req, Response $res) {
	$sql = "SELECT * FROM CLASES";
	$meta = "SHOW columns FROM CLASES";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'classes_' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1] . ";" . $valor[2] . ";" . $valor[3] . ";" . $valor[4] . ";" . $valor[5] . ";" . $valor[6] . ";" . $valor[7] . ";" . $valor[8] . ";" . $valor[9] . ";" . $valor[10] . ";" . $valor[11] . ";" . $valor[12] . ";" . $valor[13] . ";" . $valor[14] . ";" . $valor[15]);
			}
		  fclose($handle);
      }else {
        echo json_encode("No existen clases en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv/classes', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	
	$subida=$upload["csv"]->file;
  
    echo $data;
  
    try{
    $db = new db();
    $db = $db->conectDBCsv();
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
				
		
	   
    $sql = "INSERT into CLASES(class_id,class_number,course_id,instructor_id,instructor_id_2,instructor_id_3,instructor_id_4,instructor_id_5,instructor_id_6,instructor_id_7,instructor_id_8,instructor_id_9,instructor_id_10,instructor_id_11,instructor_id_12,location_id) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]','$data[10]','$data[11]','$data[12]','$data[13]','$data[14]','$data[15]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nueva clase guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


$app->get('/csv/courses', function(Request $req, Response $res) {
	$sql = "SELECT * FROM CURSOS";
	$meta = "SHOW columns FROM CURSOS";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'courses_' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1] . ";" . $valor[2] . ";" . $valor[3]);
			}
		  fclose($handle);
      }else {
        echo json_encode("No existen cursos en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv/courses', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	
	$subida=$upload["csv"]->file;
  
    echo $data;
  
    try{
    $db = new db();
    $db = $db->conectDBCsv();
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
    
    $sql = "INSERT into CURSOS(course_id,course_number,course_name,location_id) values('$data[0]','$data[1]','$data[2]','$data[3]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nuevo curso guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


$app->get('/csv/locations', function(Request $req, Response $res) {
	$sql = "SELECT * FROM LOCALIZACIONES";
	$meta = "SHOW columns FROM LOCALIZACIONES";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'locations_' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1]);
			}
		  fclose($handle);
      }else {
        echo json_encode("No existen localizaciones en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv/locations', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	
	$subida=$upload["csv"]->file;
  
    echo $data;
  
    try{
    $db = new db();
    $db = $db->conectDBCsv();
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
    
    $sql = "INSERT into LOCALIZACIONES(location_id,location_name) values('$data[0]','$data[1]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nuevo curso guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});



$app->get('/csv/rosters', function(Request $req, Response $res) {
	$sql = "SELECT * FROM LISTA";
	$meta = "SHOW columns FROM LISTA";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'rosters_' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1] . ";" . $valor[2]);
			}
		  fclose($handle);
      }else {
        echo json_encode("No existen lista en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv/rosters', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	
	$subida=$upload["csv"]->file;
  
    echo $request->getParam('id');
	echo 'Hola';
  
    try{
    $db = new db();
    $db = $db->conectDBCsv();
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
    
	if($data[1]==""){
		echo "Id de clase vacío";
		$data[1]=$request->getParam('id');
	}
    $sql = "INSERT into LISTA(roster_id,class_id,student_id) values('$data[0]','$data[1]','$data[2]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nueva lista guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});


$app->get('/csv/staff', function(Request $req, Response $res) {
	$sql = "SELECT * FROM STAFF";
	$meta = "SHOW columns FROM STAFF";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'staff_' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1] . ";" . $valor[2] . ";" . $valor[3] . ";" . $valor[4] . ";" . $valor[5] . ";" . $valor[6] . ";" . $valor[7]);
			}
		  fclose($handle);
      }else {
        echo json_encode("No existe staff en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv/staff', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	
	$subida=$upload["csv"]->file;
  
    echo $data;
  
    try{
    $db = new db();
    $db = $db->conectDBCsv();
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
    
    $sql = "INSERT into STAFF(person_id,person_number,first_name,middle_name,last_name,email_address,sis_username,location_id) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nuevo staff guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});



$app->get('/csv/students', function(Request $req, Response $res) {
	$sql = "SELECT * FROM ESTUDIANTES";
	$meta = "SHOW columns FROM ESTUDIANTES";
    try{
      $db = new db();
      $db = $db->conectDBCsv();
	  $metadatos= $db->query($meta);
      $resultado = $db->query($sql);
		$datos=$metadatos->fetchAll();
		//echo $datos[0];
		$file = 'students_' . uniqid() . '.csv';
		$handle = fopen($file, 'w') or die('Cannot open file:  '.$my_file);
		$i=0;
		foreach ($datos as &$val) {
			if($i==0)
				//echo $val[0];
			  	fwrite($handle, $val[0]);
			else	
				//echo "," . $val[0];
				fwrite($handle, ";" . $val[0]);
			$i++;
			}
		
		//echo var_dump($datos);
      if ($resultado->rowCount() > 0){
        $clientes = $resultado->fetchAll();
		  
	//
		  foreach ($clientes as &$valor) {
			  	fwrite($handle, "\n" . $valor[0] . ";" . $valor[1] . ";" . $valor[2] . ";" . $valor[3] . ";" . $valor[4] . ";" . $valor[5] . ";" . $valor[6] . ";" . $valor[7] . ";" . $valor[8] . ";" . $valor[9]);
			}
		  fclose($handle);
      }else {
        echo json_encode("No existen clases en la BBDD.");
      }
      $resultado = null;
      $db = null;
    }catch(PDOException $e){
      echo '{"error" : {"text":'.$e->getMessage().'}';
    }
	////////////////////
    
    $response = $res->withHeader('Content-Description', 'File Transfer')
   ->withHeader('Content-Type', 'application/octet-stream')
   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
   ->withHeader('Expires', '0')
   ->withHeader('Cache-Control', 'must-revalidate')
   ->withHeader('Pragma', 'public')
   ->withHeader('Content-Length', filesize($file));

readfile($file);
unlink($file);
return $response;

});

// POST Crear nuevo usuario
$app->post('/csv/students', function(Request $request, Response $response){
	$data=$request->getBody()->getContents();
	$fichero = 'file.csv';
	$upload=$request->getUploadedFiles();
	
	$subida=$upload["csv"]->file;
  
    echo $data;
  
    try{
    $db = new db();
    $db = $db->conectDBCsv();
	 
 $handle = fopen($subida, "r");
	  echo 'prueba2';
	  fgetcsv($handle);
   while($data = fgetcsv($handle,0,";"))
   {
	   echo 'prueba' . $data[0];
    
    $sql = "INSERT into ESTUDIANTES(person_id,person_number,first_name,middle_name,last_name,grade_level,email_address,sis_username,password_policy,location_id) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]','$data[7]','$data[8]','$data[9]')";
     $resultado = $db->prepare($sql);
    $resultado->execute();
    echo json_encode("Nuevo fichero guardado.");  
    $resultado = null;
   }
   fclose($handle);
    echo json_encode("Nueva clase guardado.");  
    $resultado = null;
    $db = null;
  }catch(PDOException $e){
    echo '{"error" : {"text":'.$e->getMessage().'}';
  }
});

$app->run();
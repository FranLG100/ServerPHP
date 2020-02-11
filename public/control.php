<?php  
$connect = mysqli_connect("127.0.0.1", "dam_dam_admin", "**LorenPeda123**", "dam_reservas");
if(isset($_POST["submit"]))
{
 if($_FILES['file']['name'])
 {
  $filename = explode(".", $_FILES['file']['name']);
  if($filename[1] == 'csv')
  {
   $handle = fopen($_FILES['file']['tmp_name'], "r");
   while($data = fgetcsv($handle,0,","))
   {
    //$item1 = mysqli_real_escape_string($connect, $data[0]);  
    //$item2 = mysqli_real_escape_string($connect, $data[1]);
    //$item3 = mysqli_real_escape_string($connect, $data[2]);
    $query = "INSERT into pruebascsv(id, nombre,apellido) values('$data[0]','$data[1]','$data[2]')";
    mysqli_query($connect, $query);
   }
   fclose($handle);
   echo "<script>alert('Import done');</script>";
  }
 }
}
?>  
<!DOCTYPE html>  
<html>  
 <head>  
  <title>Webslesson Tutorial</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 </head>  
 <body>  
  <h3 align="center">How to Import Data from CSV File to Mysql using PHP</h3><br />
  <form method="post" enctype="multipart/form-data">
   <div align="center">  
    <label>Select CSV File:</label>
    <input type="file" name="file" />
    <br />
    <input type="submit" name="submit" value="Import" class="btn btn-info" />
   </div>
  </form>
 </body>  
</html>
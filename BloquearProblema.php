

<?php 
//incluir conexion
include_once("config.php");
include_once("includes/db_con.php");
//include '../global_seguridad/verificar_sesion.php';

//Se recuperan las variables GET
$g_id = $_GET['id'];
$g_estado = $_GET['estado'];
if($g_estado==1)		
	$actualizar_estado = "UPDATE problem SET Publico='SI' WHERE problem_id= '$g_id'";
else
	$actualizar_estado = "UPDATE problem SET Publico='NO' WHERE problem_id= '$g_id'";
//Se ejecuta la sentencia en la clase
$result = mysqli_query($enlace, $actualizar_estado);


 ?>
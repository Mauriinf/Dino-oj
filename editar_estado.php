<?php 
//incluir conexion
include_once("config.php");
include_once("includes/db_con.php");
//include '../global_seguridad/verificar_sesion.php';

//Se recuperan las variables GET
$g_id = $_GET['id'];
$g_estado = $_GET['estado'];

//Se crea la consulta
$actualizar_estado= "UPDATE users SET Estado ='".$g_estado."' WHERE user_id ='".$g_id."'";

//Se ejecuta la sentencia en la clase
$result = mysqli_query($enlace, $actualizar_estado);


 ?>
<?php

session_start();

function encriptar($cadena){
	    $key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    alert($encriptar);
	    return $encrypted;
}
function desencriptar($cadena){
    $key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
    $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    return $decrypted;
}




//recolectar datos
$usuario = addslashes( $_GET["user"] );

$pass = desencriptar($_GET["pswd"]);
if(($usuario != $_GET["user"])){
	echo "{\"sucess\": false, \"badguy\": true, \"msg\": \"Portate bien <b>". $_SERVER['REMOTE_ADDR'] ."</b>\"}";
	return;
}



//conectarse a la bd
include_once("../config.php");
include_once("db_con.php");

//consultasr contraseña de estre presunto usuario
$consulta = "select Password, Estado,UserName, Email from Usuario where BINARY ( UserName = '{$usuario}' or Email = '{$usuario}')";
$resultado = mysqli_query($enlace,$consulta) or die('No seas malvado con Dino :P ');

//si regreso 0 resultados tons este usuario ni existe
if(mysqli_num_rows($resultado) != 1) {
	$_SESSION['status'] = "WRONG";
	if( isset($resultado))
		mysqli_free_result($resultado);
	mysqli_close($enlace);
	echo "{\"sucess\": false, \"badguy\": false}";
	return;
}


//si existe este usuario, revisar su contraseña
$row = mysqli_fetch_array($enlace,$resultado);

if(($pass, $row[0] ) != $row[0]){
	$_SESSION['status'] = "WRONG";
	echo "{\"sucess\": false, \"badguy\": false}";
	if( isset($resultado))
 		mysqli_free_result($resultado);
	mysqli_close($enlace);
	return;
}




$_SESSION['userID'] = $row['userID'];
$_SESSION['mail'] = $row['mail'];
$_SESSION['status'] = "OK";
$_SESSION['userMode'] = $row["cuenta"] ;
echo "{\"sucess\": true, \"badguy\": false}";

if( isset($resultado))
	mysqli_free_result($resultado);

if(isset($enlace))
	mysqli_close($enlace);
		
?>

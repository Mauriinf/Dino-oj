<?php
	include_once("config.php");
	include_once("includes/db_con.php");
	include 'includes/funcs.php';
	function encriptar($cadena){
	    $key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    //$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    $encrypted = base64_encode(urlencode($cadena));
	    return $encrypted;
	}
	$user_id = $enlace->real_escape_string($_POST['user_id']);
	$token = $enlace->real_escape_string($_POST['token']);
	$password = $enlace->real_escape_string($_POST['password']);
	$con_password = $enlace->real_escape_string($_POST['con_password']);
	
	if(validaPassword($password,$con_password))
	{
		
		$pass_encritado = encriptar($password);
		
		if(cambiaPassword($pass_encritado, $user_id))
		{
			
			echo '<script>alert("Password Modificado")</script> ';
			echo "<script>location.href='index.php'</script>";
		}
		else 
		{
			echo '<script>alert("Error al modificar password")</script> ';
			echo "<script>location.href='javascript:history.back(1)'</script>";//volver una pagina atrás
		}
	}
	else
	{
		echo '<script>alert("Los passwords no coinciden")</script> ';
		echo "<script>location.href='javascript:history.back(1)'</script>";//volver una pagina atrás

	}
?>
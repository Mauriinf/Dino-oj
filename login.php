<?php

	session_start();
	include_once("config.php");
	include_once("includes/db_con.php");
	function encriptar($cadena){
	    //$key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(urlencode($cadena));
	    //$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    return $encrypted;
	}
	$validate = false;
	if(isset($_REQUEST["form"]) && ($_REQUEST["form"] == true)):
		$nick = addslashes($_REQUEST["nick"]);
		$email = addslashes($_REQUEST["nick"]);
		$password = encriptar(addslashes($_REQUEST["password"]));
		$form = addslashes($_REQUEST["form"]);		
		if (mysqli_connect_errno()) 
		{
	    	printf("Falló la conexión: %s\n", mysqli_connect_error());
	   		exit();
		}
		$query = "select * from users where  BINARY (user_id = '$nick' or Email='$email') and (Password = '$password') ";
		$rs = mysqli_query($enlace,$query) or die('Credenciales incorrectas: ' . mysqli_error($enlace));
				

		if(mysqli_num_rows($rs)>=1){
			
				$datosUsu = $rs->fetch_row();
				if($datosUsu[10]==0){
					echo '<script>alert("Cuenta suspendida comuniquese con el Administrador")</script> ';
				}else{
					$_SESSION['UserName'] = $datosUsu[0];
				$_SESSION['rol'] = $datosUsu[11];
				$validate = true;
				$sms="Bienvenido ".$nick;
				echo '<script>alert("'.$sms.'")</script> ';
				echo "<script>location.href='index.php'</script>";
				}
				

		}
		else
		{
			
			echo '<script>alert("Usuario o Password Incorrecto")</script> ';
		}	


	endif;
	
?>
<html xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta content="es_MX" http-equiv="Content-Language" />

	<link media="all" href="css/dino_style.css" type="text/css" rel="stylesheet" />
	
			<script src="js/jquery-ui.custom.min.js"></script>
	<style>

		.post>form{
			width:90%;
			margin:auto;
			
			padding:30px;
			border:1px solid #bbb;
			-moz-border-radius:11px;
		}

		.post>form label{
			display:block;
			color:#777777;
			font-size:13px;
		}
		.post>form p{
			color:#777777;
			font-size:14px;
			text-align:justify;
			margin-bottom:20px;
		}
		.post>form input.text{
			background:#FBFBFB none repeat scroll 0 0;
			border:1px solid #E5E5E5;
			font-size:16px;
			margin-bottom:16px;
			margin-right:6px;
			margin-top:2px;
			padding:3px;
			width:97%;
    		border-radius: 5px;
    		height:33px;
		}
		.post>form select{
			background:#FBFBFB none repeat scroll 0 0;
			border:1px solid #E5E5E5;
			font-size: 12px;
			margin-bottom:16px;
			margin-right:6px;
			margin-top:2px;
			padding:3px;
			width:80%;
		}
		.post>form input.button {
			-moz-border-radius-bottomleft:6px;
			-moz-border-radius-bottomright:6px;
			-moz-border-radius-topleft:6px;
			-moz-border-radius-topright:6px;
			border:1px solid #AAAAAA;
			font-size:16px;
			padding:3px;
			border-radius: 5px;
		}
		.right{
			text-align:right;
		}
		#registro{
			font-size: 30px;
			color:#777777;

			text-align:justify;
			margin-top: -20px;
		}
	</style>
</head>
<body >
<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br><br></div>
	<?php include_once("includes/header.php"); ?>
	

	<div class="post" style="background:white;">
		<form action="login.php" method="post">
			<p id="registro">
			Login
			</p>
			<p>
			Ingresa los datos necesarios para ingresar en el Juez Dino.
			</p>
			<label for="nick">
				Usuario (sin espacios):
			</label>
			<input type="text" id="nick" name="nick" class="text" placeholder="Usuario" required=""/>

			<label for="password">
				Password:
			</label>
			<input type="password" id="password" name="password" class="text" placeholder="Password" required=""/>
			<div class="form-actions">
			<input type="submit" class="btn btn-success" value="Entrar" />

			<input type="hidden" id="form" name="form" value="false" />
			<a class="btn" href="javascript:history.back(1)">Retornar</a>
			</div>
			<a    id="lost_pass" href="recuperar.php">&iquest;Olvidaste tu contase&ntilde;a?</a>
		</form>
	</div>
<br>


	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
 

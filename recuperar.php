<?php

	include_once("config.php");
	include_once("includes/db_con.php");
	include 'includes/funcs.php';
	
	session_start();
	if(isset($_SESSION["UserName"])){
		header("Location: index.php");
	}
	$errors = array();
	
	if(!empty($_POST))
	{
		$email = $enlace->real_escape_string($_POST['email']);
		
		if(!isEmail($email))
		{
			$errors[] = "Debe ingresar un correo electronico valido";
		}
		
		if(emailExiste($email))
		{			
			$user_id = getValor('user_id', 'email', $email);
			$nombre = getValor('Nombre', 'email', $email);
			
			$token = generaTokenPass($user_id,$email);
			
			$url = 'http://'.$_SERVER["SERVER_NAME"].'/juez/cambia_pass.php?user_id='.$user_id.'&token='.$token;
			
			$asunto = 'Recuperar Password - Sistema de Usuarios';
			$cuerpo = "Hola $nombre: <br /><br />Se ha solicitado un reinicio de contrase&ntilde;a. <br/><br/>Para restaurar la contrase&ntilde;a, visita la siguiente direcci&oacute;n: <a href='$url'>Cambiar password</a>";
			
			if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
				//echo "Hemos enviado un correo electronico a las direcion $email para restablecer tu password.<br />";
				//echo "<a href='index.php' >Iniciar Sesion</a>";
				$sms="Hemos enviado un correo electronico a las direcion ".$email." para restablecer tu password.";
				echo '<script>alert("'.$sms.'")</script> ';
				echo "<script>location.href='index.php'</script>";
				exit;
			}
			else {
				$errors[] = "Error al enviar email. Verifique que su email este activo";
			}
		} else {
			$errors[] = "La direccion de correo electronico no existe";
		}
	}
?>
<html xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta content="es_MX" http-equiv="Content-Language" />

	<link media="all" href="css/dino_style.css" type="text/css" rel="stylesheet" />

			<script src="js/jquery-ui.custom.min.js"></script>
	<style>

		.post>form{
			width:50%;
			margin:auto;
			margin-top:30px;
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
		#errores{
			width:50%;
			margin:auto;
			margin-top:5px;
			-moz-border-radius:11px;
		}
	</style>
</head>
<body >
<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br></div>
	<?php include_once("includes/header.php"); ?>
	

	<div class="post" style="background:white;">
		<div id="errores">
		<?php echo resultBlock($errors); ?>
		</div>
		<form action="recuperar.php" method="post">

			<p>
			Recuperar contrase&ntilde;a.
			</p>
			<label for="email">
				Email:
			</label>
			<input type="text" id="email" name="email" class="text" placeholder="Email" required=""/>
			<div id="checkemail" class=""></div>
			<div class="form-actions">
			<input type="submit" class="btn btn-success"id="submitBoton"  value="Recuperar" />
			<input type="hidden" id="form" name="form" value="false" />
			 <a class="btn" href="javascript:history.back(1)">Retornar</a>
			</div>
		</form>
		
	</div>
<script>
$(document).ready(function () {
   $("#email").keyup(checarEmail);
 
});
 $(document).ready(function () {
   $("#email").change(checarEmail);
 
});

function checarEmail() {
    
var email= document.getElementById('email').value;
if(email==""){
  		$("#checkemail").html("<div class='alert alert-danger'> Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
 }else
  	if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(email))){
		$("#checkemail").html("<div class='alert alert-danger'> Ingrese un email valido.<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else{
  		if(email.length >= 63){
			$("#checkemail").html("<div class='alert alert-danger'> Tu email no debe ser mayor a 64 caracteres.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
  		}else
  		{	 
  			$("#checkemail").html("<div class='alert alert-success'> Formato valido.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = false; 		 
		}
  	}
}  

</script>
<br>


	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
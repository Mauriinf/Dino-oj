<?php
	include_once("config.php");
	include_once("includes/db_con.php");
	include 'includes/funcs.php';
	session_start();
	
	if(empty($_GET['user_id'])){
		header('Location: index.php');
	}
	
	if(empty($_GET['token'])){
		header('Location: index.php');
	}
	
	$user_id = $enlace->real_escape_string($_GET['user_id']);
	$token = $enlace->real_escape_string($_GET['token']);
	
	if(!verificaTokenPass($user_id, $token))
	{
		echo '<script>alert("No se pudo verificar los Datos")</script> ';
		echo "<script>location.href='index.php'</script>";
		exit;
	} 
?>
<html xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta content="es_MX" http-equiv="Content-Language" />

	<link media="all" href="css/dino_style.css" type="text/css" rel="stylesheet" />

			<script src="js/jquery-ui.custom.min.js"></script>
	<style>

		.post>form{
			width:80%;
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
	</style>
</head>
<body >
<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br></div>
	<?php include_once("includes/header.php"); ?>
	

	<div class="post" style="background:white;">
		<form action="guarda_pass.php" method="post">
			<input type="hidden" id="user_id" name="user_id" value ="<?php echo $user_id; ?>" />
							
			<input type="hidden" id="token" name="token" value ="<?php echo $token; ?>" />
			<p>
			Cambiar contrase&ntilde;a.
			</p>
			<label for="password">
				Password:
			</label>
			<input type="password" id="password" name="password" class="text"  placeholder="Password" required=""/>
			<div id="checkpassword" class=""></div>
			<label for="re_password">
				Confirma Password:
			</label>
			<input type="password" id="rpassword" name="con_password" class="text"  placeholder="Confirmar" required=""/>
			<div id="checkrpassword" class=""></div>
			<div class="" id="divchearsisoniguales"></div>
			<input type="submit" class="btn btn-success" id="submitBoton" value="Modificar" />

			<input type="hidden" id="form" name="form" value="false" />
		</form>
		
	</div>
<script src='js/jquery.min.js'></script>
  <script>
        
 $(document).ready(function () {
   $("#rpassword").keyup(checkPasswordMatch);
 
});

   $(document).ready(function () {
   $("#password").keyup(checkPasswordMatch2);
 
});
function checkPasswordMatch2() {
 var repeatPass= document.getElementById('rpassword').value;
var repeatclave = repeatPass.length;
 if (repeatclave>0)
 {
    var password = $("#password").val();
    var confirmarPassword = $("#rpassword").val();

    if (password != confirmarPassword){
        $("#divchearsisoniguales").html("<div class='alert alert-danger'> Las contraseñas NO coinciden!<input value='error' type='hidden' name='passwordchecker'></div>");
} else{
    
        $("#divchearsisoniguales").html("<div class='alert alert-success'> Las contraseñas coinciden.<input type='hidden'  value='1' name='passwordchecker'></div>");
    }
    }
}
    
    </script>
  <script>


 function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmarPassword = $("#rpassword").val();
    if (password != confirmarPassword){
        var contador=0;
        $("#divchearsisoniguales").html("<div class='alert alert-danger'>  Las contraseñas NO coinciden!</div><input value='error' type='hidden' name='passwordchecker'>");
   document.getElementById("submitBoton").disabled = true; 
} else{
    contador=1; 
        $("#divchearsisoniguales").html(" <div class='alert alert-success'> Las contraseñas coinciden.</div><input type='hidden'  value='1' name='passwordchecker'>");
        document.getElementById("submitBoton").disabled = false; 
    }
 
}
</script>
<br>


	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
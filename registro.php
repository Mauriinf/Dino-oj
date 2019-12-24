<?php

	require_once("bootstrap.php");
	include 'includes/funcs.php';
	function encriptar($cadena){
	    //$key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(urlencode($cadena));
	    //$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    return $encrypted;
	}
	if(isset($_REQUEST["form"])):
		$nick = addslashes($_REQUEST["nick"]);
		$password = encriptar(addslashes($_REQUEST["password"]));
		$nombre = addslashes($_REQUEST["nombre"]);
		$apellido = addslashes($_REQUEST["apellidos"]);
		$email = addslashes($_REQUEST["email"]);
		$ubicacion = addslashes($_REQUEST["ubicacion"]);
		$escuela = addslashes($_REQUEST["escuela"]);
		$form = addslashes($_REQUEST["form"]);	
		$date=date("Y-m-d H:i:s");
         if(isnullDatosRegistroUsuario($nick,$password,$nombre,$apellido,$email,$ubicacion,$escuela)){
         	$sms=" Campos vacios."; 
				echo '<script>alert("'.$sms.'")</script> ';
			echo "<script>location.href='javascript:history.back(1)'</script>";
		}
		else{
				$query = "insert into users(user_id, Nombre,Apellidos, Password, Ubicacion, Institucion, Email,TiempoRegistro,Estado) 
				values ('$nick','$nombre', '$apellido','$password','$ubicacion','$escuela', '$email','$date',1)";
				$rs = mysqli_query($enlace,$query) or die("Error al registrar");
				$sms=$nick." Fuiste registrado correctamente."; 
				echo '<script>alert("'.$sms.'")</script> ';
				echo "<script>location.href='login.php'</script>";
					exit;
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
			width:70%;
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
	<script language="javascript">
		function _validate(){


			if( $('#nombre')[0].value.length<5){
				return Array("Ingrese su nombre completo por favor.", $('#nombre')[0]);
			}
			
			if( $('#apellidos')[0].value.length<5){
				return Array("Ingrese su apellido por favor.", $('#apellidos')[0]);
			}

			
			if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email')[0].value))){
				return Array("Ingrese un email valido.", $('#email')[0]);
			}

			if($("#nick")[0].value.indexOf(" ") > -1){
				return Array("Tu usuario no puede contener espacios.", $('#nick')[0]);
			}
			
			
			if($("#nick")[0].value.length < 5){
				return Array("Tu usurio no debe ser menor a 5 caracteres.", $('#nick')[0]);
			}
			


			if($("#password")[0].value.length<5){
				return Array("Ingresa un password con una logitud de 5 caracteres.", $('#password')[0]);
			}
			if($("#password")[0].value != $("#re_password")[0].value){
				return Array("Los passwords ingresados no son iguales. Confirma nuevamente tu password", $("#re_password")[0]);
			}
			if($("#escuela")[0].value.length==0){
				return Array("Ingresa tu institucion de procedencia. Gracias", $('#escuela')[0]);
			}
			return true;
		}
		
		function validate(){


			rs = _validate();
			console.log("validando", rs)

			
			if(rs=== true){
				$("form").value="true";
				return true; 
			}else {
				alert(rs[0]);
				rs[1].focus();
				rs[1].select();
				return false;
			}

		}
	</script>
</head>
<body >
<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<br><br><br>
	<?php include_once("includes/header.php"); ?>


	<div class="post" style="background: white;" >
		<form  action="registro.php" method="post" >
			<p id="registro">
			Registro
			</p>
			<p>
			Ingresa los datos necesarios para registrarte en el Juez Dino.
			</p>
			<label for="nick">
				Usuario (sin espacios):
			</label>
			<input type="text" id="nick" name="nick" class="text"  placeholder="Usuario" required=""/>
			<div id="checknick" class=""></div>
			<label for="password">
				Password:
			</label>
			<input type="password" id="password" name="password" class="text"  placeholder="Password" required=""/>
			<div id="checkpassword" class=""></div>
			<label for="re_password">
				Confirma Password:
			</label>
			<input type="password" id="rpassword" name="re_password" class="text"  placeholder="Confirmar" required=""/>
			<div id="checkrpassword" class=""></div>
			<div class="" id="divchearsisoniguales"></div>
			<label for="nombre">
				Nombre Completo:
			</label>
			<input type="text" id="nombre" name="nombre" class="text"  placeholder="Nombre" required=""/>
			<div id="checknombre" class=""></div>
			<label for="apellidos">
				Apellidos:
			</label>
			<input type="text" id="apellidos" name="apellidos" class="text"  placeholder="Apellidos" required=""/>
			<div id="checkapellidos" class=""></div>
			<label for="email">
				Correo :
			</label>
			<input type="text" id="email" name="email" class="text"  placeholder="Email" required=""/>
			<div id="checkemail" class=""></div>
			
			<label>
				Pa&iacute;s:
			</label>
			<select id="ubicacion" name="ubicacion" >
				<script language="javascript">
				
				var states = new Array( "Bolivia","Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burma", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo, Democratic Republic", "Congo, Republic of the", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Mongolia", "Morocco", "Monaco", "Mozambique", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Samoa", "San Marino", " Sao Tome", "Saudi Arabia", "Senegal", "Serbia and Montenegro", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe");

				for(var hi=0; hi<states.length; hi++) 
				document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>");
				</script>
			</select>
			<label>
				Instituci&oacute;n :
			</label>
			<input type="text" id="escuela" name="escuela" class="text"  placeholder="Institucion" required=""/>
			<div id="checkescuela" class=""></div>
			<div class="form-actions">
			<input type="submit" class="btn btn-success"id="submitBoton"  value="Registrar" />
			<input type="hidden" id="form" name="form" value="false" />
			 <a class="btn" href="javascript:history.back(1)">Retornar</a>
			</div>
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
$(document).ready(function () {
   $("#nick").keyup(checarUsuarios);
 
});
 $(document).ready(function () {
   $("#nick").change(checarUsuarios);
 
});
 $(document).ready(function () {
   $("#nombre").keyup(checarNombre);
 
});
 $(document).ready(function () {
   $("#nombre").change(checarNombre);
 
});
$(document).ready(function () {
   $("#apellidos").keyup(checarApellidos);
 
});
 $(document).ready(function () {
   $("#apellidos").change(checarApellidos);
 
});
$(document).ready(function () {
   $("#email").keyup(checarEmail);
 
});
 $(document).ready(function () {
   $("#email").change(checarEmail);
 
});
$(document).ready(function () {
   $("#escuela").keyup(checarEscuela);
 
});
 $(document).ready(function () {
   $("#escuela").change(checarEscuela);
 
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
function checarUsuarios() {
    
var nick= document.getElementById('nick').value;
if(nick==""){
  		$("#checknick").html("<div class='alert alert-danger'> Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(nick.indexOf(" ") > -1){
			$("#checknick").html("<div class='alert alert-danger'> Tu usuario no puede contener espacios.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else
	  	if(nick.length < 5){
			$("#checknick").html("<div class='alert alert-danger'> Tu usurio no debe ser menor a 5 caracteres.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{
	  		if(nick.length >= 255){
				$("#checknick").html("<div class='alert alert-danger'> Tu usurio no debe ser mayor a 255 caracteres.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = true; 
	  		}else
	  		{	  			 
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (xhttp.readyState == 4 && xhttp.status == 200) {
			document.getElementById("checknick").innerHTML = xhttp.responseText;
			usernameresponsed = document.getElementById('usernamechecker').value;



			if (usernameresponsed=="1")
			{			   
			    document.getElementById("submitBoton").disabled = false; 
			      
			}


			else if (usernameresponsed=="0")
			{
			    document.getElementById("submitBoton").disabled = true;
			}
			}
			};
			xhttp.open("POST", "checarusername.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("nick="+nick+"");
	  		}
	  	}
}
 function checarNombre() {
    
var nombre= document.getElementById('nombre').value;
if(nombre==""){
  		$("#checknombre").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(nombre.length < 4){
			$("#checknombre").html("<div class='alert alert-danger'> Ingrese su nombre completo por favor.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{
	  		if(nombre.length >= 127){
				$("#checknombre").html("<div class='alert alert-danger'> Tu nombre no debe ser mayor a 127 caracteres.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = true; 
	  		}else
	  		{	  			 
				$("#checknombre").html("<div class='alert alert-success'> Nombre correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  		}
	  	}
}
function checarApellidos() {
    
var apellidos= document.getElementById('apellidos').value;
if(apellidos==""){
  		$("#checkapellidos").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(apellidos.length < 4){
			$("#checkapellidos").html("<div class='alert alert-danger'> Ingrese su apellido completo por favor.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{
	  		if(apellidos.length >= 127){
				$("#checkapellidos").html("<div class='alert alert-danger'> Tu apellido no debe ser mayor a 127 caracteres.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = true; 
	  		}else
	  		{	  			 
				$("#checkapellidos").html("<div class='alert alert-success'> Apellidos correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  		}
	  	}
} 
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
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (xhttp.readyState == 4 && xhttp.status == 200) {
		document.getElementById("checkemail").innerHTML = xhttp.responseText;
		emailresponsed = document.getElementById('emailchecker').value;



		if (emailresponsed=="1")
		{			   
		    document.getElementById("submitBoton").disabled = false; 
		      
		}


		else if (emailresponsed=="0")
		{
		    document.getElementById("submitBoton").disabled = true;
		}
		}
		};
		xhttp.open("POST", "checkemail.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send("email="+email+"");
  		}
  	}
}  
function checarEscuela() {
    
var escuela= document.getElementById('escuela').value;
if(apellidos==""){
  		$("#checkescuela").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	{		 
		$("#checkescuela").html("<div class='alert alert-success'> Valido.<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = false;
  	}
} 
    </script>


	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
 

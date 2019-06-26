<?php
require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';
	require_once 'includes/funcs.php';
	date_default_timezone_set('America/La_Paz');
	$user="";
	if(!isset($_SESSION["UserName"])){//si no hay una sesion iniciada no puede acceder a crear problema
    	//echo '<script>alert("Debe iniciar sesion para habilitar esta opcion.")</script> ';
    	header("Location: index");
  	}
  	else{
  		$user=$_SESSION["UserName"];
  	}
	function encriptar($cadena){
	    //$key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(urlencode($cadena));
	    //$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    return $encrypted;
	}
	$errors = array();
	if ( !empty($_POST))
	{
		$titulo = addslashes($_POST['titulo']);
		$descripcion = addslashes($_POST['cdesc']);

		//trim($descripcion);//otra manera
		$inicio = addslashes($_POST['inicio']);
		$fin = addslashes($_POST['fin']);
		$ocultarScore = addslashes($_POST['ocultar']);
		$comboprivado = addslashes($_POST['comboprivado']);
		$password = $_POST['password'];
		$plist = trim($_POST['pset']);
		
    	$t="";
		if(empty($_POST['password'])){$password= "N";} 
		else{
			$password=encriptar($password);
		}
		

		if(isNullDatosContest($titulo,$descripcion,$inicio,$fin,$comboprivado,$ocultarScore,$password,$user))
		{	
			$errors[] = "Debe llenar todos los campos";		
		} 
		else{
			if (strlen($titulo) < 5) {
			    $errors[] =  "Escribe un titulo mas explicativo.";
			}
			
			if (strlen($descripcion) < 5) {
			    $errors[] =  "Escribe una descripcion mas explicativa.";
			}
			
					
			//parsear fecha de inicio
			if (($time_inicio = strtotime($inicio)) === false) {
			   $errors[] =  "La fecha de inicio es invalida.";
			} 
			$act=date("Y-m-d H:i:s", mktime(date("H"), date("i") ));
			//que no sea en el pasado
			if($act > $time_inicio){
				$errors[] = "No puedes iniciar un concurso en el pasado.";
			}

			//parsear fecha de final
			if (($time_fin = strtotime($fin)) === false) {
			    $errors[] =  "La fecha de fin es invalida.";
			} 

			if($time_fin < $time_inicio){
				$errors[] = "El concurso no puede terminar... antes de comenzar.";
			}

			if(!isNumero($ocultarScore)){
				$errors[] = "Duracion de la parte ciega debe ser un valor entero";
			}
		}
		$cadena="";
		$piezas= explode(",",$plist );	
		if (count($piezas)>0 && intval($piezas[0])>0){
			$cont=0;
			for ($i=0;$i<count($piezas);$i++){
				$sql="SELECT problem_id FROM problem WHERE problem_id = '".$piezas[$i]."'and Publico='SI' LIMIT 1";
				$res = mysqli_query($enlace,$sql) or die('Error al buscar problema'. mysqli_error($enlace));
				if ($res->num_rows <= 0){	
					$cadena=$cadena.$piezas[$i].",";				
				}
			}					
						//echo $sql_1;
		}
		else{
			$errors[]="Formato de problemas incorrecto";
		}
		if($cadena!=""){
			$errors[]="No existen los problemas: ".$cadena;
		}
		if(count($errors) == 0)
		{		

			$registro = registraConcurso($titulo,$descripcion,$time_inicio,$time_fin,$comboprivado,$ocultarScore,$password,$user) ;			
			if($registro > 0)
				{			
					$pieces = explode(",",$plist );	
					if (count($pieces)>0 && intval($pieces[0])>0){
						for ($i=0;$i<count($pieces);$i++){	
								$var=$pieces[$i];						
								$sql_1="INSERT INTO concursoproblema(cid,pid,numero) 
								VALUES ('$registro','$var','$i')";
								
								$rs = mysqli_query($enlace,$sql_1) or die('Error al guardar problema de contest'. mysqli_error($enlace));
						}						
						//echo $sql_1;
					}
					$sms="Concurso registrado correctamente."; 
					echo '<script>alert("'.$sms.'")</script> ';
					echo "<script>location.href='crearContest.php'</script>";
					exit;
					
				} else {
					$errors[] = "Error al Agendar Concurso".$registro;
					echo '<script>alert("Error al Agendar Concurso")</script> ';
				}
				
				
		}
		
	}	
	
?>
<html xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta content="es_MX" http-equiv="Content-Language" />

	<link media="all" href="css/dino_style.css" type="text/css" rel="stylesheet" />

	
	<script src="ckeditor/ckeditor.js" type="text/javascript"></script>
	<script src="ckfinder/ckfinder.js" type="text/javascript"></script>
	<style>
		.post>form{
			width:80%;
			margin:auto;
			margin-top:30px;
			padding:30px;
			border:2px solid #bbb;
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
		.post>form input.file{
			background:#FBFBFB none repeat scroll 0 0;
			border:1px solid #E5E5E5;
			font-size:16px;
			margin-bottom:16px;
		
			width:97%;
    		border-radius: 5px;
    		

		}
		.post>form textarea{
			background:#FBFBFB none repeat scroll 0 0;
			border:1px solid #E5E5E5;
			font-size:16px;
			margin-bottom:16px;
			width:97%;
			min-width: 97%;
			min-height: 200px;
    		border-radius: 5px;
    		

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
			width:80%;
			margin:auto;
			margin-top:5px;
			-moz-border-radius:11px;
		}
	</style>
	
</head>
<body >
<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br><br></div>
	<?php include_once("includes/header.php"); ?>

	<script type="text/javascript">
	function comprobarOption(elemento){
    
    if(elemento.value == 1) document.getElementById("esprivado").style.display = "inline";
    else document.getElementById("esprivado").style.display = "none";
	}
	</script>
	<div class="post" style="background:white;">
		<!--mostrar los errores-->
		<div id="errores">
		<?php echo resultBlock($errors); ?>
		</div>
		<!--mostrar formulario-->
		<form id="concurso" name="concurso" action="crearContest.php" method="post" enctype="multipart/form-data">
		
			<p id="registro">
			Crear un Concurso
			</p>
			<label for="titulo">
				T&iacute;tulo
			</label>
			<input type="text" id="titulo" name="titulo" class="text" placeholder="Titulo" required=""/>
			<div id="checktitulo" class=""></div>
			<label for="cdesc">Descripcion del concurso</label>
			<input placeholder="Descripcion del concurso" type="text" id="cdesc" name="cdesc" required=""class="text" />
			<div id="checkdescripcion" class=""></div>

			<label for="inicio">Hora actual en Dino (<?php echo date("Y-m-d H:i:s", mktime(date("H"), date("i") )); ?>)<br><br>
				Inicio del Concurso ( YYYY-MM-DD  HH:MM:SS )
			</label>

			<input type="text" id="inicio" name="inicio"required="" class="text" 
				value="<?php echo date("Y-m-d H:i:s", mktime(date("H"), date("i") + 5 )); ?>" />
			<div id="checkinicio" class=""></div>

			<label for="fin">Fin del Concurso ( YYYY-MM-DD  HH:MM:SS )</label>
			<input type="text" id="fin" name="fin" required="" class="text" 
				value="<?php echo date("Y-m-d H:i:s", mktime(date("H"), date("i") + 10 )); ?>"/>
				<div id="checkfin" class=""></div>
			<label for="pset">Duracion de la parte ciega del Scoreboard</label>
			<input placeholder="0" type="text" id="ocultar" required=""name="ocultar" class="text" />
			<div id="checkocultar" class=""></div>
			<label for="pset">Modo de Acceso</label>
			<select class="text" name="comboprivado" id="comboprivado" onChange="comprobarOption(this)"> 
			<option value="0" selected="selected">Publico</option> 
			<option value="1">Privado</option> 
			</select> 
			<input name="password" id="esprivado" type="password" class="text" style="display:none"  placeholder="Password" /> 
			 <label for="pset">Problemas, ID de los problemas separados por una coma</label>
			<input placeholder="Example:1000,1001,1002" type="text" required="" id="pset" name="pset" class="text" />
			<div id="checkpset" class=""></div>
			
			<div class="form-actions">
			<input type="submit" id="submitBoton" class="btn btn-success" value="Agendar concurso" />
			<a class="btn" href="javascript:history.back(1)">Retornar</a>
			</div>
	
		</form>

			
	</div>
<script src='js/jquery.min.js'></script>
  <script>
  $(document).ready(function () {
   $("#titulo").keyup(checkTitulo);
 
});

   $(document).ready(function () {
   $("#titulo").change(checkTitulo);
 
});
   $(document).ready(function () {
   $("#cdesc").keyup(checkDescripcion);
 
});

   $(document).ready(function () {
   $("#cdesc").change(checkDescripcion);
 
});
   $(document).ready(function () {
   $("#inicio").keyup(checkInicio);
 
});

   $(document).ready(function () {
   $("#inicio").change(checkInicio);
 
});
   $(document).ready(function () {
   $("#fin").keyup(checkFin);
 
});

   $(document).ready(function () {
   $("#fin").change(checkFin);
 
});
      $(document).ready(function () {
   $("#ocultar").keyup(checkOcultar);
 
});

   $(document).ready(function () {
   $("#pset").change(checkPset);
 
});
      $(document).ready(function () {
   $("#pset").keyup(checkPset);
 
});

 function checkTitulo() {
    
var titulo= document.getElementById('titulo').value;
if(titulo==""){
  		$("#checktitulo").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(titulo.length < 5){
			$("#checktitulo").html("<div class='alert alert-danger'> Titulo muy corto.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{
	  		if(titulo.length >= 255){
				$("#checktitulo").html("<div class='alert alert-danger'> Titulo no debe ser mayor a 255 caracteres.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = true; 
	  		}else
	  		{	  			 
				$("#checktitulo").html("<div class='alert alert-success'> Titulo correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  		}
	  	}
}
function checkDescripcion() {
    
var descripcion= document.getElementById('cdesc').value;
if(descripcion==""){
  		$("#checkdescripcion").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(descripcion.length < 5){
			$("#checkdescripcion").html("<div class='alert alert-danger'> Descripcion muy corto.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checkdescripcion").html("<div class='alert alert-success'> Descripcion correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}
}
function checkInicio() {
    
var inicio= document.getElementById('inicio').value;
var actual='<?php echo date("Y-m-d H:i:s", mktime(date("H"), date("i") )); ?>';
/*
otra manera de comparar con el actual mas largo
today = new Date();//Fecha actual del sistema
function d2(n) {
		if(n<9) return "0"+n;
		return n;
}
var actual =  d2(today.getDate()) + "-" + d2(parseInt(today.getMonth()+1)) + "-" +today.getFullYear() + " " + d2(today.getHours()) + ":" + d2(today.getMinutes()) + ":" + d2(today.getSeconds());*/

//expresion para verificar un datetimer formato dd-MM-yyyy hh:mm:ss 
//^([0-2][0-9]|3[0-1])\-(0[1-9]|1[0-2])\-([0-2][0-9]{3}) ([0-1][0-9]|2[0-3])\:([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
//expresion para verificar un datetimer formato yyyy-MM-dd hh:mm:ss 
//^([0-2][0-9]{3})\-(0[1-9]|1[0-2])\-([0-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
var exp=/^([0-2][0-9]{3})\-(0[1-9]|1[0-2])\-([0-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;

//^([0-2][0-9]|3[0-1])\-(0[1-9]|1[0-2])\-([0-2][0-9]{3}) ([0-1][0-9]|2[0-3])\:([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
if(inicio==""){
  		$("#checkinicio").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(!(exp.test(inicio))){
			$("#checkinicio").html("<div class='alert alert-danger'> Formato invalido.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	 
	  		if(actual>inicio){ 
				$("#checkinicio").html("<div class='alert alert-danger'> No puedes iniciar un concurso en el pasado.<input value='error' type='hidden' name='passwordchecker'></div>");
	  			document.getElementById("submitBoton").disabled = true; 
	  		}else{		  			 
				$("#checkinicio").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
		  	}
	  	}
}
function checkFin() {
    
var fin= document.getElementById('fin').value;
var inicio= document.getElementById('inicio').value;
//expresion para verificar un datetimer formato dd-MM-yyyy hh:mm:ss 
//^([0-2][0-9]|3[0-1])\-(0[1-9]|1[0-2])\-([0-2][0-9]{3}) ([0-1][0-9]|2[0-3])\:([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
//expresion para verificar un datetimer formato yyyy-MM-dd hh:mm:ss 
//^([0-2][0-9]{3})\-(0[1-9]|1[0-2])\-([0-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
var exp=/^([0-2][0-9]{3})\-(0[1-9]|1[0-2])\-([0-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
//^([0-2][0-9]|3[0-1])\-(0[1-9]|1[0-2])\-([0-2][0-9]{3}) ([0-1][0-9]|2[0-3])\:([0-5][0-9])\:([0-5][0-9])( ([\-\+]([0-1][0-9])\:00))?$/;
if(fin==""){
  		$("#checkfin").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(!(exp.test(fin))){
			$("#checkfin").html("<div class='alert alert-danger'> Formato invalido.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
			if(inicio>fin){ 
				$("#checkfin").html("<div class='alert alert-danger'> El concurso no puede terminar... antes de comenzar.<input value='error' type='hidden' name='passwordchecker'></div>");
	  			document.getElementById("submitBoton").disabled = true; 
	  		}else{		  			 
				$("#checkfin").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
		  	}
	  	}
}
function checkOcultar() {
    
var ocultar= document.getElementById('ocultar').value;
if(ocultar==""){
  		$("#checkocultar").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(isNaN(ocultar)){
			$("#checkocultar").html("<div class='alert alert-danger'> Solo debe contener numeros.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checkocultar").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}
}

function checkPset() {
    
var pset= document.getElementById('pset').value;
if(pset==""){
  		$("#checkpset").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else{
	  		  		  			 
		$("#checkpset").html("<div class='alert alert-success'> Correcto. Asegurese de introducir problemas validos<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = false;
  	}
}
</script>
	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
 

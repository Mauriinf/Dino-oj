<?php

require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';
	include 'includes/funcs.php';
	
	if(!isset($_SESSION["UserName"])){//si no hay una sesion iniciada no puede acceder a crear problema
    	//echo '<script>alert("Debe iniciar sesion para habilitar esta opcion.")</script> ';
    	header("Location: index.php");
  	}else{
  		$id=$_SESSION["UserName"];
  	}

	$errors = array();
	if ( !empty($_POST))
	{
		$titulo = $enlace->real_escape_string($_POST['titulo']);
		$autor = $enlace->real_escape_string($_POST['autor']);
		$tiempo = $enlace->real_escape_string($_POST['tiempo']);
		$memoria = $enlace->real_escape_string($_POST['memoria']);
		$descripcion = saltoLinea($_POST['descripcion']);

		//trim($descripcion);//otra manera
		$entrada = saltoLinea($_POST['entrada']);
		$ejemploentrada = saltoLinea($_POST['ejemploentrada']);
		$salida = saltoLinea($_POST['salida']);
		$ejemplosalida = saltoLinea($_POST['ejemplosalida']);
		$archivo = $_FILES['archivo']['name'];
		 $tipo="";
    	$t="";
		if (is_uploaded_file($_FILES['archivo']['tmp_name'])) {
			$extension = pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION);
			if($extension=="zip"|| $extension=="ZIP")
			{
				$tipo=".".$extension;
        		
			}
			else {	        	
	    	 $errors[] = "Formato no admitido"; 
	    	 $tipo="";    		
			}
			
		}else{
			$errors[] = "Ingrese archivo";
			$tipo="";
		}
		

		

		if(isNullDatosProblema($titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida))
		{	
			$errors[] = "Debe llenar todos los campos";		
		} 
		else{
			if(!isNumero($tiempo)){
				$errors[] = "Limite de tiempo debe ser un valor entero";
			}
			if(!isNumero($memoria)){
				$errors[] = "Limite de memoria debe ser un valor entero";
			}
		}
		if(count($errors) == 0)
		{			

			$registro = registraProblema($id,$titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida);			
			if($registro > 0)
			{	
				$arch = "doc/".$registro.$tipo;//almacena en $nom la fecha hora segundo y milesegundo en la q se subio el archivo
				$des="doc/";
				copy($_FILES['archivo']['tmp_name'], $arch);
				$carpeta = "doc/".$registro;
				if (!file_exists($carpeta)) {
				    mkdir($carpeta, 0777, true);
				   // Include and initialize Extractor class
					require_once 'Extractor.class.php';
					$extractor = new Extractor;

					// Path of archive file
					$archivePath = $arch;

					// Destination path
					$destPath = $carpeta."/";

					// Extract archive file
					$extract = $extractor->extract($archivePath, $destPath);

					if($extract){
					    $stmt = $enlace->prepare("UPDATE problem SET Source = ? WHERE problem_id = ?");
						$stmt->bind_param('si', $arch, $registro);				
						if($stmt->execute()){
							$sms="Problema registrado correctamente.";
							echo '<script>alert("'.$sms.'")</script> ';
							echo "<script>location.href='index.php'</script>";
							exit;
						} else {
							$errors[] = "Error al Subir archivo";	
						}	
					}else{
					    $errors[] = "No se pudo descomprimir el archivo";
					} 
				}
				else{
					$errors[] = "El directorio ya existe";
				}
				
								
					
					
			} else {
					$errors[] = "Error al Registrar";
					
			}
				
				
		}
		
	}	
	
?>
<html xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta content="es_MX" http-equiv="Content-Language" />

	<link media="all" href="css/dino_style.css" type="text/css" rel="stylesheet" />

	<script src="js/jquery-ui.custom.min.js"></script>
	<script src="ckeditor/ckeditor.js" type="text/javascript"></script>
	<script src="ckfinder/ckfinder.js" type="text/javascript"></script>
	<style>

		.post>form{
			width:90%;
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


	<div class="post" style="background:white;">
		<!--mostrar los errores-->
		<div id="errores">
		<?php echo resultBlock($errors); ?>
		</div>
		<form action="crearProblema.php" method="post" enctype="multipart/form-data">
		
			<p id="registro">
			Crear Problema
			</p>
			<p>
			&iquest;Es la primera vez que crea un problema? <a href="https://github.com/Dino-oj/Dino-online-judge/wiki">Aqui esta c&oacute;mo crear un archivo</a>.
			</p>
			<label for="titulo">
				T&iacute;tulo
			</label>
			<input type="text" id="titulo" name="titulo" class="text" placeholder="Titulo" required=""/>
			<div id="checktitulo" class=""></div>
			<label for="autor">
				Autor
			</label>
			<input type="text" id="autor" name="autor" class="text" placeholder="Autor" required=""/>
			<div id="checkautor" class=""></div>
			<label for="tiempo">
				Tiempo L&iacute;mite(ms)
			</label>
			<input type="text" id="tiempo" name="tiempo" placeholder="1000" class="text" required=""/>
			<div id="checktiempo" class=""></div>
			<label for="memoria">
				L&iacute;mite de Memoria(KiB)
			</label>
			<input type="text" id="memoria" name="memoria" placeholder="32768" class="text" required=""/>
			<div id="checkmemoria" class=""></div>
			<label for="descripcion">
				Descripci&iacute;n
			</label>
			<textarea class="ckeditor" id="editor1" row="10" cols="5" name="descripcion"></textarea>
			<div id="checkeditor1" class=""></div>
			<label for="entrada">
				Entrada
			</label>
			<textarea id="entrada" row="10" cols="5" name="entrada" placeholder="Entrada de problema" required=""></textarea>
			<div id="checkentrada" class=""></div>
			<label for="salida">
				Salida
			</label>
			<textarea id="salida" row="10" cols="5" name="salida" placeholder="Salida de problema" required=""></textarea>
			<div id="checksalida" class=""></div>
			<label for="ejemploentrada">
				Ejemplo de entrada
			</label>
			<textarea id="ejentrada" row="10" cols="5" name="ejemploentrada" placeholder="Ejemplo de entrada" required=""></textarea>
			<div id="checkejentrada" class=""></div>
			<label for="ejemplosalida">
				Ejemplo de salida
			</label>
			<textarea  id="ejsalida" row="10" cols="5" name="ejemplosalida" placeholder="Ejemplo de salida" required=""></textarea>
			<div id="checkejsalida" class=""></div>

			
			<label>
				Archivo <a href="https://github.com/Dino-oj/Dino-online-judge/wiki">C&oacute;mo crear el archivo</a>
			</label>
			 <input name="archivo" type="file" required="">
			
			 <script type="text/javascript">
			var editor = CKEDITOR.replace( 'editor1', {
			    filebrowserBrowseUrl : 'ckfinder/ckfinder.html',
			    filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',
			    filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',
			    filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
			    filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
			    filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			});
			CKFinder.setupCKEditor( editor, '../' );
			</script>
			
			<div class="form-actions">
			<input type="submit" id="submitBoton"class="btn btn-success" value="Crear Problema" />
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
   $("#autor").keyup(checkAutor);
 
});

   $(document).ready(function () {
   $("#autor").change(checkAutor);
 
});
   $(document).ready(function () {
   $("#tiempo").keyup(checkTiempo);
 
});

   $(document).ready(function () {
   $("#tiempo").change(checkTiempo);
 
});
   $(document).ready(function () {
   $("#memoria").keyup(checkMemoria);
 
});

   $(document).ready(function () {
   $("#memoria").change(checkMemoria);
 
});
      $(document).ready(function () {
   $("#editor1").keyup(checkEditor1);
 
});

   $(document).ready(function () {
   $("#editor1").change(checkEditor1);
 
});
      $(document).ready(function () {
   $("#entrada").keyup(checkEntrada);
 
});

   $(document).ready(function () {
   $("#entrada").change(checkEntrada);
 
});
      $(document).ready(function () {
   $("#salida").keyup(checkSalida);
 
});

   $(document).ready(function () {
   $("#salida").change(checkSalida);
 
});
      $(document).ready(function () {
   $("#ejentrada").keyup(checkEjentrada);
 
});

   $(document).ready(function () {
   $("#ejentrada").change(checkEjentrada);
 
});
      $(document).ready(function () {
   $("#ejsalida").keyup(checkEjsalida);
 
});

   $(document).ready(function () {
   $("#ejsalida").change(checkEjsalida);
 
});
 function checkTitulo() {
    
var titulo= document.getElementById('titulo').value;
if(titulo==""){
  		$("#checktitulo").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(titulo.length < 3){
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
function checkAutor() {
    
var autor= document.getElementById('autor').value;
if(autor==""){
  		$("#checkautor").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(autor.length < 3){
			$("#checkautor").html("<div class='alert alert-danger'> Autor muy corto.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checkautor").html("<div class='alert alert-success'> Autor correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}
}
function checkTiempo() {
    
var tiempo= document.getElementById('tiempo').value;
if(tiempo==""){
  		$("#checktiempo").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(isNaN(tiempo)){
			$("#checktiempo").html("<div class='alert alert-danger'> Solo debe contener numeros.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checktiempo").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}
}
function checkMemoria() {
    
var memoria= document.getElementById('memoria').value;
if(memoria==""){
  		$("#checkmemoria").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(isNaN(memoria)){
			$("#checkmemoria").html("<div class='alert alert-danger'> Solo debe contener numeros.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checkmemoria").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}
}
function checkEditor1() {
    
var editor1= document.getElementById('editor1').value;
if(editor1==""){
  		$("#checkeditor1").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else
	  	if(editor1.length < 4){
			$("#checkeditor1").html("<div class='alert alert-danger'> Descripcion muy corto.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checkeditor1").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}
}
function checkEntrada() {
    
var entrada= document.getElementById('entrada').value;
if(entrada==""){
  		$("#checkentrada").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else{
	  		  		  			 
		$("#checkentrada").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = false;
  	}
}
function checkSalida() {
    
var salida= document.getElementById('salida').value;
if(entrada==""){
  		$("#checksalida").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else{
	  		  		  			 
		$("#checksalida").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = false;
  	}
}
function checkEjentrada() {
    
var ejentrada= document.getElementById('ejentrada').value;
if(ejentrada==""){
  		$("#checkejentrada").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else{
	  		  		  			 
		$("#checkejentrada").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = false;
  	}
}
function checkEjsalida() {
    
var ejsalida= document.getElementById('ejsalida').value;
if(ejsalida==""){
  		$("#checkejsalida").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = true; 
  	}else{
	  		  		  			 
		$("#checkejsalida").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
  		document.getElementById("submitBoton").disabled = false;
  	}
}
</script>
	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
 

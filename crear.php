<?php
session_start();
	include_once("config.php");
	include_once("includes/db_con.php");
	include 'includes/funcs.php';
	$id=$_SESSION["UserID"];
	if(!isset($_SESSION["UserID"])){//si no hay una sesion iniciada no puede acceder a crear problema
    	//echo '<script>alert("Debe iniciar sesion para habilitar esta opcion.")</script> ';
    	header("Location: index.php");
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
		if($tipo!=""){//si la extension es valido

			$time =microtime(true);//mide el tiempo
			$micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
			$date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
			$fecha=$date->format("Ymd");
			$arch = "doc/".$date->format("Ymd-His-u").$tipo;//almacena en $nom la fecha hora segundo y milesegundo en la q se subio el archivo
			copy($_FILES['archivo']['tmp_name'], $arch);
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

			$registro = registraProblema($id,$titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida,$arch);			
			if($registro > 0)
				{				
					$sms="Problema registrado correctamente.";
				echo '<script>alert("'.$sms.'")</script> ';
				echo "<script>location.href='index'</script>";
				exit;
					
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
			width:600px;
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
			width:650px;
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
		<form action="crear" method="post" enctype="multipart/form-data">
		
			<p id="registro">
			Crear Problema
			</p>
			<p>
			&iquest;Es la primera vez que crea un problema? <a href="">Aqui esta c&oacute;mo crear un archivo</a>.
			</p>
			<label for="titulo">
				T&iacute;tulo
			</label>
			<input type="text" id="titulo" name="titulo" class="text" />
			<label for="autor">
				Autor
			</label>
			<input type="text" id="autor" name="autor" class="text" />
			<label for="tiempo">
				Tiempo L&iacute;mite(ms)
			</label>
			<input type="text" id="tiempo" name="tiempo" placeholder="1000" class="text" />
			<label for="memoria">
				L&iacute;mite de Memoria(KiB)
			</label>
			<input type="text" id="memoria" name="memoria" placeholder="32768" class="text" />

			<label for="descripcion">
				Descripci&iacute;n
			</label>
			<textarea class="ckeditor" id="editor1" row="10" cols="5" name="descripcion"></textarea>
			
			<label for="entrada">
				Entrada
			</label>
			<textarea id="editor1" row="10" cols="5" name="entrada" ></textarea>

			<label for="salida">
				Salida
			</label>
			<textarea id="editor1" row="10" cols="5" name="salida" ></textarea>

			<label for="ejemploentrada">
				Ejemplo de entrada
			</label>
			<textarea id="editor1" row="10" cols="5" name="ejemploentrada" ></textarea>

			<label for="ejemplosalida">
				Ejemplo de salida
			</label>
			<textarea  id="editor1" row="10" cols="5" name="ejemplosalida" ></textarea>


			
			<label>
				Archivo <a href="">C&oacute;mo crear el archivo</a>
			</label>
			 <input name="archivo" type="file">
			
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
			<br><br>
			<input type="submit" class="button" value="Crear Problema" />
			
			
		</form>

			
	</div>
<div id="errores">
<?php echo resultBlock($errors); ?>
</div>
	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
 

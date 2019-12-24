<?php
require_once 'ficheroGlobal.php';
session_start();
    include_once("config.php");
    include_once("includes/db_con.php");
    include 'includes/funcs.php';
    if(!isset($_SESSION["UserName"])){//si no hay una sesion iniciada no puede acceder a crear problema
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
        $salid = saltoLinea($_POST['salida']);
        $ejemplosalida = saltoLinea($_POST['ejemplosalida']);
        $archivo = $_FILES['archivo']['name'];
        $id = $_POST['id'];
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
       

        if(isNullDatosProblema($titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salid,$ejemploentrada,$ejemplosalida))
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
                $arch = "/home/judge/data/".$id.$tipo;//almacena en $nom la fecha hora segundo y milesegundo en la q se subio el archivo
                
                copy($_FILES['archivo']['tmp_name'], $arch);
                $carpeta = "/home/judge/data/".$id;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta,0777);      
                     $salida=shell_exec('chmod 777'.$carpeta);
                    $s=shell_exec('chmod 777'.$arch);                      
                }
                else{
                    /* foreach(glob($carpeta . "/*") as $archivos_carpeta){             
        		if (is_dir($archivos_carpeta)){
          			rmDir_rf($archivos_carpeta);
        		} else {
        		unlink($archivos_carpeta);
        		}
      		    }*/
		    $cmdBorrarDir = "rm -R ".$carpeta."/";
                    $a=shell_exec($cmdBorrarDir);
                }
		if (!file_exists($carpeta)) {
                    mkdir($carpeta,0777);      
                                        
                }

                $des=$carpeta."/";
                $salida=shell_exec('chmod 777'.$carpeta);
                $s=shell_exec('chmod 777'.$arch);                       //Include and initialize Extractor class
                    require_once 'Extractor.class.php';
                    $extractor = new Extractor;

                    // Path of archive file
                    $archivePath = $arch;

                    // Destination path
                    $destPath = $des;

                    // Extract archive file
                    $extract = $extractor->extract($archivePath, $destPath);
                    if($extract){
                        $registro = EditarProblema($id,$titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salid,$ejemploentrada,$ejemplosalida,$arch);         
                        if($registro > 0)
                            {               
                                $sms="Problema editado correctamente.";
                            echo '<script>alert("'.$sms.'")</script> ';
                            echo "<script>location.href='misProblemas.php'</script>";
                            exit;
                                
                            } else {
                                $sms="Error al editar.";
                                echo '<script>alert("'.$sms.'")</script> ';
                                echo "<script>location.href='javascript:history.back(1)'</script>";//volver una pagina atrás
                            }
                    }else{
                        $errors[] = "No se pudo descomprimir el archivo";
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
        <div id="errores">
        <?php echo resultBlock($errors); ?>
        </div>
        <?php function showContent($datos,$id){ ?>
        <form action="editarProblema.php" method="post" enctype="multipart/form-data">
        <?php if(!isset($_REQUEST["form"])): ?>
            <p id="registro">
            Editar Problema
            </p>
            <p>
            &iquest;Es la primera vez que crea un problema? <a href="https://github.com/Dino-oj/Dino-online-judge/wiki">Aqui esta c&oacute;mo crear un archivo</a>.
            </p>
            <input type="hidden" name="id" value="<?php echo $id;?>"required=""/>
            <label for="titulo">
                T&iacute;tulo
            </label>
            <input type="text" id="titulo" name="titulo" class="text"required="" value="<?php echo $datos['Titulo']; ?>"/>
            <div id="checktitulo" class=""></div>
            <label for="autor">
                Autor
            </label>
            <input type="text" id="autor" name="autor" class="text"required="" value="<?php echo $datos['Autor']; ?>"/>
            <div id="checkautor" class=""></div>
            <label for="tiempo">
                Tiempo L&iacute;mite(ms)
            </label>
            <input type="text" id="tiempo" name="tiempo" class="text"required="" value="<?php echo $datos['time_limit']; ?>"/>
            <div id="checktiempo" class=""></div>
            <label for="memoria">
                L&iacute;mite de Memoria(KiB)
            </label>
            <input type="text" id="memoria" name="memoria"  class="text" required=""value="<?php echo $datos['memory_limit']; ?>"/>
            <div id="checkmemoria" class=""></div>
            <label for="descripcion">
                Descripci&iacute;n
            </label>
            <textarea class="ckeditor" id="editor1" row="10" cols="5" name="descripcion"><?php echo htmlspecialchars($datos['descripcion']); ?></textarea>
            <div id="checkeditor1" class=""></div>
            <label for="entrada">
                Entrada
            </label>
            <textarea id="editor2" row="10" cols="5"  name="entrada" ><?php echo htmlspecialchars($datos['Entrada']); ?></textarea>
            <div id="checkentrada" class=""></div>
            <label for="salida">
                Salida
            </label>
            <textarea id="editor3" row="10" cols="5"  name="salida" ><?php echo htmlspecialchars($datos['Salida']); ?></textarea>
            <div id="checksalida" class=""></div>
            <label for="ejemploentrada">
                Ejemplo de entrada
            </label>
            <textarea id="ejentrada" row="10" cols="5" required="" name="ejemploentrada" ><?php echo $datos['EjemploEntrada']; ?></textarea>
            <div id="checkejentrada" class=""></div>
            <label for="ejemplosalida">
                Ejemplo de salida
            </label>
            <textarea  id="ejsalida" row="10" cols="5" required="" name="ejemplosalida" ><?php echo $datos['EjemploSalida']; ?></textarea>
            <div id="checkejsalida" class=""></div>

            
            <label>
                Archivo <a href="https://github.com/Dino-oj/Dino-online-judge/wiki">C&oacute;mo crear el archivo</a>
            </label>
             <input name="archivo" type="file" required="" value="<?php echo $datos['Saurce']; ?>">
            
            

			 <script type="text/javascript">

			var editor = CKEDITOR.replace( 'editor1', {

				uiColor: '#CCEAEE',

			      toolbar: [

			    //{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },

			    //{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },

			    { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'SelectAll', '-', 'Scayt' ] },

			    //{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },

			    //'/',

			    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },

			    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList',   '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },

			    //{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },

			    { name: 'insert', items: [ 'Image',  'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'] },

			    '/',

			    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },

			    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },

			    //{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },

			    { name: 'others', items: [ '-' ] },

			    { name: 'about', items: [ 'About' ] }

			],

			    filebrowserBrowseUrl : 'ckfinder/ckfinder.html',

			    filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',

			    filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',

			    filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

			    filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

			    filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

			});

			CKFinder.setupCKEditor( editor, '../' );

			</script>
			 <script type="text/javascript">

			var editorentrada = CKEDITOR.replace( 'editor2', {

				uiColor: '#CCEAEE',

			      toolbar: [

			    //{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },

			    //{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },

			    { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'SelectAll', '-', 'Scayt' ] },

			    //{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },

			    //'/',

			    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },

			    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList',   '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },

			    //{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },

			    { name: 'insert', items: [ 'Image',  'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'] },

			    '/',

			    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },

			    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },

			    //{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },

			    { name: 'others', items: [ '-' ] },

			    { name: 'about', items: [ 'About' ] }

			],

			    filebrowserBrowseUrl : 'ckfinder/ckfinder.html',

			    filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',

			    filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',

			    filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

			    filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

			    filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

			});

			CKFinder.setupCKEditor( editorentrada, '../' );

			
			</script>
			 <script type="text/javascript">

			var editorsalida = CKEDITOR.replace( 'editor3', {

				uiColor: '#CCEAEE',

			      toolbar: [

			    //{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },

			    //{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },

			    { name: 'editing', groups: [ 'selection', 'spellchecker' ], items: [ 'SelectAll', '-', 'Scayt' ] },

			    //{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },

			    //'/',

			    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },

			    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList',   '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },

			    //{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },

			    { name: 'insert', items: [ 'Image',  'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'] },

			    '/',

			    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },

			    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },

			    //{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },

			    { name: 'others', items: [ '-' ] },

			    { name: 'about', items: [ 'About' ] }

			],

			    filebrowserBrowseUrl : 'ckfinder/ckfinder.html',

			    filebrowserImageBrowseUrl : 'ckfinder/ckfinder.html?type=Images',

			    filebrowserFlashBrowseUrl : 'ckfinder/ckfinder.html?type=Flash',

			    filebrowserUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',

			    filebrowserImageUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',

			    filebrowserFlashUploadUrl : 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'

			});

			CKFinder.setupCKEditor( editorsalida, '../' );

			</script>
            <div class="form-actions">
            <input type="submit" class="btn btn-success" id="submitBoton" name="form" value="Editar Problema" />
            <a class="btn" href="javascript:history.back(1)">Retornar</a>
            </div>
            <?php endif; ?> 
            
        </form>
        <?php } ?>
        <?php 
            if( ! isset($_SESSION['UserName'] ) ){
                ?> <div align="center"><a href="href='login.php'">Debes iniciar sesion para poder editar tus datos.</a></div> <?php
            }else{
                
                
                    //mysql_query("select * from Usuario where userID = '". slashes() ."'")
                    $query = "select * FROM problem WHERE problem_id='" . mysqli_real_escape_string($enlace,$_GET['id']) . "'";
                    //echo $query;
                    $foo = mysqli_query($enlace,$query) or die(mysqli_error());
                    //echo ">" . mysql_num_rows($foo). "< <br>";
                    $r = mysqli_fetch_array($foo);
                    $id= mysqli_real_escape_string($enlace,$_GET['id']);

                    //var_dump($r);
                    showContent($r,$id);
                    
            }
        ?>
            
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
   $("#editor2").keyup(checkEntrada);
 
});

   $(document).ready(function () {
   $("#editor2").change(checkEntrada);
 
});
      $(document).ready(function () {
   $("#editor3").keyup(checkSalida);
 
});

   $(document).ready(function () {
   $("#editor3").change(checkSalida);
 
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
    
var editor2= document.getElementById('editor2').value;
if(editor2==""){
        $("#checkentrada").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
        document.getElementById("submitBoton").disabled = true; 
    }else{
         if(editor2.length < 4){
			$("#checkentrada").html("<div class='alert alert-danger'> Entrada muy corta.<input value='error' type='hidden' name='passwordchecker'></div>");
	  		document.getElementById("submitBoton").disabled = true; 
	  	}else{	  		  			 
				$("#checkentrada").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
		  		document.getElementById("submitBoton").disabled = false;
	  	}                        
   
    }
}
function checkSalida() {
    
var editor3= document.getElementById('editor3').value;
if(editor3==""){
        $("#checksalida").html("<div class='alert alert-danger'>Llene el campo!<input value='error' type='hidden' name='passwordchecker'></div>");
        document.getElementById("submitBoton").disabled = true; 
    }else{
                                 
         if(editor3.length < 4){
            $("#checksalida").html("<div class='alert alert-danger'> Descripcion muy corto.<input value='error' type='hidden' name='passwordchecker'></div>");
            document.getElementById("submitBoton").disabled = true; 
        }else{                       
                $("#checksalida").html("<div class='alert alert-success'> Correcto.<input value='error' type='hidden' name='passwordchecker'></div>");
                document.getElementById("submitBoton").disabled = false;
        }
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

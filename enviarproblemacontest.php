<?php
    require_once("bootstrap.php");
    require_once 'ficheroGlobal.php';
    include 'includes/funcs.php';
    if(isset($_SESSION["UserName"])){
       $user=$_SESSION["UserName"];
    }
   
    $errors = array();
    if( isset($_POST["btnEnviar"])):
        $codigo = saltoLinea($_POST['codigo']);//revisar para q no lo suprima el saltode linea de un codigo
        $lenguaje = $enlace->real_escape_string($_REQUEST['lenguaje']);
        $enviar_a=$enlace->real_escape_string($_REQUEST['enviar_a']);
        $cid=$enlace->real_escape_string($_REQUEST['cid']);
        if(strlen(trim($codigo)) < 1){
            $errors[] = "Debe llenar el campo de código"; 
        }
         if(strlen(trim($lenguaje)) < 1){
            $errors[] = "Debe seleccionar un lenguaje"; 
         }
         if(strlen(trim($enviar_a)) < 1){
            $errors[] = "No se Selecionó el problema a enviar"; 
         }
         if(strlen(trim($cid)) < 1){
            $errors[] = "No se Selecionó el concurso"; 
         }
         if(count($errors) == 0)
        {        
            $time =microtime(true);//mide el tiempo
            $micro_time=sprintf("%06d",($time - floor($time)) * 1000000);
            $date=new DateTime( date('Y-m-d H:i:s.'.$micro_time,$time) );
            $fecha=$date->format("Ymd-His-u");//almacena en $nom la fecha hora segundo y milesegundo en la q se subio el archivo

            $registro = registraEnvioConcurso($user,$enviar_a,$cid,$lenguaje,$codigo);           
            if($registro > 0)
                {               
                $sms="Código enviado correctamente.";
                echo '<script>alert("'.$sms.'")</script> ';
                echo "<script>location.href='verProblema.php?id=". $enviar_a  ."&cid=". $cid  ."'</script>";
                exit;
                    
            } else {
                $errors[] = "Error al enviar codigo";
                
            }
        } 
        

    endif;

    
?>

<html xml:lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="es_MX" http-equiv="Content-Language" />
    <script src="js/jquery-ui.custom.min.js"></script>
    <link media="all" href="css/dino_style.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="prototype-1.6.0.2.js"></script>
    <title>Enviar Problema</title>
    <style>

        .post form {
            width:70%;
            margin:auto;
            margin-top:30px;
            padding:30px;
            border:1px solid #bbb;
            -moz-border-radius:11px;
        }
        .post label{
            display:block;
            color:#777777;
            font-size:13px;
        }
        .post p{
            color:#777777;
            font-size:14px;
            text-align:justify;
            margin-bottom:20px;
        }
        
        .post>form input.text{
            background:#FBFBFB none repeat scroll 0 0;
            border:1px solid #E5E5E5;
            font-size:20px;
            margin-bottom:16px;
            margin-right:6px;
            margin-top:2px;
            padding:3px;
            width:97%;
            border-radius: 5px;
            height:33px;
        }
        .post select{
            background:#FBFBFB none repeat scroll 0 0;
            border:1px solid #E5E5E5;
            font-size: 12px;
            margin-bottom:16px;
            margin-right:6px;
            margin-top:2px;
            padding:3px;
            width:80%;
        }
        
        .post input.button {
            -moz-border-radius-bottomleft:6px;
            -moz-border-radius-bottomright:6px;
            -moz-border-radius-topleft:6px;
            -moz-border-radius-topright:6px;
            border:1px solid #AAAAAA;
            font-size:16px;
            padding:3px;
        }
        
        .right{
            text-align:right;
        }
        
    </style>
</head>
<body >
<div class="wrapper">
    <?php include_once("includes/head.php"); ?>
    <br><br>
<?php 
if(isset($_SESSION["UserName"])){
?>
<div class="post" style="background:white;">
            <form action="enviarproblemacontest.php" method="post" enctype="multipart/form-data">
            <p id="enviar">
            Enviar Soluci&oacute;n
            </p>
            <div align="center" >
            Codigo Fuente.
            </div>
                    <textarea 
                        cols        =   40 
                        rows        =   15 
                        id           =   "editor1" 
                        name="codigo"
                        placeholder =   'Pega el codigo fuente aqui'     
                        onmousemove =   "checkForText(this.value)"></textarea>
                    <br>
                    <div align="center" >
                    Lenguaje :
                    <select name="lenguaje">
                        <option value="3">Java</option>
                        <option value="0">C</option>
                        <option value="1">C++</option>
                        <option value="1">C++11</option>
                        <!-- <option value="php">PHP</option> -->                                                                                
                    </select>
                    <br><br>
                    <input type="hidden" name="enviar_a" value="<?php echo $_POST['enviar_a']; ?>">
                    <input type="hidden" name="cid" value="<?php echo $_POST['cid']; ?>">
                    <input type="submit" class="button" name="btnEnviar" value="Enviar" />
                    </div>
            </form> 
    </div>
     <div id="errores">
    
    </div>
<?php }else{ ?>
<div align='center'><h4>Debe iniciar sesi&oacute;n para enviar soluci&oacute;n.</h4></div> 
<?php 
}
?>
<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html> 
 

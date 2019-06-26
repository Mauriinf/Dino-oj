<?php 

    require_once("bootstrap.php");
    require_once 'ficheroGlobal.php';

?><html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/dino_style.css" />
        <title>Dino Online Judge - Usuarios</title>
            <script src="js/jquery-ui.custom.min.js"></script>
    <style type="text/css">
     
    .cabezera{
        text-align: center;
    }
    .paginador{
        margin-left: 20%;
       display: inline-block;
    }
    .paginador ul{
        padding: 15px;
        list-style: none;
        background: #FFF;
        margin-top: 0px;
       display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flex;
        display: -o-flex;
        display: flex;
        justify-content:flex-end;
        
        
    }
    .paginador a,.pageSelected{
        color: #428bca;
        border: 1px solid #ddd;
        padding: 3px;
        display: inline-block;
        font-size: 14px;
        text-align: center;
        width: 35px;
    }
    .paginador a:hover{
        background: #ddd;
    }
    .pageSelected{
        color: #FFF;
        background:#428bca;
        border:1px solid #428bca;
    }
    .divuno{
       display: inline-block;
    }

    @media screen and (max-width: 480px) {

    .paginador {
     margin-left: 0%;
    }     
    }
    </style>
    </head>
<body>

<div class="wrapper">
    <?php include_once("includes/head.php"); ?>
    <br><br>
    <?php include_once("includes/header.php"); ?>

    
   
<div class="post" style="background: white; border:1px solid #bbb;">
<div align="center">
    <h2>Usuarios</h2>

    <?php
    include_once("includes/db_con.php");
    
    if(empty($_POST['busqueda']))
        $busqueda="";
    else
    $busqueda=$_POST['busqueda'];
        //paginador
        $consulta2 = "SELECT count(*) as total_registro from users 
                                    where (user_id LIKE '%$busqueda%' or Nombre LIKE '%$busqueda%') and rol=0 or rol=1 and user_id<>'".$_SESSION["UserName"]. "'";
        //paginador
        $consulta2 = "SELECT count(*) as total_registro from users 
                                    where (user_id LIKE '%$busqueda%' or Nombre LIKE '%$busqueda%')
                                         AND rol=0 or rol=1 and user_id<>'".$_SESSION["UserName"]. "'";
    $sql_registe=mysqli_query($enlace,$consulta2);
    $resul_registe=mysqli_fetch_array($sql_registe);
    $total_registro=$resul_registe['total_registro'];
    $por_pagina=25;//mostrar 25 registro
    if(empty($_GET['pagina'])){
        $pagina=1;
    }else{
        $pagina=$_GET['pagina'];
    }
    $desde= ($pagina-1)*$por_pagina;
    $total_paginas=ceil($total_registro/$por_pagina);

    $consulta = "SELECT user_id,Nombre,Apellidos,Ubicacion,Institucion,Email,Estado,rol from users 
                                where (user_id LIKE '%$busqueda%' or Nombre LIKE '%$busqueda%')  and rol=0 or rol=1 and user_id<>'".$_SESSION["UserName"]. "'";

    $solved = array();

    //ordenar los problemas segun titulo vistos aceptados intentos
    if(isset($_GET["orden"])){
        if($_GET["orden"]=="Nombre"){
            $consulta .= (" ORDER BY " . mysqli_real_escape_string($enlace,$_GET["orden"])) ;
        }
        else{
            $consulta .= (" ORDER BY " . mysqli_real_escape_string($enlace,$_GET["orden"])) ;
        }
        
    }else{
        $consulta .= (" ORDER BY user_id") ;
    }
    $consulta.=" LIMIT ".$desde.",".$por_pagina;
    $resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());

    
    echo "Hay un total de <b>" . $total_registro . "</b> usuarios<br>";
    ?> 
</div>
    <br>   
    <div align="center" style="overflow-x: scroll;">
        <div class="cabezera">
        <div class="divuno">
        <form action="usuarios.php" method="post">
            <input style="width:200px; height:30px; font-size:16px; margin-bottom: 0px;" type="text" name="busqueda" placeholder="Nombre o Usuario" value="<?php if($busqueda!="") echo $busqueda; ?>">
            <input  type="submit" value="Buscar" class="btn btn-primary">
        </form>
        </div>
        <div class="paginador">
        <ul>
            <?php 
            $primera = $pagina - ($pagina % 5) + 1;
            if ($primera > $pagina) { $primera = $primera - 5; }
            $ultima = $primera + 4 > $total_paginas ? $total_paginas : $primera + 4;
            if($pagina!=1){
                $aux=$pagina-1;
                if(!empty($_GET["orden"])){
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina=1">|<<</a></li>';
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$aux.'"><<</a></li>';                   
                }else{
                        if(empty($_GET["orden"])){
                             echo '<li><a href="?pagina=1">|<<</a></li>';
                             echo '<li><a href="?pagina='.$aux.'"><<</a></li>';
                         }else{
                            
                                echo '<li><a href="?orden='.$_GET["orden"].'&pagina=1">|<<</a></li>';
                                echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$aux.'"><<</a></li>';
                        }
                }
            }
             ?>
        
            <?php 
            for ($i=$primera; $i <=$ultima ; $i++) { 
                if($i==$pagina){
                     echo '<li class="pageSelected">'.$i.'</li>';
                }
                else{
                    if(!empty($_GET["orden"]))
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$i.'">'.$i.'</a></li>';                   
                    else
                        if(empty($_GET["orden"]))
                             echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                         else
                            echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                }
            }
             ?>    
              <?php 
            if($pagina!=$total_paginas){
                $aux=$pagina+1;
                if(!empty($_GET["orden"])){                    
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$aux.'">>></a></li>';  
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$total_paginas.'">>>|</a></li>';                 
                }else{
                        if(empty($_GET["orden"])){
                            echo '<li><a href="?pagina='.$aux.'">>></a></li>';
                             echo '<li><a href="?pagina='.$total_paginas.'">>>|</a></li>';                         
                         }else{
                            echo '<li><a href="?pagina='.$aux.'">>></a></li>';
                            echo '<li><a href="?pagina='.$total_paginas.'">>>|</a></li>';                             
                        }
                }
            }
             ?>        
            
        </ul>
    </div>  
    </div>
    <table border='0' style="font-size: 20px;"> 
    <thead> <tr >
        <th width='10%'><a href="usuarios.php?orden=user_id">Usuario</a></th> 
        <th width='22%'><a href="usuarios.php?orden=Nombre">Nombre</a></th> 
        <th width='22%'><a >Apellidos</a></th> 
        
        <?php if(isset($_SESSION["rol"])  && ($_SESSION["rol"]==1|| $_SESSION["rol"]==2)){ ?>
        <th width='8%'><a >Activo</a></th>
        <th width='8%'><a >Admin</a></th>
        <?php } ?>
        </tr> 
    </thead> 
    <tbody>
    <?php

    $flag = true;
    $left = 0;
        while($row = mysqli_fetch_array($resultado)){
        //no muestra los problemas solucionados
        /*$ss = false;

        foreach ($solved as $probsolved) {
            if($row['ProbID'] == $probsolved)
                $ss = false;
        }
        
        if($ss)continue;*/
        if($flag){
                echo "<TR style=\"background:#e7e7e7;\">";
            $flag = false;
        }else{
                echo "<TR style=\"background:white;\">";
            $flag = true;
        }
        echo "<TD align='center' ><a href='userinfo.php?user=". htmlentities($row['user_id'])  ."'> &nbsp; ". $row['user_id']   ."</a> </TD>";
        echo "<TD align='center' ><a href='userinfo.php?user=". htmlentities($row['user_id'])  ."'> &nbsp; ". $row['Nombre']   ."</a> </TD>";
        echo "<TD align='center' ><a href='userinfo.php?user=". htmlentities($row['user_id'])  ."'> &nbsp; ". $row['Apellidos']   ."</a> </TD>";
    
       if(isset($_SESSION["rol"])  && ($_SESSION["rol"]==1|| $_SESSION["rol"]==2)){
        $usid=$row['user_id'];
            $v=$row['Estado'];
            echo "<TD align='center' style='color:#0F8BF2';>";
            if ($v==1) {
                $valor = "checked";
            }
            if ($v==0) {
                $valor="";
            }
             echo "<input type='checkbox' id='status' $valor onclick='OnChangeCheckbox (this)' data-id='$usid'/>"; 
           echo " </TD>";
           $r=$row['rol'];
           echo "<TD align='center' style='color:#0F8BF2';>";
            if ($r==1) {
                $aux = "checked";
            }
            if ($r==0) {
                $aux="";
            }
             echo "<input type='checkbox' id='admin' $aux onclick='chekar (this)' data-id='$usid'/>"; 
           echo " </TD>";
        }
        echo "</TR>";
        $left++;
    }

    if(isset($_GET["UserName"])){
        ?>
            <script>document.getElementById("probs_left").innerHTML = "<?php echo $left; ?>";</script>
        <?php
    }
    ?>      
    </tbody>
    </table>
    <?php if($total_registro==0) {

    ?>
    <P>No hay coincidencias en la búsqueda </P>
    <?php } ?> 
    </div>
    </div>
<script type="text/javascript">
        function OnChangeCheckbox (checkbox) {
            ajax = function(url){
                var xhr = window.XMLHttpRequest ? 
                          new XMLHttpRequest() : 
                          new ActiveXObject("Microsoft.XMLHTTP") || 
                          new ActiveXObject("Msxml2.XMLHTTP");
         
                xhr.open("GET", url, true);
                xhr.send(null);
            };
        var estado = checkbox.checked ? 1 : 0, //Si está marcado, asigno 1 a 'estado', si no, 0
        id = checkbox.getAttribute("data-id"), //El valor del pseudo-atributo 'data-id'
        url = "editar_estado.php?estado=" + estado + "&id=" + id;
        ajax(url); 

        if (estado==0) {
            alert("El registro se ha desactivado");
        };
        if (estado==1) {
            alert("El registro ha sido activado");
        };
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#id_sucursal").select2();
        });
    </script>
 <script type="text/javascript">
        function chekar (checkbox) {
            ajax = function(url){
                var xhr = window.XMLHttpRequest ? 
                          new XMLHttpRequest() : 
                          new ActiveXObject("Microsoft.XMLHTTP") || 
                          new ActiveXObject("Msxml2.XMLHTTP");
         
                xhr.open("GET", url, true);
                xhr.send(null);
            };
        var estado = checkbox.checked ? 1 : 0, //Si está marcado, asigno 1 a 'estado', si no, 0
        id = checkbox.getAttribute("data-id"), //El valor del pseudo-atributo 'data-id'
        url = "editar_rol.php?estado=" + estado + "&id=" + id;
        ajax(url); 

        if (estado==0) {
            alert("El registro se ha desactivado");
        };
        if (estado==1) {
            alert("El registro ha sido activado");
        };
        }
    </script>
      </script>
     




    <?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>

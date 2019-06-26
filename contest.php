<?php 

    require_once("bootstrap.php");
    require_once 'ficheroGlobal.php';
    date_default_timezone_set('America/La_Paz');
    $timestamp = date( "Y-m-d H:i:s" );
?><html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/dino_style.css" />
        <title>Dino Online Judge - Contest</title>
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
    <div><br><br></div>
    <?php include_once("includes/header.php"); ?>

    
   
    <div class="post" style="background: white; border:1px solid #bbb;">
<div align="center">
    <h2>Contest</h2>

    <?php
    include_once("includes/db_con.php");
     if(empty($_POST['busqueda']))
        $busqueda="";
    else
    $busqueda=$_POST['busqueda'];
    $consulta2 = "SELECT count(*) as total_registro from contest where (Titulo LIKE '%$busqueda%' or contest_id LIKE '%$busqueda%')";
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
    $consulta = "SELECT * from contest where (Titulo LIKE '%$busqueda%' or contest_id LIKE '%$busqueda%')";

       
    //ordenar los concursos segun titulo fecha
    if(isset($_GET["orden"])){
        if($_GET["orden"]=="Titulo"){
            $consulta .= (" ORDER BY " . mysqli_real_escape_string($enlace,$_GET["orden"])) ;
        }
        else{
            $consulta .= (" ORDER BY " . mysqli_real_escape_string($enlace,$_GET["orden"]." DESC")) ;
        }
        
    }else{
        $consulta .= (" ORDER BY contest_id DESC") ;
    }
    $consulta.=" LIMIT ".$desde.",".$por_pagina;
    $resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());

    


    if(isset($_SESSION["UserName"]) ){
        ?> 
        <div align="center">
        </div>
        <?php
    }

    ?>
    </div>
    <br>    <br>
    <div align="center" style="overflow-x: scroll;">
        <div class="cabezera">
        <div class="divuno">
        <form action="contest.php" method="post">
            <input style="width:200px; height:30px; font-size:16px; margin-bottom: 0px;" type="text" name="busqueda" placeholder="Titulo o ID" value="<?php if($busqueda!="") echo $busqueda; ?>">
            <input  type="submit" value="Buscar" class="btn btn-primary">
        </form>
        </div>
        <div class="paginador">
        <ul>
            <?php 
            if($pagina!=1){
                $aux=$pagina-1;               
                if(empty($_GET["orden"])){
                     echo '<li><a href="?pagina=1">|<<</a></li>';
                     echo '<li><a href="?pagina='.$aux.'"><<</a></li>';
                 }else{
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina=1">|<<</a></li>';
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$aux.'"><<</a></li>';
                    
                }
            }
             ?>
        
            <?php 
            $primera = $pagina - ($pagina % 5) + 1;
            if ($primera > $pagina) { $primera = $primera - 5; }
            $ultima = $primera + 4 > $total_paginas ? $total_paginas : $primera + 4;
            for ($i=$primera; $i <=$ultima ; $i++) {  
                if($i==$pagina){
                     echo '<li class="pageSelected">'.$i.'</li>';
                }
                else{
                    if(empty($_GET["orden"]))
                         echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
                     else                        
                    echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$i.'">'.$i.'</a></li>';
                }
            }
             ?>    
              <?php 
            if($pagina!=$total_paginas){
                $aux=$pagina+1;                
                if(empty($_GET["orden"])){
                    echo '<li><a href="?pagina='.$aux.'">>></a></li>';
                     echo '<li><a href="?pagina='.$total_paginas.'">>>|</a></li>';                         
                 }else{                   
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$aux.'">>></a></li>';
                        echo '<li><a href="?orden='.$_GET["orden"].'&pagina='.$total_paginas.'">>>|</a></li>';                         
                    
                }
            }
             ?>        
            
        </ul>
    </div>  
    </div>
    <table border='0' style="font-size: 20px;"> 
    <thead> <tr >
   
        <th width='5%'>ID</th> 
        <th width='25%'><a href="contest.php?orden=Titulo">Titulo</a></th> 
        <th width='14%'><a href="contest.php?orden=Inicio">Inicio</a></th> 
        <th width='14%'><a href="contest.php?orden=Final">Fin</a></th> 
        <th width='10%'><a >Estado</a></th> 
        <th width='10%'><a href="contest.php?orden=EsPrivado">Privado</a></th> 
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
        
    
        
        echo "<TD align='center' >". $row['contest_id'] ."</TD>";
        echo "<TD align='left' ><a href='contest_rank.php?cid=". $row['contest_id']  ."'> &nbsp; ". $row['Titulo']   ."</a> </TD>";
        echo "<TD align='center' style='color:#3A8935';>". $row['Inicio']   ." </TD>";
        echo "<TD align='center' style='color:#004d1a';>". $row['Final']   ." </TD>";
        $estado="";
        $color=0;
        if($timestamp< $row['Final']&& $timestamp>$row['Inicio']){
             $estado="Corriendo";
             $color=1;
        }
        else{
            if($timestamp> $row['Final'] ){
             $estado="Pasado";
            }
            else{
                $estado="Futuro";
                $color=2;
            }
        }
        if($color==0){
             echo "<TD align='center' style='color:#F2200F';>". $estado   ." </TD>";
        }
        else{
            if($color==1)
             echo "<TD align='center' style='color:#0F8BF2';>". $estado   ." </TD>";
         else
             echo "<TD align='center' style='color:#1CC810';>". $estado   ." </TD>";
        }
        $color=1;
        if($row['EsPrivado']==1){
            $private="Privado";
        }
        else{
            $private="Publico";
            $color=0;
        }
        if($color==1){
            echo "<TD align='center' style='color:#F2200F';>". $private   ." </TD>";
        }
        else{
            echo "<TD align='center' style='color:#0F8BF2';>". $private   ." </TD>";
        }
        
        echo "</TR>";
        $left++;
    }

    if(isset($_SESSION["UserName"])){
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




    <?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>

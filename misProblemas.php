<?php 

    require_once("bootstrap.php");
    require_once 'ficheroGlobal.php';

?><html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/dino_style.css" />
        <title>Dino Online Judge - Problem Set</title>
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

    
    <div class="post" style="background: white; border:2px solid #bbb; margin:auto; border-radius: 15px;">
        
<div align="center" >
    <h2>Problem-Set</h2>

    <?php
    include_once("includes/db_con.php");
     if(empty($_POST['busqueda']))
        $busqueda="";
    else
    $busqueda=$_POST['busqueda'];
    //paginador
    $consulta2 = "SELECT count(*) as total_registro from problem 
                            where (Titulo LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%') 
                            and user_id='".$_SESSION["UserName"]."'";
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
    $consulta = "SELECT problem_id, Titulo,Vistas, accepted, submit from problem 
                            where (Titulo LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%') 
                            and user_id='".$_SESSION["UserName"]."'";

    //ordenar los problemas segun titulo vistos aceptados intentos
    if(isset($_GET["orden"])){
        if($_GET["orden"]=="Titulo"){
            $consulta .= (" ORDER BY " . mysqli_real_escape_string($enlace,$_GET["orden"])) ;
        }
        else{
            $consulta .= (" ORDER BY " . mysqli_real_escape_string($enlace,$_GET["orden"])." DESC") ;
        }
        
    }else{
        $consulta .= (" ORDER BY problem_id") ;
    }
    $consulta.=" LIMIT ".$desde.",".$por_pagina;
    $resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());

    
    echo "Hay un total de <b>" . $total_registro . "</b> problemas creados por t&iacute<br>";

    ?>
    </div>
    <br>    <br>
    <div align="center" style="overflow-x: scroll;">
    <div class="cabezera">
    <div class="divuno">
    <form action="misProblemas.php" method="post">
        <input style="width:200px; height:30px; font-size:16px; margin-bottom: 0px;" type="text" name="busqueda" placeholder="Titulo o ID" value="<?php if($busqueda!="") echo $busqueda; ?>">
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
        <th width='5%'></th> 
        <th width='5%'>ID</th> 
        <th width='33%'><a href="misProblemas.php?orden=Titulo">Titulo</a></th> 
        <th width='12%'><a href="misProblemas.php?orden=Vistas">Vistas</a></th> 
        <th width='12%'><a href="misProblemas.php?orden=accepted">Aceptados</a></th> 
        <th width='12%'><a href="misProblemas.php?orden=submit">Intentos</a></th> 
        <th width='12%'><a>Radio</a></th>
        <th width='12%'><a>Acciones</a></th>
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

        if($row['submit']!=0)
            $ratio = ($row['accepted'] / $row['submit'])*100;
        else
            $ratio = "0.0";

        $ratio = substr($ratio, 0, 6);

        if($flag){
                echo "<TR style=\"background:#e7e7e7;\">";
            $flag = false;
        }else{
                echo "<TR style=\"background:white;\">";
            $flag = true;
        }
        //mostrar el icono de problema aceptado intentado 
        if(isset($_SESSION['UserName'])){
            $consult = "select problem_id from solution where user_id = '" . mysqli_real_escape_string($enlace,$_SESSION['UserName']) . "'AND problem_id='".$row['problem_id']."' AND result = 4";
            $respuesta = mysqli_query($enlace,$consult) or die('Algo anda mal: ' . mysqli_error());
            if(mysqli_num_rows($respuesta) > 0){
                echo "<TD align='center' ><img src='img/ok.png'/></TD>";

            }
            else{
                $consult = "select problem_id from solution where user_id = '" . mysqli_real_escape_string($enlace,$_SESSION['UserName']) . "'AND problem_id='".$row['problem_id']."'";
                $respuesta = mysqli_query($enlace,$consult) or die('Algo anda mal: ' . mysqli_error());
                if(mysqli_num_rows($respuesta) > 0){
                    echo "<TD align='center' ><img src='img/agt_update_critical.png'/></TD>";
                }else
                {
                    echo "<TD align='center' ></TD>"; 
                }
               
            }
        }
        else{
             echo "<TD align='center' ></TD>"; 
        }
        
        echo "<TD align='center' >". $row['problem_id'] ."</TD>";
        echo "<TD align='left' ><a href='verProblema.php?id=". $row['problem_id']  ."'>&nbsp;". $row['Titulo']   ."</a> </TD>";
        echo "<TD align='center' >". $row['Vistas']   ." </TD>";
        echo "<TD align='center' >". $row['accepted']   ." </TD>";
        echo "<TD align='center' >". $row['submit']   ." </TD>";
        printf("<TD align='center' >%2.2f%%</TD>", $ratio);
        echo "<TD align='center' style='color:#0F8BF2';>";
            echo '<a   href="editarProblema.php?id='.$row['problem_id'].'"><img src="img/edit.ico" width="32px" height="32px" /></a>';
            
         echo " </TD>";
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
    </div>
    </div>




    <?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>

<?php 
	require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" />
		<title>Dino Online Judge - Ranking</title>
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

	<div class="post_blanco" align="center">
	<h2>Ranking de Dino</h2>        
	<?php

	include_once("includes/db_con.php");
	 if(empty($_POST['busqueda']))
        $busqueda="";
    else
    $busqueda=$_POST['busqueda'];
	//paginador
    $consulta2 = "SELECT count(*) as total_registro from users 
    									where (user_id LIKE '%$busqueda%')
                                         AND Estado =1";
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
	//vamos a imprimir cosas del usuario

	$query = "SELECT user_id, institucion, solved, submit, ubicacion from users 
								where (user_id LIKE '%$busqueda%')
                                         AND rol<>2 AND Estado =1 order by ";


	if(isset($_GET["orden"])){
		switch ($_REQUEST["orden"] ) {
			case "ubicacion" : 	$query .= "ubicacion"; break;
			case "username" : 	$query .= "user_id"; break;
			case "institucion" :	$query .= "institucion"; break;
			case "TotalAceptados" : 	$query .= "solved desc"; break;
			case "TotalEnvios" : 	$query .= "submit desc"; break;
			default:
				$query .= "solved desc, solved";
		}		
	}else{
		$query .= "solved desc, solved";
	}
	 $query.=" LIMIT ".$desde.",".$por_pagina;	

	$resultado = mysqli_query($enlace,$query) or die('Algo anda mal: ' . mysqli_error());
	echo "<b> ". $total_registro . "</b> usuarios<br>";
	?>

	<div align="center" style="overflow-x: scroll;">
	<div class="cabezera">
    <div class="divuno">
    <form action="ranklist.php" method="post">
        <input style="width:200px; height:30px; font-size:16px; margin-bottom: 0px;" type="text" name="busqueda" placeholder="Usuario" value="<?php if($busqueda!="") echo $busqueda; ?>">
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
	<table border='0' style="font-size: 18px;" > 
	<thead> <tr >
		<th width='5%'><a>Rank</a></th> 
		<th width='5%'><a  href="ranklist.php?orden=username">Usuario</a></th> 
		<th width='15%'>
			<a href="ranklist.php?orden=ubicacion">
			Ubicacion
			</a>
		</th> 
		<th width='15%'><a  href="ranklist.php?orden=institucion">Institucion</a></th> 
		<th width='5%'><a href="ranklist.php?orden=TotalAceptados">Resueltos</a></th> 
		<th width='5%'><a  href="ranklist.php?orden=TotalEnvios">Envios</a></th> 
		<th width='5%'><a>Radio</a></th> 
		</tr> 
	</thead> 
	<tbody>
	<?php
	$rank = 1;
	$flag = true;
    	while($row = mysqli_fetch_array($resultado)){

		$nick = $row['user_id'];

		if( $row['solved'] != 0 )
			$ratio = substr( ($row['solved'] / $row['submit'])*100 , 0, 5);
		else
			$ratio = 0.0;
		
		//checar si hay una sesion y si si hay mostrar el usuario actual en cierto color
		if(isset($_SESSION['UserName']) &&  $_SESSION['UserName'] == $row['UserName'] ){
	        echo "<TR style=\"background:#566D7E; color:white;\">";

			$flag = !$flag;
		}else{ 
			if($flag){
				echo "<TR style=\"background:#e7e7e7;\">";
				$flag = false;
			}else{
				echo "<TR style=\"background:white;\">";
				$flag = true;
			}
		}

		echo "<TD align='center' >". $rank ."</TD>";
		
		if(isset($_SESSION['UserName']) &&  $_SESSION['UserName'] == $row['UserName'] ){
			echo "<TD align='center' ><a style=\"color:white;\" href='userinfo.php?user=". htmlentities($row['user_id'])  ."'>". $nick   ."</a> </TD>";
		}else{
			echo "<TD align='center' ><a style='color:#555;' href='userinfo.php?user=". htmlentities($row['user_id'])  ."'>". $nick   ."</a> </TD>";
		}
		echo "<TD align='center' >".  htmlentities(utf8_decode($row['ubicacion'])) ." </TD>";
		echo "<TD align='center' >".  htmlentities(utf8_decode($row['institucion'])) ." </TD>";
		echo "<TD align='center' >". $row['solved']  ." </TD>";
        echo "<TD align='center' >". $row['submit']   ." </TD>";
        //echo "<TD align='center' > {$ratio}% </TD>";
        printf("<TD align='center' > %2.2f%% </TD>", $ratio);
		echo "</TR>";
		$rank++;
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
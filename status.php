<?php 

	require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" />
		<title>Dino Online Judge - Runs</title>
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
	<?php

	include_once("includes/db_con.php");
	if(empty($_POST['busqueda']))
        $busqueda="";
    else
    $busqueda=$_POST['busqueda'];
	//paginador
	$res="";
	if(!empty($_POST['resul'])){
	if($_GET["resul"]==4) $res=4;
	if($_GET["resul"]==6) $res=6;
	if($_GET["resul"]==5) $res=5;
	if($_GET["resul"]==7) $res=7;
	if($_GET["resul"]==8) $res=8;
	if($_GET["resul"]==9) $res=9;
	if($_GET["resul"]==10) $res=10;
	if($_GET["resul"]==11) $res=11;
	if($_GET["resul"]==15) $res=15;
	}

	if(isset($_GET["user"])&&isset($_GET["resul"])){
		if($res==15){
			$consulta2 = "SELECT count(*) as total_registro from solution
    									where (user_id LIKE '%$busqueda%' or language LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%')
    									and user_id = '" . addslashes($_GET["user"]) . "' order by judgetime desc";
		}
    	else{
    		$consulta2 = "SELECT count(*) as total_registro from solution
    									where (user_id LIKE '%$busqueda%' or language LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%' )
    									and user_id = '" . addslashes($_GET["user"]) . "' and result = '".$res."' order by judgetime desc";
    	}
    }else{
    	$consulta2 = "SELECT count(*) as total_registro from solution
    									where (user_id LIKE '%$busqueda%' or language LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%' )";
    }
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
    //fin paginador
	$consulta = "SELECT solution_id, user_id, problem_id, result, time, memory, judgetime, language, contest_id FROM solution 
											where (user_id LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%' ) 
											order by judgetime desc";

	if(isset($_GET["user"])&&isset($_GET["resul"])){
		/*
		 * ejecuciones del usuario
		 * */
		$res="";

		if($_GET["resul"]==4) $res=4;
		if($_GET["resul"]==6) $res=6;
		if($_GET["resul"]==5) $res=5;
		if($_GET["resul"]==7) $res=7;
		if($_GET["resul"]==8) $res=8;
		if($_GET["resul"]==9) $res=9;
		if($_GET["resul"]==10) $res=10;
		if($_GET["resul"]==11) $res=11;
		if($_GET["resul"]==15) $res=15;
		//dejar esta consulta en 
		if($res==15){
		$consulta = "SELECT solution_id, user_id, problem_id, result, time, memory, judgetime, language, contest_id  FROM solution 
									where (user_id LIKE '%$busqueda%' or problem_id LIKE '%$busqueda%' )
									and user_id = '" . addslashes($_GET["user"]) . "' order by judgetime desc";
		}else{
			$consulta = "SELECT solution_id, user_id, problem_id, result, time, memory, judgetime, language, contest_id  FROM solution 
									where (user_id LIKE '%$busqueda%'  or problem_id LIKE '%$busqueda%' )
									and user_id = '" . addslashes($_GET["user"]) . "' and result = '".$res."' order by judgetime desc";
		}
		$consulta.=" LIMIT ".$desde.",".$por_pagina;
		$resultado = mysqli_query($enlace,$consulta);
		?>
			<div align="center">
				<h3>Run-Status</h3>
			</div>
			<div align="center" style="overflow-x: scroll;">
			<!-- <h2>Ultima actividad</h2> -->
		    <div class="divuno">
		    <form action="status.php?user=<?php echo addslashes($_GET["user"]); ?>&resul=<?php echo addslashes($_GET["resul"]); ?>" method="post">
		        <input style="width:200px; height:30px; font-size:16px; margin-bottom: 0px;" type="text" name="busqueda" placeholder="Usuario o Problema" value="<?php if($busqueda!="") echo $busqueda; ?>">
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
		                                
				             echo '<li><a href="?user='.$_GET["user"].'&resul='.$_GET["resul"].'&pagina=1">|<<</a></li>';
	                        echo '<li><a href="?user='.$_GET["user"].'&resul='.$_GET["resul"].'&pagina='.$aux.'"><<</a></li>';
			         	
		            }
		            for ($i=$primera; $i <=$ultima ; $i++) { 
		                if($i==$pagina){
		                     echo '<li class="pageSelected">'.$i.'</li>';
		                }
		                else{         
		                	                
	                        echo '<li><a href="?user='.$_GET["user"].'&resul='.$_GET["resul"].'&pagina='.$i.'">'.$i.'</a></li>';
			         		                    
		                }
		            }
		            if($pagina!=$total_paginas){
		                $aux=$pagina+1;     
		                              
				             echo '<li><a href="?user='.$_GET["user"].'&resul='.$_GET["resul"].'&pagina='.$aux.'">>></a></li>';
	                        echo '<li><a href="?user='.$_GET["user"].'&resul='.$_GET["resul"].'&pagina='.$total_paginas.'">>>|</a></li>';
			                      
		            }
		             ?>        
		            
		        </ul>
		    </div>  
			<table border='0' style="font-size: 17px;" > 
			<thead> <tr >
				<th width='5%'><a>Run ID</a></th> 
				<th width='5%'><a>Problema</a></th> 
				<th width='12%'><a>Usuario</a></th> 
				<th width='10%'><a>Lenguaje</a></th> 
				<th width='12%'><a>Resultado</a></th> 
				<th width='10%'><a>Tiempo</a></th> 
				<th width='10%'><a>Memoria</a></th> 
				<th width='12%'><a>Fecha</a></th>
				</tr> 
			</thead> 
			<tbody>
			<?php
			$flag = true;
		    	while($row = mysqli_fetch_array($resultado)){
				$color = "black";
				$ESTADO = $row['result'];
			
				

				$nick = htmlentities( $row['user_id'] );

				//checar si hay una sesion y si si hay mostrar el usuario actual en cierto color
				//$foobar = $row['EjecID'];
				$tooltip = "Ver este Codigo";
				if( $row["contest_id"] != -1 ){
					//$foobar = "" . $row['EjecID'] . "*";
					$tooltip = "Este run fue parte de algun concurso online";
				}


				if($flag){
					echo "<TR style=\"background:#e7e7e7;\">";
					$flag = false;
				}else{
					echo "<TR style=\"background:white;\">";
					$flag = true;
				}

				$result="";
				//color them statusessss
				switch($row['result']){

					
					case "0":
						$result = "<span style='color:purple;'>Pending</span>";
					break;
					case "1":
						$result = "<span style='color:purple;'>Pending Rejudge</span>";
					break;
					case "2":
						$result = "<span style='color:red;'>Compiling</span>";
					break;
					case "3":
						$result = "<span style='color:purple;'>Judging...</span>";	//<img src='img/load.gif'>
					break;
					//case "Running":
					//	CurrentRuns[a].Estado = "<span style='color:purple;'>" + CurrentRuns[a].Estado + "...</span>";	//<img src='img/load.gif'>
					//break;
					case "4":
						$result = "<span style='color:green;'><b>Accepted</b></span>";
					break;
					case "5":
						$result = "<span style='color:brown;'><b>Presentation Error</b></span>";
					break;
					case "6":
						$result = "<span style='color:red;'><b>Wrong Answer</b></span>";				
					break;
					case "7": 
						$result = "<span style='color:brown;'>Time Limit Exceeded</span>";
					break;
					case "8":
						$result = "<span style='color:brown;'><b>Memory Limit Exceeded</b></span>";
					break;								
					case "9":
						$result= "<span style='color:red;'><b>Output Limit Exceeded</b></span>";				
					break;
					case "10":
						$result = "<span style='color:blue;'><b>Runtime Error</b></span>";				
					break;							
					case "11":
						$result = "<span style='color:red;'><b>Compile Error</b></span>";				
					break;
													
				}
				$leng="";
				switch($row['language']){

					
					case "0":
						$leng = "<span style='color:green;'>C</span>";
					break;
					case "1":
						$leng = "<span style='color:green;'>C++</span>";
					break;
					case "2":
						$leng = "<span style='color:red;'>Pas</span>";
					break;
					case "3":
						$leng = "<span style='color:brown;'>Java</span>";	//<img src='img/load.gif'>
					break;
					case "4":
						$leng= "<span style='color:green;'><b>rb</b></span>";
					break;
					case "5":
						$leng = "<span style='color:brown;'><b>sh</b></span>";
					break;
					case "6":
						$leng= "<span style='color:red;'><b>py</b></span>";				
					break;
					case "7": 
						$leng = "<span style='color:brown;'>php</span>";
					break;
					case "8":
						$leng = "<span style='color:brown;'><b>pl</b></span>";
					break;								
					case "9":
						$leng = "<span style='color:red;'><b>cs</b></span>";				
					break;
					case "10":
						$leng= "<span style='color:blue;'><b>m</b></span>";				
					break;							
					case "11":
						$leng = "<span style='color:red;'><b>bas</b></span>";				
					break;
					case "12":
						$leng = "<span style='color:red;'><b>scm</b></span>";				
					break;
					case "13":
						$leng = "<span style='color:red;'><b>C</b></span>";				
					break;
					case "14":
						$leng = "<span style='color:red;'><b>CC</b></span>";				
					break;
					case "15":
						$leng = "<span style='color:red;'><b>py</b></span>";				
					break;
					case "16":
						$leng = "<span style='color:red;'><b>cc</b></span>";				
					break;

													
				}
				echo "<TD align='center' ><a style='color:#555;' title='$tooltip' href='verCodigo.php?execID={$row['solution_id']}'>". $row['solution_id']  ."</a></TD>";
				echo "<TD align='center' ><a style='color:#555;' title='". $row["problem_id"] ."' href='verProblema.php?id=". $row['problem_id']  ."'>". $row['problem_id']   ."</a> </TD>";
				echo "<TD align='center' ><a style='color:#555;' title='Ver perfil' href='userinfo.php?user=". $row['user_id']  ."'>". $nick   ."</a> </TD>";
				echo "<TD align='center' >". $leng   ."</TD>";
				echo "<TD align='center' >".$result."</TD>";
				printf("<TD align='center' >%1.3fs </TD>", $row['time'] / 1000);
				echo "<TD align='center' >". $row['memory']   ." </TD>";
				echo "<TD align='center' >". $row['judgetime']   ." </TD>";
				echo "</TR>";
			}
			?>		
			</tbody>
			</table>

			</div>		
		<?php
	}else{
		$consulta.=" LIMIT ".$desde.",".$por_pagina;
		$resultado = mysqli_query($enlace,$consulta);
			?>
			<div align="center">
				<h2>Run-Status</h2>
			</div>
			<div align="center" style="overflow-x: scroll;">
			<!-- <h2>Ultima actividad</h2> -->
		    <div class="divuno">
		    <form action="status.php" method="post">
		        <input style="width:200px; height:30px; font-size:16px; margin-bottom: 0px;" type="text" name="busqueda" placeholder="User o Problema" value="<?php if($busqueda!="") echo $busqueda; ?>">
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
			             echo '<li><a href="?pagina=1">|<<</a></li>';
			             echo '<li><a href="?pagina='.$aux.'"><<</a></li>';
		            }
		            for ($i=$primera; $i <=$ultima ; $i++) { 
		                if($i==$pagina){
		                     echo '<li class="pageSelected">'.$i.'</li>';
		                }
		                else{                    
		                    echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';                       
		                }
		            }
		            if($pagina!=$total_paginas){
		                $aux=$pagina+1;                
		                echo '<li><a href="?pagina='.$aux.'">>></a></li>';
		                 echo '<li><a href="?pagina='.$total_paginas.'">>>|</a></li>';                   
		            }
		             ?>        
		            
		        </ul>
		    </div>  
			<table border='0' style="font-size: 17px;" > 
			<thead> <tr >
				<th width='5%'><a>Run ID</a></th> 
				<th width='5%'><a>Problema</a></th> 
				<th width='12%'><a>Usuario</a></th> 
				<th width='10%'><a>Lenguaje</a></th> 
				<th width='12%'><a>Resultado</a></th> 
				<th width='10%'><a>Tiempo</a></th> 
				<th width='10%'><a>Memoria</a></th> 
				<th width='12%'><a>Fecha</a></th>
				</tr> 
			</thead> 
			<tbody>
			<?php
			$flag = true;
		    	while($row = mysqli_fetch_array($resultado)){
				$color = "black";
				$ESTADO = $row['result'];
			
				

				$nick = htmlentities( $row['user_id'] );

				//checar si hay una sesion y si si hay mostrar el usuario actual en cierto color
				//$foobar = $row['EjecID'];
				$tooltip = "Ver este Codigo";
				if( $row["contest_id"] != -1 ){
					//$foobar = "" . $row['EjecID'] . "*";
					$tooltip = "Este run fue parte de algun concurso online";
				}


				if($flag){
					echo "<TR style=\"background:#e7e7e7;\">";
					$flag = false;
				}else{
					echo "<TR style=\"background:white;\">";
					$flag = true;
				}

				$result="";
				//color them statusessss
				switch($row['result']){

					
					case "0":
						$result = "<span style='color:purple;'>Pending</span>";
					break;
					case "1":
						$result = "<span style='color:purple;'>Pending Rejudge</span>";
					break;
					case "2":
						$result = "<span style='color:red;'>Compiling</span>";
					break;
					case "3":
						$result = "<span style='color:purple;'>Judging...</span>";	//<img src='img/load.gif'>
					break;
					//case "Running":
					//	CurrentRuns[a].Estado = "<span style='color:purple;'>" + CurrentRuns[a].Estado + "...</span>";	//<img src='img/load.gif'>
					//break;
					case "4":
						$result = "<span style='color:green;'><b>Accepted</b></span>";
					break;
					case "5":
						$result = "<span style='color:brown;'><b>Presentation Error</b></span>";
					break;
					case "6":
						$result = "<span style='color:red;'><b>Wrong Answer</b></span>";				
					break;
					case "7": 
						$result = "<span style='color:brown;'>Time Limit Exceeded</span>";
					break;
					case "8":
						$result = "<span style='color:brown;'><b>Memory Limit Exceeded</b></span>";
					break;								
					case "9":
						$result= "<span style='color:red;'><b>Output Limit Exceeded</b></span>";				
					break;
					case "10":
						$result = "<span style='color:blue;'><b>Runtime Error</b></span>";				
					break;							
					case "11":
						$result = "<span style='color:red;'><b>Compile Error</b></span>";				
					break;
													
				}
				$leng="";
				switch($row['language']){

					
					case "0":
						$leng = "<span style='color:brown;'><b>C</b></span>";
					break;
					case "1":
						$leng = "<span style='color:blue;'><b>C++</b></span>";
					break;
					case "2":
						$leng = "<span style='color:red;'>Pas</span>";
					break;
					case "3":
						$leng = "<span style='color:orange;'><b>Java</b></span>";	//<img src='img/load.gif'>
					break;
					case "4":
						$leng= "<span style='color:green;'><b>rb</b></span>";
					break;
					case "5":
						$leng = "<span style='color:brown;'><b>sh</b></span>";
					break;
					case "6":
						$leng= "<span style='color:red;'><b>py</b></span>";				
					break;
					case "7": 
						$leng = "<span style='color:brown;'>php</span>";
					break;
					case "8":
						$leng = "<span style='color:brown;'><b>pl</b></span>";
					break;								
					case "9":
						$leng = "<span style='color:red;'><b>cs</b></span>";				
					break;
					case "10":
						$leng= "<span style='color:blue;'><b>m</b></span>";				
					break;							
					case "11":
						$leng = "<span style='color:red;'><b>bas</b></span>";				
					break;
					case "12":
						$leng = "<span style='color:red;'><b>scm</b></span>";				
					break;
					case "13":
						$leng = "<span style='color:brown;'><b>C</b></span>";				
					break;
					case "14":
						$leng = "<span style='color:blue;'><b>CC</b></span>";				
					break;
					case "15":
						$leng = "<span style='color:red;'><b>py</b></span>";				
					break;
					case "16":
						$leng = "<span style='color:blue;'><b>cc</b></span>";				
					break;

													
				}

				echo "<TD align='center' ><a style='color:#555;' title='$tooltip' href='verCodigo.php?execID={$row['solution_id']}'>". $row['solution_id']  ."</a></TD>";
				echo "<TD align='center' ><a style='color:#555;' title='". $row["problem_id"] ."' href='verProblema.php?id=". $row['problem_id']  ."'>". $row['problem_id']   ."</a> </TD>";
				echo "<TD align='center' ><a style='color:#555;' title='Ver perfil' href='userinfo.php?user=". $row['user_id']  ."'>". $nick   ."</a> </TD>";
				echo "<TD align='center' >". $leng  ."</TD>";
				echo "<TD align='center' >".$result."</TD>";
				printf("<TD align='center' >%1.3fs </TD>", $row['time'] / 1000);
				echo "<TD align='center' >". $row['memory']   ." </TD>";
				echo "<TD align='center' >". $row['judgetime']   ." </TD>";
				echo "</TR>";
			}
			?>		
			</tbody>
			</table>
			<?php if($total_registro==0) {

		    ?>
		    <P>No hay coincidencias en la búsqueda </P>
		    <?php } ?> 
			</div>
			<?php
	}
	
	?>

	
	</div>
	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>

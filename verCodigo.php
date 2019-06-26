<?php 

    require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';
date_default_timezone_set('America/La_Paz');
?>
<html>
<head>
		<link rel="stylesheet" type="text/css" href="css/Dino_style.css" />
		<title>Dino Online Judge - Ver Codigo Fuente</title>
			<script src="js/jquery-ui.custom.min.js"></script>
		<link type="text/css" rel="stylesheet" href="css/SyntaxHighlighter.css">
		<script language="javascript" src="js/shCore.js"></script>
		<script language="javascript" src="js/shBrushCSharp.js"></script>
		<script language="javascript" src="js/shBrushJava.js"></script>
		<script language="javascript" src="js/shBrushCpp.js"></script>
		<script language="javascript" src="js/shBrushPython.js"></script>
		<script language="javascript" src="js/shBrushXml.js"></script>
</head>
<body>

<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
    <div><br><br><br></div>
    <?php include_once("includes/header.php"); ?>

	<div class="post" style="background:white;">

	<h2>Revisar un codigo fuente</h2>

<?php



	function mostrarCodigo( $lenguaje, $execID , $row){

		$file  = "../codigos/" . $execID  ;

		switch($lenguaje){
			case "JAVA": 	$file .= ".java"; 	$sintaxcolor = "java"; 		break;
			case "C": 		$file .= ".c"; 		$sintaxcolor = "c"; 		break;
			case "C++": 	$file .= ".cpp"; 	$sintaxcolor = "cpp"; 		break;
			default : 		$file .= ".java"; 	$sintaxcolor = "java";
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
				switch($lenguaje){

					
					case "0":
						$leng = "<span style='color:brown;'><b>C</b></span>"; $sintaxcolor = "c"; 
					break;
					case "1":
						$leng = "<span style='color:blue;'><b>C++</b></span>"; $sintaxcolor = "cpp";
					break;
					case "2":
						$leng = "<span style='color:red;'>Pas</span>"; $sintaxcolor = "java";
					break;
					case "3":
						$leng = "<span style='color:orange;'><b>Java</b></span>"; $sintaxcolor = "java";	//<img src='img/load.gif'>
					break;
					case "4":
						$leng= "<span style='color:green;'><b>rb</b></span>"; $sintaxcolor = "java";
					break;
					case "5":
						$leng = "<span style='color:brown;'><b>sh</b></span>"; $sintaxcolor = "java";
					break;
					case "6":
						$leng= "<span style='color:red;'><b>py</b></span>";		 $sintaxcolor = "java";		
					break;
					case "7": 
						$leng = "<span style='color:brown;'>php</span>"; $sintaxcolor = "java";
					break;
					case "8":
						$leng = "<span style='color:brown;'><b>pl</b></span>"; $sintaxcolor = "java";
					break;								
					case "9":
						$leng = "<span style='color:red;'><b>cs</b></span>";		 $sintaxcolor = "java";		
					break;
					case "10":
						$leng= "<span style='color:blue;'><b>m</b></span>";		 $sintaxcolor = "java";		
					break;							
					case "11":
						$leng = "<span style='color:red;'><b>bas</b></span>";		 $sintaxcolor = "java";		
					break;
					case "12":
						$leng = "<span style='color:red;'><b>scm</b></span>";	 $sintaxcolor = "java";			
					break;
					case "13":
						$leng = "<span style='color:brown;'><b>C</b></span>";	$sintaxcolor = "c"; 			
					break;
					case "14":
						$leng = "<span style='color:blue;'><b>CC</b></span>";	$sintaxcolor = "cpp";			
					break;
					case "15":
						$leng = "<span style='color:red;'><b>py</b></span>";	 $sintaxcolor = "java";			
					break;
					case "16":
						$leng = "<span style='color:blue;'><b>cc</b></span>";	$sintaxcolor = "cpp";			
					break;

													
		}
		$codigo=$row["Source"]

		
		?>
		<div class="post">
			<div align=center >
				<table border='0' style="font-size: 14px;" > 
				<thead> <tr >
					<th width='12%'>EjecID</th> 
					<th width='12%'>Usuario</th> 
					<th width='12%'>Lenguaje</th> 
					<th width='12%'>Resultado</th> 
					<th width='10%'>Tiempo</th>
					 <th width='10%'>Memoria</th> 
					<th width='14%'>Fecha</th>
					</tr> 
				</thead> 
				<tbody>
				<?php
						$nick = $row['user_id'];
			        	echo "<TR style=\"background:#e7e7e7;\">";
						$cuando = date("F j, Y h:i:s A", strtotime($row['judgetime']));
						echo "<TD align='center' >". $row['solution_id'] ."</TD>";
						echo "<TD align='center' ><a href='runs.php?user=". $row['user_id']  ."'>". $nick   ."</a> </TD>";
						echo "<TD align='center' >".$leng  ."</TD>";
						echo "<TD align='center' >". $result  ."</TD>";
						echo "<TD align='center' ><b>". $row['time'] / 1000  ."</b> Segundos </TD>";
						echo "<TD align='center' >". $row['memory'] ." </TD>";
						echo "<TD align='center' >". $cuando   ." </TD>";
						echo "</TR>";

				?>		
				</tbody>
				</table>
			</div>
			&nbsp;
		</div>
		
		<?php
		
		echo "<textarea name=\"code\" class=\"$sintaxcolor\" cols=\"60\" rows=\"10\">{$codigo}</textarea>";
	}


	// --- revisar login ---
	function revisarLogin(){
		global $enlace;
		$asdf =  mysqli_real_escape_string($enlace,$_REQUEST["execID"]);
		$consulta = "select * from solution where BINARY ( solution_id = '{$asdf}' )";
		$resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());
	
		if(mysqli_num_rows($resultado) != 1){
			echo "<b>Este codigo no existe</b>";
			return;
		}

		$row = mysqli_fetch_array($resultado);

		if(!isset($_SESSION['UserName'])){
			?> <div align='center'> Inicia sesion con la barra de arriba para comprobar que este codigo es tuyo. </div> <?php
			return;
		}

		if( ($row['user_id'] == $_SESSION['UserName']) ){
			//este codigo es tuyo o eres OWNER
			mostrarCodigo($row['language'], $_REQUEST["execID"] , $row);
	
		}else{
			
			
			//no puedes ver codigos que estan mal
			if($row['result'] != 4){
				?><div style="font-size: 16px;"> <img src="img/12.png">No puedes ver códigos que no están aceptados aunque cumplas con los requisitos.</div><?php
				return;
			}
			
			//no puedes ver codigos que son parte de algun concurso
			if($row['contest_id'] != "-1"){
				?><div style="font-size: 16px;"> <img src="img/12.png">No puedes ver codigos que pertenecen a un concurso aunque cumplas con los requisitos.</div><?php
				return;
			}
			
			//este codigo no es tuyo, pero vamos a ver si ya lo resolviste con mejor tiempo y que no sea parte de un concurso
			$consulta = "select * from solution where problem_id = '". $row['problem_id'] ."' AND user_id = '". $_SESSION['UserName'] ."' AND time < " . $row['time'] . " AND result = 4 ;";
			$resultado2 = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error($enlace));
			$nr = mysqli_num_rows($resultado2);
			
			if($nr >= 1){
				//ok, te lo voy a mostrar...
				?><div style="font-size: 16px;"> <img src="img/49.png">Este codigo no es tuyo, pero lo puedes ver porque ya lo resolviste con un mejor tiempo.</div><?php
				mostrarCodigo($row['language'], $_REQUEST["EjecID"] , $row );
			}else{
				//no cumples con los requisitos
				?> 	
					<div align='center'> 
						<h2>Disculpa</h2> 
						<br>
						<div style="font-size: 16px;"> <img src="img/12.png">Estas intentado ver un codigo que no es tuyo. Para poder verlo tienes que resolver este problema y tener un mejor tiempo que el codigo que quieres ver.</div>
					</div> 
				<?php
			}
			

		}

	}


	// --- conectarse a la bd ---
	include_once("includes/db_con.php");


	revisarLogin();

	// --- cerrar conexion ---
	if( isset($resultado))
		 mysqli_free_result($resultado);
	if( isset($enlace))
		mysqli_close($enlace);
?>

	
	</div>





	<?php include_once("includes/footer.php"); ?>

</div>


<script language="javascript">
window.onload = function () {

    dp.SyntaxHighlighter.ClipboardSwf = 'flash/clipboard.swf';
    dp.SyntaxHighlighter.HighlightAll('code');
}

</script>
<?php include("includes/ga.php"); ?>
</body>
</html>


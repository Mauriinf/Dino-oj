<?php 
    require_once("bootstrap.php");	
require_once 'ficheroGlobal.php';


?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" />
		<title>Dino Online Judge - Ver Problema</title>
			<script src="js/jquery-ui.custom.min.js"></script>
	</head>
<body>

<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
    <div><br><br><br></div>
    <?php include_once("includes/header.php"); ?>
	
	<div class="post">
       	<?php
	
	include_once("includes/db_con.php");

	$consulta = "select Titulo,Autor, Descripcion,Entrada,Salida,EjemploEntrada,EjemploSalida, time_limit, accepted, submit from problem where problem_id = '" . addslashes($_GET["id"]) . "';";
	$resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());
	$row = mysqli_fetch_array($resultado);

	if(mysqli_num_rows($resultado) == 1){
		$tiempo = $row['time_limit'] / 1000;
		echo '<div align="center">';
		echo "<h2>" . $_GET["id"] . ". " . $row['Titulo'] ."</h2>";
		echo "<p>" . "Autor: " . $row['Autor'] ."</p>";
		echo "<p>Limite de tiempo : <b>" . $tiempo . "</b> seg. &nbsp;&nbsp;";
		echo "Total runs : <b>" . $row['submit'] . "</b>&nbsp;&nbsp;";
		echo "Aceptados : <b>" . $row['accepted'] . "</b></p> ";
		echo '</div>';
		echo $row['Descripcion'] . "</p> ";
		echo "<h3>Entrada</h3>";
		$entrada=$row['Entrada'];
		echo str_replace("\n", "<br>", $entrada);
		echo "<h3>Salida</h3>";
		$salida=$row['Salida'];
		echo str_replace("\n", "<br>", $salida);
		echo "<h3>Ejemplo Entrada</h3>";
		$ejentrada=$row['EjemploEntrada'];
		echo str_replace("\n", "<br>", $ejentrada);
		echo "<h3>Ejemplo Salida</h3>";
		$ejsalida=$row['EjemploSalida'];
		echo str_replace("\n", "<br>", $ejsalida);
		$consulta = "UPDATE problem 
					SET Vistas = (vistas + 1) 
					WHERE problem_id = \"".mysqli_real_escape_string($enlace,$_GET["id"])."\" 
					LIMIT 1 ";	

		mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());
	

	if(!isset($_REQUEST['cid'])){
		//si no es concurso
		?>
		<div align="center">
			<form action="enviar.php" method="post">
				<input type="hidden" name="enviar_a" value="<?php echo $_GET['id']; ?>">
				<input class="btn btn-success" type="submit" value="Enviar solucion">
			</form>
		</div>
		<?php

	}else{
		//si es concurso


		?>
		<div align="center">
			<form action="enviarproblemacontest.php" method="post">
				<input type="hidden" name="enviar_a" value="<?php echo $_REQUEST['id']; ?>">
		    	<input type="hidden" name="cid" value="<?php echo $_REQUEST['cid']; ?>">
				<input class="btn btn-success" type="submit" value="Enviar solucion">
			</form>
		</div>		
		<?php
	}

		// <-- php 
		}else{
			echo "<div align='center'><h2>El problema " . $_GET["id"] . " no existe.</h2></div>";
		}
		//<-- php
	?>
</div>

<?php
	if(!isset($_REQUEST['cid'])){
?>
	<div class="post" style="background: white; border:1px solid #bbb;">
		<?php
		// mejores tiempos !
		$consulta = "SELECT DISTINCT  user_id ,  solution_id ,  result , MIN(  time ) as 'time' , judgetime as Fecha ,language  FROM  solution WHERE (	problem_id =  ". mysqli_real_escape_string($enlace,$_GET["id"]) ."	AND result =  4	)	GROUP BY  user_id	 order by time asc LIMIT 5";
		$resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());
		?>

		<div align="center" >
		<h3>Top 5 tiempos para este problema</h3><br>

		<table border='0' style="font-size: 14px;" > 
		<thead> <tr >
			<th width='12%'>EjecID</th> 
			<th width='12%'>Usuario</th> 
			<th width='12%'>Lenguaje</th> 

			<th width='12%'>Tiempo</th> 
			<th width='12%'>Fecha</th>
			</tr> 
		</thead> 
		<tbody>
		<?php
		$flag = true;
	    	while($row = mysqli_fetch_array($resultado)){

				$nick = $row['user_id'];


				if($flag){
		        	echo "<TR style=\"background:#e7e7e7;\">";
					$flag = false;
				}else{
		        	echo "<TR style=\"background:white;\">";
					$flag = true;
				}

				$cuando = date("F j, Y", strtotime($row['Fecha']));
				echo "<TD align='center' ><a href='verCodigo.php?EjecID={$row['solution_id']}'>". $row['solution_id'] ."</a></TD>";
				echo "<TD align='center' ><a href='runs.php?user=". $row['user_id']  ."'>". $nick   ."</a> </TD>";
				echo "<TD align='center' >". $row['language']   ."</TD>";
				echo "<TD align='center' ><b>". $row['time'] / 1000  ."</b> Segundos </TD>";
				echo "<TD align='center' >". $cuando   ." </TD>";
				echo "</TR>";
		}
		?>		
		</tbody>
		</table>
		</div>
	</div>
	<?php
	
	}
	?>


	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>
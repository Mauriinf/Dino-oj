<?php 

	require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" />
		<title>Dino Online Judge - Usuario</title>
			<script src="js/jquery-ui.custom.min.js"></script>
	<style type="text/css">
	tr{
		border-top: 1px solid #ddd;
		
	}
	td{
		padding: 10px;
		padding-left: 20px;
	}
	.estadisticas{
		display: inline-block;
	}
	.tabla{
		display: inline-block;
	}
	</style>
	</head>
<body>

<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br><br></div>
	<div class="post_blanco">
	<?php

	include_once("includes/db_con.php");

    //fin paginador
	$consulta = "SELECT solution_id, user_id, problem_id, result, time, memory, judgetime, language, contest_id FROM solution order by judgetime desc";

	if(isset($_GET["user"])){
		/*
		 * ESTADISTICAS DEL USUARIO
		 * */
		
		
		//concursos
     	$sql = 'SELECT DISTINCT contest_id FROM  solution WHERE contest_id > 0 AND user_id =  "' . addslashes($_GET['user']) . '"';
		$resultado = mysqli_query($enlace,$sql) or die('Algo anda mal: ' . mysqli_error());
		$ncontests = mysqli_num_rows($resultado);
						
		//vamos a imprimir cosas del usuario
		$query2 = "SELECT user_id, Nombre, solved, submit, Institucion, Ubicacion, Email, TiempoRegistro from users where user_id = '" . addslashes($_GET['user']) . "'";
		$resultado = mysqli_query($enlace,$query2) or die('Algo anda mal: ' . mysqli_error());
	
		if(mysqli_num_rows($resultado) != 1){
			echo "<h2>Ups</h2>";
			echo "Este usuario no existe";
			return;
		}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE user_id ='".addslashes($_GET['user'])."'")){ $total= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 4 and user_id ='".addslashes($_GET['user'])."'")){ $AC= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 5 and user_id ='".addslashes($_GET['user'])."'")){ $PE= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 6 and user_id ='".addslashes($_GET['user'])."'")){ $WA= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 7 and user_id ='".addslashes($_GET['user'])."'")){ $TLE= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 8 and user_id ='".addslashes($_GET['user'])."'")){ $MLE= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 9 and user_id ='".addslashes($_GET['user'])."'")){ $OLE= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 10 and user_id ='".addslashes($_GET['user'])."'")){ $RE= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 11 and user_id ='".addslashes($_GET['user'])."'")){ $CE= mysqli_num_rows($result);}
		$row = mysqli_fetch_array($resultado);
		echo "		<h2>Información de " . htmlentities(utf8_decode( $_GET['user'])) . "</h2>";
		?>
			<div class="tabla">
			<table style="width: 80%;" border=0>
			<tr><td><b>Nombre de usuario</b></td><td><?php echo $_GET["user"]; ?></td></tr>
			<tr><td><b>Nombre</b></td><td><?php echo htmlentities(utf8_decode( $row['Nombre'])); ?></td></tr>
			<tr><td><b>Escuela</b></td><td><?php echo htmlentities(utf8_decode( $row['Institucion'])); ?></td></tr>
			<tr><td><b>País</b></td><td><?php echo htmlentities(utf8_decode( $row['Ubicacion'])); ?></td></tr>
			<tr><td><b>Email</b></td><td><?php echo htmlentities(utf8_decode( $row['Email'])); ?></td></tr>
			<tr><td><b>Hora de registro</b></td><td><?php echo htmlentities(utf8_decode( $row['TiempoRegistro'])); ?></td></tr>
			<tr><td><b>Aceptados</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=4"><?php echo $AC; ?></a></td></tr>
			<tr><td><b>Enviados</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=15"><?php echo $total; ?></a></td></tr>
			<tr><td><b>Concursos</b></td><td><?php echo $ncontests; ?></td></tr>
			<tr><td></td><td></td></tr>
			</table>
			</div>
		<div class="estadisticas">
			<table style="width: 80%;" border=0>
			<tr><td><b>AC</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=4"><?php echo $AC; ?></a></td></tr>
			<tr><td><b>WA</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=6"><?php echo $WA; ?></a></td></tr>
			<tr><td><b>PE</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=5"><?php echo $PE; ?></a></td></tr>
			<tr><td><b>TLE</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=7"><?php echo $TLE; ?></a></td></tr>
			<tr><td><b>MLE</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=8"><?php echo $MLE; ?></a></td></tr>
			<tr><td><b>OLE</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=9"><?php echo $OLE; ?></a></td></tr>
			<tr><td><b>RE</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=10"><?php echo $RE; ?></a></td></tr>
			<tr><td><b>CE</b></td><td><a href="status.php?user=<?php echo $_GET['user'] ?>&resul=11"><?php echo $CE; ?></a></td></tr>
		</table>
		<?php
		//CALCULO DE EESTADISTICAS
		if($total == 0) $total = 1;
		$AC = ($AC * 100)/$total;
		$WA = ($WA * 100)/$total;
		$PE = ($PE * 100)/$total;
		$TLE = ($TLE * 100)/$total;
		$MLE = ($MLE * 100)/$total;
		$OLE = ($OLE * 100)/$total;
		$RE = ($RE * 100)/$total;
		$CE = ($CE * 100)/$total;
		?>
		<h4>Estadisticas</h4>
		<img src="http://chart.apis.google.com/chart?
			chs=500x225
		&amp;	cht=p3
		&amp;   chco=3D7930
		&amp;	chd=t:<?php print($AC.','.$WA.','.$PE.','.$TLE.','.$MLE.','.$OLE.','.$RE.','.$CE); ?>
		&amp;	chl=AC|WA|PE|TLE|MLE|OLE|RE|CE"
		alt="Estadisticas de usuario" />

  		</div>	
		<?php
		
	}else{
			?>
			<div align="center">
				<h2>Algo anda mal</h2>
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

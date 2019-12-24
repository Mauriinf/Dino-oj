<?php
	session_start();
	include_once("config.php");
	include_once("includes/db_con.php");
	$id = 0;

	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}

	if ( !empty($_POST)) {
		$id = $_POST['id'];
		$query = "DELETE FROM ConcursoProblema  WHERE cid = '$id'";
		if ($enlace->query($query) === TRUE){

			$query2 = "DELETE FROM ConcursoUsuario  WHERE contest_id = '$id'";
			
			if ($enlace->query($query2) === TRUE){
				$query3 = "DELETE FROM contest  WHERE contest_id = '$id'";
				if ($enlace->query($query3) === TRUE){
					$sms="Concurso Eliminado."; 
					echo '<script>alert("'.$sms.'")</script> ';
					echo "<script>location.href='misContest.php'</script>";
				}
				else{
					$sms="Error al eliminar."; 
							echo '<script>alert("'.$sms.'")</script> ';
							echo "<script>location.href='misContest.php'</script>";
				}
			}
			else{
				$sms="Error al eliminar."; 
						echo '<script>alert("'.$sms.'")</script> ';
						echo "<script>location.href='misContest.php'</script>";
			}
			
		}
		else{
			$sms="Error al eliminar."; 
					echo '<script>alert("'.$sms.'")</script> ';
					echo "<script>location.href='misContest.php'</script>";
		}
		

	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/dino_style.css" />
        <title>Dino Online Judge - Concursos</title>
            <script src="js/jquery-ui.custom.min.js"></script>
    <style>
		.post>form{
			width:50%;
			margin:auto;
			margin-top:30px;
			padding:30px;
			
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
		
		
		
	</style>
</head>

<body>
 <div class="wrapper">
    <?php include_once("includes/head.php"); ?>
    <div><br><br><br></div>
    <?php include_once("includes/header.php"); ?>

    <h2>Eliminar Contest</h2>
    <div class="post" style="background: white;" >
			<form class="form-horizontal" action="eliminarContest.php" method="post">
			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
			  <p class="alert alert-error">Esta seguro de eliminar el concurso ?</p>
			  <div class="form-actions">
				  <button type="submit" class="btn btn-danger">Si</button>
				  <a class="btn" href="misContest.php">No</a>
				</div>
			</form>
	</div>

	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>

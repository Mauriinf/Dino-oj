<!DOCTYPE html>
<html lang="en" >
<head>
	<title>Dino Online Judge - Home</title>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">
		<link rel="stylesheet" href="css/style/bootstrap.cerulean.min.css">
		<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
	
</head>
<body>

		<nav class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
		          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		          </a>
		          <a class="brand" href="index.php">DINO OJ</a>
				
				<div class="nav-collapse collapse">
					<ul class="nav" id="nav">
						<li id="principal"><a href="index.php">Principal</a></li>
						<?php
						if(isset($_SESSION['UserName']))
						{
						?>
						<li class="dropdown" id="contest"><a class="dropdown-toggle" data-toggle="dropdown" href="problemas.php">Problemas<b class="caret" ></b></a>
						<ul class="dropdown-menu">
		                  
							<li ><a href="crearProblema.php">Crear un problema</a></li>
					    	<li ><a href="misProblemas.php">Mis problemas</a></li>
							<li ><a href="problemas.php">Problemas</a></li>		
		                </ul>
		                </li>
		                <?php
						} else {
						?>
						<li id="prroblemas"><a href="problemas.php">Problemas</a></li>
		    			 <?php
						}
						?>

						
						<?php
						if(isset($_SESSION['UserName']))
						{
						?>
						<li class="dropdown" id="contest"><a class="dropdown-toggle" data-toggle="dropdown" href="contest.php">Concursos<b class="caret" ></b></a>
						<ul class="dropdown-menu">
		                  
							<li ><a href="crearContest.php">Crear concurso</a></li>
					    	<li ><a href="misContest.php">Mis concursos</a></li>
							<li ><a href="contest.php">Concurso</a></li>		
		                </ul>
		                </li>
		                <?php
						} else {
						?>
						<li id="contest"><a href="contest.php">Concursos</a></li>
		    			 <?php
						}
						?>
						<li id="ranklist"><a href="ranklist.php">Ranklist</a></li>							
						<li id="ejecucuiones"><a href="status.php">Ejecuciones</a></li>	
						<?php
						if(isset($_SESSION['rol'])&& ($_SESSION["rol"]==1|| $_SESSION["rol"]==2))						{
						?>
						<li id="usuarios"><a href="usuarios.php">Usuarios</a></li>	
						<?php } ?>
						<li id="ayuda"><a href="faq.php">Ayuda</a></li>
					</ul>					
<?php
if(!isset($_SESSION['UserName']))
{
?>
			<ul id="loginbar" class="nav pull-right">
              <li id="loginbutton"><a href="login.php" id="login">Login</a></li>
              <li id="register"><a href="registro.php" class="toregister">Register</a></li>
            </ul>
<?php
} else {
	$nowuser=$_SESSION['UserName']
?>
			<ul id="loginbar" class="nav pull-right">
              <li id="loginbutton"><a  href="editprofile.php"><?=$nowuser?></a></li>
              <li id="register"><a href="salir.php" class="toregister">salir</a></li>
            </ul>
            
<?php
}
?>
						
		        
				</div>
			</div>
		</nav>

<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.js"></script>

</body>
</html>

	
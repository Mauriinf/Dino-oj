<?php 
$errors = array();
require_once("bootstrap.php");
if(isset($_SESSION["UserName"])){//si no hay una sesion iniciada no puede acceder a crear problema
    	$user=$_SESSION["UserName"];
}
//bootsrapear

date_default_timezone_set('America/Caracas');
$timestamp = date( "Y-m-d H:i:s" );

//validar el concurso que voy a renderear
if(!isset($_REQUEST["cid"])){
	die(header("Location: contest.php"));
}


//validar que el concurso exista
$q = "SELECT * from contest where contest_id = ". mysqli_real_escape_string( $enlace,$_REQUEST['cid'] ) .";";
$resultado = mysqli_query($enlace,$q) or pretty_die("Error al buscar este concurso." . mysqli_error());
$q2 = "SELECT * from concursoproblema where cid = ". mysqli_real_escape_string( $enlace,$_REQUEST['cid'] ) .";";
$resultado2 = mysqli_query($enlace,$q2) or pretty_die("Error al buscar problemas de este concurso." . mysqli_error());


if(mysqli_num_rows($resultado) != 1) {
	die("Este concurso no existe." . mysqli_error());
}



$CONTEST = NULL;
$STATUS = null;
$ISPRIVATE = null;
$CDATA = null;
	

//este concurso existe
$CONTEST = $_REQUEST['cid'] ;

//revisar si, es pasado, actual, o en el futuro
$row = mysqli_fetch_array($resultado);

// cdata contiene los datos de este concurso que trae el sql
$CDATA = $row;	
$bandera= 0;	

if( (time() > strtotime($row["Inicio"])) && ( time() < strtotime($row["Final"]) ) ){
	// activo
	$STATUS = "Corriendo";
}

if( (time() > strtotime($row["Final"])) ){
	// ya termino
	$STATUS = "Pasado";		
}

if( time() < strtotime($row["Inicio"]) ){
	// activo
	$STATUS = "Futuro";
}
		
if( $row["EsPrivado"]==1 ){
	// privado
	$ISPRIVATE = "Privado";
}
else{
	$ISPRIVATE = "Publico";
	$bandera=1;
}
function encriptar($cadena){
	    //$key='JuezOnlineJudgeDino';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
	    $encrypted = base64_encode(urlencode($cadena));
	    //$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
	    return $encrypted;
}
if ( !empty($_POST))
{
	$password = $_POST['password'];
	if(empty($_POST['password'])){$password= "N";} 
	else{$password=encriptar($password);}
	if($password==$row['password']){
		$bandera=1;
	}
	else{
		$bandera=0;
		$sms = "Contraseñas no coinciden";
		echo '<script>alert("'.$sms.'")</script> ';
	}
}

/***** ****************************
	CABECERA
 ***** ****************************/
function start(){
	global $CONTEST;
	global $STATUS;
	global $ISPRIVATE;
	global $CDATA;	
	
	if($CONTEST == NULL) {
		echo  "<div align='center'><h2>Este concurso no es valido.</h2></div>" ;
		return;
	}

	?>
	<div align=center>
		
	<div><h2><?php echo $CDATA["Titulo"]; ?></h2></div>
	<div><h4><?php echo $CDATA["Descripcion"]; ?></h4></div>
	<b>Organizador <?php echo $CDATA["Owner"];  ?></b><br>
	<table border='0' cellspacing="5" style="font-size: 16px;" > 
		<tbody >
			<tr align=center style="background-color: #e7e7e7">
				<td ><b>Tiempo Inicio </b><b style="color:#6B169B";><?php echo $CDATA["Inicio"]; ?></b>&nbsp;&nbsp;</td>
				<td ><b>Tiempo Final </b><b style="color:#6B169B ";> <?php echo $CDATA["Final"]; ?></b></td>
				<td ></td>
			</tr>
			<tr align=center style="background-color: #e7e7e7">
				<td ><b>Tiempo actual </b><b style="color:#6B169B";><?php  echo date("d-m-Y H:i:s", mktime(date("H"), date("i") )); ?></b></td>
				<td ><b>Estado </b><b style="color:#6B169B";><?php echo $STATUS; ?></b></td>
				<td ><b style="color:#6B169B ";> <?php echo $ISPRIVATE; ?></b></td>
			</tr>
		</tbody>
	</table>
	
	</div>
	<?php

	
}



/***** ****************************
	IMPRIMIR FORMA DE ENVIO
 ***** ****************************/
function imprimirForma(){
	
	global $row;
	
	
	
	
	?>
	<!--
	<div align="center" >
	<form action="contest_rank.php?cid=<?php echo $_REQUEST['cid']; ?>" method="POST" enctype="multipart/form-data">
		<br>
		<table border=0>
			 <tr><td  style="text-align: right">Codigo fuente&nbsp;&nbsp;</td><td><input name="userfile" type="file"></td></tr>
			
			 <tr><td style="text-align: right">Problema&nbsp;&nbsp;</td><td>
			 	<select name="prob">	
				<?php

				$probs = explode(' ', $row["Problemas"]);
				for ($i=0; $i< sizeof( $probs ); $i++) {
					echo "<option value=". $probs[$i] .">". $probs[$i] ."</option>"; //"<a href='verProblema.php?id=". $probs[$i]  ."'>". $probs[$i] ."</a>&nbsp;";
				}

				?>
				</select>
			 </td></tr>
			
			 <tr><td></td><td><input type="submit" value="Enviar Solucion"></td></tr>
		</table>
	    <input type="hidden" name="ENVIADO" value="SI">
	    <input type="hidden" name="cid" value="<?php echo $_REQUEST['cid']; ?>">
	    
	</form> 
	</div>
	-->
	<?php
}




/***** ****************************
	ENVIAR PROBLEMA
 ***** ****************************/
function enviando(){
		global $CDATA;	
		
		
		//tomo el valor de un elemento de tipo texto del formulario
		$usuario 		= $_SESSION	["UserName"];
		$prob    		= $_POST["prob"];
		$CONCURSO_ID 	= $_REQUEST['cid'];

		//revisar que su ultimo envio sea mayor a 5 minutos
		
		//revisar que este problema exista para este concurso
		$PROBLEMAS = explode(' ', $CDATA["Problemas"]);						
		
		$found = false;

		for ($i=0; $i< sizeof( $PROBLEMAS ); $i++) {
			if($prob ==$PROBLEMAS[$i]) $found = true;
		}
		
		if(!$found){
			echo "<br><div align='center'><b>Ups, este problema no es parte de este concurso.</b><br><br></div>";
			imprimirForma();
			return;
		}
		
		
		
		//revisar si existe este problema
		$consulta = "select problem_id , titulo from problem where BINARY ( problem_id = '{$prob}' ) ";
		$resultado = mysqli_query($enlace,$consulta) or die('Algo anda mal: ' . mysqli_error());

		//si este problema no existe, salir
		if(mysqli_num_rows($resultado) != 1) {
			echo "<br><div align='center'><b>Ups, este problema no existe.</b><br>Vuelve a intentar. Recuerda que el id es el numero que acompa&ntilde;a a cada problema.<br><br></div>";
			imprimirForma();
			return;
		}
		
		
		$row = mysqli_fetch_array( $resultado );
		$TITULO = $row["titulo"];

		//datos del archivo
		$nombre_archivo = $_FILES['userfile']['name'];
		$tipo = $_FILES['userfile']['type'];
		$fname = $_FILES['userfile']['name'];

		//revisar que no existan espacios en blacno en el nombre del archivo
		$fname = strtr($fname, " ", "0");
		$fname = strtr($fname, "_", "0");
		$fname = strtr($fname, "'", "0");

		//compruebo si las características del archivo son las que deseo
		//si (no es text/x-java) y (no termina con .java) tons no es java	
			
		if ( !(endsWith($fname, ".java") || endsWith($fname, ".c") || endsWith($fname, ".cpp")|| endsWith($fname, ".py") || endsWith($fname, ".pl")) ) {
    			echo "<br><br><div align='center'><h2>Error :-(</h2>Debes subir un archivo que contenga un codigo fuente valio y que termine en alguna de las extensiones que <b>teddy</b> soporta.<br>";
			echo "Tipo no permitido: <b>". $tipo . "</b> para <b>". $_FILES['userfile']['name'] ."</b></div><br>";

			imprimirForma();

			return;
		}
		
		
		
		//insertar userID, probID, remoteIP
		mysqli_query ( "INSERT INTO solution (`user_id` , `problem_id` , `remoteIP`, `contest_id`) VALUES ('{$usuario}', {$prob}, '" . $_SERVER['REMOTE_ADDR']. "', " . $_REQUEST['cid'] . "); " ) or die('Algo anda mal: ' . mysql_error());
		$resultado = mysqli_query ($enlace, "SELECT `solution_id` FROM `solution` order by `judgetime` desc limit 1;" ) or die('Algo anda mal: ' . mysql_error());
		$row = mysqli_fetch_array ( $resultado );

		$execID = $row["solution_id"];

		//mover el archio a donde debe de estar
		if (move_uploaded_file($_FILES['userfile']['tmp_name'], "../codigos/" . $execID . "_" . $fname)){

		}else{
			//if no problem al subirlo	
			echo "Ocurrio algun error al subir el archivo. No pudo guardarse.";
		}
	
		imprimirForma();
}


?>
<html>
<head>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" />
			<title>Dino Online Judge - Concurso</title>
			<script src="js/jquery-ui.custom.min.js"></script>

	        <script type="text/javascript" src="uploadify/swfobject.js"></script>
	        <script type="text/javascript" src="uploadify/jquery.uploadify.v2.1.0.min.js"></script>
			<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css" />
<style>
		.post>form{
			width:60%;
			margin:auto;
			margin-top:30px;
			padding:30px;
			border:2px solid #bbb;
			-moz-border-radius:11px;
		}
		.post>form label{
			display:block;
			color:#777777;
			font-size:13px;
		}
		.post>form input.text{
			background:#FBFBFB none repeat scroll 0 0;
			border:1px solid #E5E5E5;
			font-size:16px;
			margin-bottom:16px;
			margin-right:6px;
			margin-top:2px;
			padding:3px;
			width:97%;
    		border-radius: 5px;
    		height:33px;

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
		.right{
			text-align:right;
		}
		#errores{
			width:60%;
			margin:auto;
			margin-top:5px;
			-moz-border-radius:11px;
		}
	</style>       
</head>
<body>

<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
    <div><br><br><br></div>
	<?php include_once("includes/header.php"); ?>

	
	
	<!-- 
		INFORMACION DEL CONCURSO
	-->
	<div class="post_blanco" >
	<?php
		//informacion del concurso
		start();

	?>	
	</div>
	<?php 
	if( $bandera==0 ){
	// privado
	?>	
		<div class="post" style="background:white;">	
			<?php 
			if( !isset($_SESSION["UserName"]) ){
			?>
				<h2>Debes iniciar sesion</h2>
			<?php
			}else{
			?>
				<form id="frm" name="concurso" action="vercontest.php?cid=<?php echo $CONTEST?>" method="post" enctype="multipart/form-data">
			
				<label for="Password">
					Password
				</label>
				<input name="password" type="password" class="text"  placeholder="Password" />
				
				<div class="form-actions">
				<input type="submit" id="submitBoton" class="btn btn-success" value="Entrar" />
				<a class="btn" href="javascript:history.back(1)">Retornar</a>
				</div>
		
				</form>
			<?php
			}
			?>		
			<!--mostrar formulario-->
			
				
		</div>;
	<?php 
	}
	else{
		?>
		<ul class="nav nav-tabs">
		  <li class="active" >
		    <a data-toggle="tab" href="#problemas">Problemas</a>
		  </li>
		  <li>
		    <a data-toggle="tab" href="#rank">Ranck</a>
		  </li>
		  <li >
		    <a data-toggle="tab" href="#envios">Estado</a>
		    
		  </li>
		  <li >
		    <a data-toggle="tab" href="#enviar">Enviar solucion</a>
		    
		  </li>
		</ul>
		
		<div class="tab-content">
			<div class="tab-pane active" id="problemas">
				<?php
				/***********************************************
						PROBLEM SET
				 ***********************************************/
				if( $STATUS == "Corriendo" || $STATUS  == "Pasado" ){
					?>
					<!-- 
						PROBLEM SET
					-->
					<div class="post" >
						<div style="font-size: 26px" align=center>Problemas</div>
						<br>					
						<div id='problem_div' align=center>
							<table border='0' style="font-size: 18px;" > 
							<thead> <tr > 
								<th width='20%'>Problem ID</th> 
								<th width='30%'>Titulo</th> 
								<th width='12%'>AC</th> 
								<th width='15%'>Enviar</th> 
								</tr> 
							</thead> 
							<tbody id="problem_tabla">

							</tbody>
							</table>
						</div>
						<script>
						
						var CurrentPoblem = null;
						
						function showProblem(){
							//los runs han cambiado, entonces mostrar el rank
							//askforproblem();
							
							//console.log("Mostrando runs", CurrentRuns);
							
							$("#problem_div").fadeOut("fast", function (){
								html = "";
								var letra = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q'
								, 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
								for( a = 0; a < CurrentPoblem.length; a++ )
								{	

									if(a%2 ==0){
										html += "<TR style=\"background:#e7e7e7;\">";
									}else{
										html += "<TR style=\"background:white;\">";
									}

									html +=  "<TD align='center' >" +CurrentPoblem[a].pid+ " Problema "+letra[a]+"</TD>";
									html +=  "<TD align='center' ><a href='verProblema.php?id=" +CurrentPoblem[a].pid+"'>" +CurrentPoblem[a].titulo+"</a> </TD>";
									if(CurrentPoblem[a].accepted==null)
									html +=  "<TD align='center' > 0 </TD>";
									else
									html +=  "<TD align='center' > "+CurrentPoblem[a].accepted+" </TD>";
									if(CurrentPoblem[a].envios==null)
									html +=  "<TD align='center' >0</TD>";
									else	
									html +=  "<TD align='center' >" +CurrentPoblem[a].envios+"</TD>";
									html +=  "</TR>";
									
								}

								document.getElementById("problem_tabla").innerHTML = html;
								$("#problem_div").fadeIn();
							})

						}
						
						
						function problemCallback(data){
							if(CurrentPoblem === null){
								//es la primera vez
								CurrentPoblem = data;
								showProblem();
								return;
							}
						
							if(CurrentPoblem.length == data.length){
								// es el mismo, no hacer nada
								return;
							}
								
							CurrentPoblem = data;

							showProblem();

						}
						
						function askforproblem (){

							$.ajax({
							  url: "ajax/problem.php",
							  data: "cid= <?php echo $_REQUEST['cid']; ?>",
							  cache: false,
							  success: function(data){
								var obj = jQuery.parseJSON(data);
								problemCallback(obj);
							  }
							});	
							
							
							setTimeout("askforproblem()",5000);		
						}
						
						askforproblem();

						</script>
					</div>
					<?php
				}
				?>
			</div>
			<div class="tab-pane" id="rank">
				<?php

				if( $STATUS == "Corriendo" || $STATUS  == "Pasado" ){
					?>
					<!-- 
						RANK
					-->
						<div style="font-size: 26px" align=center>Ranking</div>	
						<br>
						<div id='ranking_div' align=center>
							<table border='0' style="font-size: 16px; bold;" > 
							<thead> <tr >
								<th width='50px'>Rank</th> 
								<th width='12%'>Usuario</th> 
								<th width='50px'>Envios</th> 					
								<th width='50px'>Resueltos</th> 
								<?php
									
									$problem = "";
											
									$i="A";
									while ($PROBLEMAS=$resultado2->fetch_assoc()) {
										echo "<th width='100px'><a target='_blank' href='verProblema.php?id=" . $PROBLEMAS['pid']. "&cid=". $_REQUEST['cid']."'>".$i."</a></th> ";
										$i++;
										$problem=$problem.$PROBLEMAS['pid'].",";
									}
									$problem=substr($problem, 0, -1);
								?>
								<th width='12%'>Penalty</th>
								</tr> 
							</thead> 
							<tbody id="ranking_tabla">

							</tbody>
							</table>
						</div>
						<script>
						
						var CurrentRank = null;
						function showRank(){

							
							$("#ranking_tabla").fadeOut("fast", function (){
								html = "";
								
								for( a = 0; a < CurrentRank.length; a++ )
								{	
									if(a%2 ==0){
										html += "<TR style=\"background:#e7e7e7; height: 50px;\">";
									}else{
										html += "<TR style=\"background:white; height: 50px;\">";
									}
									html +=  "<TD align='center' style='font-size: 18px' ><b>" +CurrentRank[a].RANK+ "</b></a></TD>";
									html +=  "<TD align='center' >" +CurrentRank[a].UserName+"</a> </TD>";
									html +=  "<TD align='center' >" +CurrentRank[a].ENVIOS+"</a> </TD>";
									html +=  "<TD align='center' >" +CurrentRank[a].OK+"</a> </TD>";
									//alert("<?php echo $problem;?>");
									var problemas = [<?php echo $problem; ?>];
									//console.log(problemas)
									//console.log(CurrentRank[a].problemas)
									for( z = 0 ; z < problemas.length ; z++ ){
										foo = "";
										for ( p in CurrentRank[a].problemas  ){
											if(p == problemas[z]){
												//estoy en este problema
												foo = "x";
												//CurrentRank[a].problemas[p].bad
												if(CurrentRank[a].problemas[p].ok > 0){
													
													tiempo = parseInt(CurrentRank[a].problemas[p].ok_time / 60);
													tiempo += ":"; 
													bar = parseInt((parseInt(CurrentRank[a].problemas[p].ok_time % 60)));
													if(bar<=9){ bar = "0"+bar;}
													tiempo += bar;
													//tiempo += parseInt((parseInt(CurrentRank[a].problemas[p].ok_time % 60)*60)/100);
													/*
														100 - 60
														x - 
														(x*60)/100
													*/
													foo = "<b>" +  tiempo + "</b> / "+CurrentRank[a].problemas[p].ok_time+"<br>";
													foo += "("+CurrentRank[a].problemas[p].bad+")";
												}else{
													foo = "-"+CurrentRank[a].problemas[p].bad+"";
												}
												

												
											}
										}
										html +=  "<TD align='center' >" + foo +"</TD>";

									}

									html +=  "<TD align='center' >" +CurrentRank[a].PENALTY+" </TD>";
									html +=  "</TR>";

								}

								document.getElementById("ranking_tabla").innerHTML = html;
								
								$("#ranking_tabla").fadeIn();
							});

						}
						

						function askfor (){
							$.ajax({
							  url: "ajax/rank.php",
							  data: "cid= <?php echo $_REQUEST['cid']; ?>",
							  cache: false,
							  success: function(data){
								CurrentRank = jQuery.parseJSON(data);
								showRank();
							  }
							});	
						}
						askfor();
						

						</script>
					
					<?php
				}
				?>
			</div>
			<div class="tab-pane" id="envios">
				<?php
		/***********************************************
				RUNS
		 ***********************************************/
		if( $STATUS == "Corriendo" || $STATUS  == "Pasado" ){
			?>
			<!-- 
				RUNS
			-->
			<div class="post" >
				<div style="font-size: 26px" align=center>Envios</div>
				<br>
				<div id='runs_div' align=center>
					<table border='0' style="font-size: 14px;" > 
					<thead> <tr >
						<th width='12%'>execID</th> 
						<th width='12%'>Problema</th> 
						<th width='12%'>Usuario</th> 
						<th width='12%'>Lenguaje</th> 
						<th width='12%'>Resultado</th> 
						<th width='12%'>Tiempo</th> 
						<th width='12%'>Fecha</th>
						</tr> 
					</thead> 
					<tbody id="runs_tabla">

					</tbody>
					</table>
				</div>
				<script>
				
				var CurrentRuns = null;
				
				function showRuns(){
					//los runs han cambiado, entonces mostrar el rank
					askforruns();
					
					//console.log("Mostrando runs", CurrentRuns);
					
					$("#runs_div").fadeOut("fast", function (){
						html = "";

						for( a = 0; a < CurrentRuns.length; a++ )
						{	

							if(a%2 ==0){
								html += "<TR style=\"background:#e7e7e7;\">";
							}else{
								html += "<TR style=\"background:white;\">";
							}
							
							//color them statusessss
							switch(CurrentRuns[a].Estado){
								case "Compiling":
									CurrentRuns[a].Estado = "<span style='color:red;'>" + CurrentRuns[a].Estado + "</span>";
								break;
								case "Pending":
									CurrentRuns[a].Estado = "<span style='color:purple;'>" + CurrentRuns[a].Estado + "</span>";
								break;
								case "Pending Rejudge":
									CurrentRuns[a].Estado = "<span style='color:purple;'>" + CurrentRuns[a].Estado + "</span>";
								break;
								case "Time Limit Exceeded": 
									CurrentRuns[a].Estado = "<span style='color:brown;'>" + CurrentRuns[a].Estado + "</span>";
								break;
								case "Memory Limit Exceeded":
									CurrentRuns[a].Estado = "<span style='color:brown;'><b>" + CurrentRuns[a].Estado + "</b></span>";
								break;
								case "Accepted":
									CurrentRuns[a].Estado = "<span style='color:green;'><b>" + CurrentRuns[a].Estado + "</b></span>";
								break;
								case "Presentation Error":
									CurrentRuns[a].Estado = "<span style='color:brown;'><b>" + CurrentRuns[a].Estado + "</b></span>";
								break;
								case "Runtime Error":
									CurrentRuns[a].Estado = "<span style='color:blue;'><b>" + CurrentRuns[a].Estado + "</b></span>";				
								break;
								case "Output Limit Exceeded":
									CurrentRuns[a].Estado = "<span style='color:red;'><b>" + CurrentRuns[a].Estado + "</b></span>";				
								break;
								case "Wrong Answer":
									CurrentRuns[a].Estado = "<span style='color:red;'><b>" + CurrentRuns[a].Estado + "</b></span>";				
								break;
								case "Compile Error":
									CurrentRuns[a].Estado = "<span style='color:red;'><b>" + CurrentRuns[a].Estado + "</b></span>";				
								break;
								case "Judging":
								case "Running":
									CurrentRuns[a].Estado = "<span style='color:purple;'>" + CurrentRuns[a].Estado + "...</span>";	//<img src='img/load.gif'>
								break;								
							}
							
							html +=  "<TD align='center' ><a href='verCodigo.php?METHOD=555&execID=" +CurrentRuns[a].EjecID+ "'>" +CurrentRuns[a].EjecID+ "</a></TD>";
							html +=  "<TD align='center' ><a href='verProblema.php?id=" +CurrentRuns[a].ProbID+"'>" +CurrentRuns[a].ProbID+"</a> </TD>";
							html +=  "<TD align='center' ><a href='status.php?user=" +CurrentRuns[a].UserName+"'>" +CurrentRuns[a].UserName+"</a> </TD>";
							html +=  "<TD align='center' >" +CurrentRuns[a].Lenguaje+"</TD>";
							html +=  "<TD align='center' >" +CurrentRuns[a].Estado+"</TD>";
							html +=  "<TD align='center' >" +(parseInt(CurrentRuns[a].Tiempo)/1000)+" Seg. </TD>";
							html +=  "<TD align='center' >" +CurrentRuns[a].Fecha+" </TD>";
							html +=  "</TR>";
						}

						document.getElementById("runs_tabla").innerHTML = html;
						$("#runs_div").fadeIn();
					})

				}
				
				
				function runsCallback(data){
				
					if(CurrentRuns === null){
						//es la primera vez
						CurrentRuns = data;
						showRuns();
						return;
					}
				
					if(CurrentRuns.length == data.length){
						// es el mismo, no hacer nada
						return;
					}
						
					CurrentRuns = data;
					showRuns();

				}
				
				function askforruns (){

					$.ajax({
					  url: "ajax/runs.php",
					  data: "cid= <?php echo $_REQUEST['cid']; ?>",
					  cache: false,
					  success: function(data){
						var obj = jQuery.parseJSON(data);
						runsCallback(obj);
					  }
					});	
					
					
					setTimeout("askforruns()",5000);		
				}
				
				askforruns();

				</script>
			</div>
			<?php
		}
		?>
			</div>
			<div class="tab-pane" id="enviar">
					<!-- 
			ENVIAR SOLUCION
		-->
		<div class="post" >
			<div style="font-size: 18px" align=center>
				<?php

				switch($STATUS){
					case "Pasado": 
	 					echo "Este concurso ha terminado.";
					break;
					
					case "Futuro": 
						echo "Este concurso iniciar&aacute; en "; 
						$datetime1 = date_create( $CDATA['Inicio']);
						$datetime2 = date_create(date("Y-m-d H:i:s"));
						$interval = date_diff($datetime1, $datetime2);
						
						if($interval->format('%D') > 0){
							echo "<b>" . $interval->format('%D') . "</b> dias.";	
						}else{

							?>
								<b><span id='time_left'><?php echo $interval->format('%H:%I:%S'); ?></span></b>.
								<script>
									function updateTime(){

										data = $("#time_left").html().split(":");
										hora = data[0];
										min = data[1];
										seg = data[2];
										
										if(--seg < 0){
											seg = 59;
											
											if(--min < 0){
												min = 59;

												if(--hora < 0){
													hora = 59;
												}
												
												hora = hora < 10 ? "0" + hora : hora;
											}
											
											min = min < 10 ? "0" + min : min;
										}

										seg = seg < 10 ? "0" + seg : seg;
																			
										if(hora == 0 && min == 0 && seg == 0)
										{
											window.location.reload( false );
											
										}
										
										//hora = hora < 10 ? "0" + hora : hora;

																								
										$("#time_left").html(hora+":"+min+":"+seg);
										
									}
									setInterval("updateTime()", 1000);
								</script>
							<?php
						}
						

					break;	
					
					case "Corriendo": 
						echo "Enviar Soluciones al concurso";
						$datetime1 = date_create( $CDATA['Final']);
						$datetime2 = date_create(date("Y-m-d H:i:s"));
						$interval = date_diff($datetime1, $datetime2);
						echo "<br><span id='time_left'>" . $interval->format('%H:%I:%S') . "</span> restante.";					
						
						if( ! isset($_SESSION['UserName'] ) ){
							?> <div align="center">Debes iniciar sesion en la parte de arriba para poder enviar problemas a <b>Dino</b>.</div> <?php
						}else{
							if( isset($_REQUEST["ENVIADO"]) )
								enviando();
							else
								imprimirForma();
						}
					break;
				}
				

				
				?>	
			</div>
		</div>
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


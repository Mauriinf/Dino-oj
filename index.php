<?php 
	require_once("bootstrap.php");
	require_once 'ficheroGlobal.php';

?>
<html>
	<head>
		<title>Dino Online Judge - Home</title>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" media="screen" />
		
		<script src="js/jquery-ui.custom.min.js"></script>
		
	</head>
<body>

<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br></div>
	<?php include_once("includes/header.php"); ?>


	<div class="post_blanco">
        
		<div align="center" >
			<h2>Bienvenido a Dino</h2>
			
			<b><?php require_once("config.php"); require_once("includes/db_con.php"); if ($result = mysqli_query($enlace, "SELECT * FROM solution")){  echo mysqli_num_rows($result);}?></b> ejecuciones &nbsp; 
	
			<b><?php require_once("includes/db_con.php");if ($result = mysqli_query($enlace, "SELECT * FROM users WHERE estado=1")){  echo mysqli_num_rows($result);} ?></b> usuarios &nbsp; 
			<b><?php require_once("includes/db_con.php"); if ($result = mysqli_query($enlace, "SELECT * FROM problem WHERE publico = 'SI'")){  echo mysqli_num_rows($result);} ?></b> problemas &nbsp; 
			<b><?php require_once("includes/db_con.php");if ($result = mysqli_query($enlace, "SELECT * FROM contest")){echo mysqli_num_rows($result);} ?></b> concursos 
		</div>



		<table>
		<tr>
		<td style="text-align:justify;">
			<p> &iexcl;Hola Mundo!
			<br><br>
			Este es el juez en l&iacute;nea de la carrera de Ingerinier&iacute;a Inform&aacute;tica de Universidad Autonoma Tom&aacute;s Frias, un juez en l&iacute;nea similar a ACM dise&ntilde;ado con fines de capacitaci&oacute;n y una plataforma de concurso.
		    
			<br><br>
			Dino es un juez que tiene como objetivo ayudar a los programadores a resolver sus propios problemas, y les ofrece un reto cada cierto tiempo. 
			<br><br>
			Dino evalua c&oacute;digos en C/C++ y Java.
			</p>
		</td>
		<td valign="top">
			<img style="border: 1px" src="img/dino.png">
		</td>
		</tr>
		</table>

	</div>
<!--
	<div class="post">
		<div align="center"><h2>Ultimas Noticias</h2></div>

		<ul>
		<?php 
		require_once("includes/db_con.php");
		$res = mysqli_query($enlace,"select * from Aviso order by fecha desc limit 10"); 

		while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
			print("<li><b>". $row["fecha"] . "</b> " .$row["aviso"] ."</li>");
		} 
		?>
		</ul>
		<br>
		<script type="text/javascript">
		window.google_analytics_uacct = "UA-11327997-2";
		</script>

		<div align="center">
		<script type="text/javascript"><!--
		google_ad_client = "pub-1974587537148067";
		/* teddy horizontal */
		google_ad_slot = "9105913021";
		google_ad_width = 468;
		google_ad_height = 60;
		
		</script>
		<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">	</script>
		</div>
	</div>
	-->




	<div class="post_blanco">
		
		<div align="center"><h2>Algunas Estadisticas</h2></div>
		<div align ="center">

		<?php
		 //  0    1     2       3      4    5     6     7       8     9     10   11     12    13  14   15   16
		//{ "c", "cc", "pas", "java", "rb", "sh", "py", "php", "pl", "cs", "m", "bas", "scm","c","cc","py","cc" };
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE language = 3")){ $java=mysqli_num_rows($result);}
		
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE language = 0" )){ $c =mysqli_num_rows($result);}

		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE language = 1")){ $cpp= mysqli_num_rows($result);}

		/*
		SELECT COUNT( * ) AS  `Filas` ,  `status` 
		FROM  `Ejecucion` 
		GROUP BY  `status` 
		ORDER BY  `status`
		*/
		$total = $java + $c + $cpp;
		if($total == 0) $total = 1;
		$java = ($java * 100)/$total;
		$c = ($c * 100)/$total;
		$cpp = ($cpp * 100)/$total;
		?>

		<img src="http://chart.apis.google.com/chart?
			chs=400x200
		&amp;	chtt=Lenguajes+usados
		&amp;	chd=t:<?php print($java.','.$c.','.$cpp); ?>
		&amp;	cht=p
		&amp;	chl=Java|C|Cpp"
		alt="Lenguajes enviados a Dino" />

		<?php
		/*
		#define OJ_WT0 0   Pending
		#define OJ_WT1 1   Pending Rejudge:
		#define OJ_CI 2    Compiling 
		#define OJ_RI 3    Running & Judging
		#define OJ_AC 4    Accepted 
		#define OJ_PE 5	   Presentation Error
		#define OJ_WA 6    Wrong Answer 
		#define OJ_TL 7    Time Limit Exceeded
		#define OJ_ML 8    Memory Limit Exceeded 
		#define OJ_OL 9    Output Limit Exceeded: 
		#define OJ_RE 10   Runtime Error
		#define OJ_CE 11   Compile Error 
		#define OJ_CO 12
		#define OJ_TR 13
		*/
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 4")){ $ok= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 7")){ $tiempo= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 11")){ $compilacion= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 10")){ $runtime= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution WHERE result = 6")){ $wrong= mysqli_num_rows($result);}
		if ($result = mysqli_query($enlace,"SELECT language FROM solution")){ $total= mysqli_num_rows($result);}

		$otros = $total - ($ok+$tiempo+$compilacion+$runtime+$wrong);
		if ( $ok> 0)
		$ok = ($ok * 100)/$total;
		if ( $tiempo> 0)
		$tiempo = ($tiempo * 100)/$total;
		if ( $compilacion> 0)
		$compilacion = ($compilacion * 100)/$total;
		if ( $runtime> 0)
		$runtime = ($runtime * 100)/$total;
		if ( $wrong> 0)
		$wrong = ($wrong * 100)/$total;
		if ( $otros> 0)
		$otros = ($otros * 100)/$total;

		?>

		<img src="http://chart.apis.google.com/chart?
			chs=400x200
		&amp;	chtt=Status+de+envios
		&amp;	chd=t:<?php print($ok.','.$wrong.','.$tiempo.','.$compilacion.','.$runtime.','.$otros); ?>
		&amp;	cht=p
		&amp;	chl=Aceptado|Incorrecto|Tiempo|Compilacion|Runtime+Error|Otros"
		alt="Lenguajes enviados a Dino" />


	<?php	


		//$res = mysql_query("SELECT LANG FROM `Ejecucion` WHERE fecha = '" .  ."'");
		$days = 6;
		$data_for_chart  = "";
		$data_for_chart_dates  = "";

		while ( $days >= 0 ) {

			$dia  = mktime(0, 0, 0, date("m")  , date("d")-$days, date("Y"));

			$res = mysqli_query($enlace,"SELECT solution_id, judgetime FROM solution WHERE judgetime like '" . date("Y-m-d", $dia) . " %:%:%'");


			$data_for_chart .= mysqli_num_rows($res) . ",";
			$data_for_chart_dates .= date("M+d|", $dia);	
			$days -- ;	

		}

		$data_for_chart = substr($data_for_chart , 0, strlen($data_for_chart) - 1 );
		$data_for_chart_dates = substr($data_for_chart_dates , 0, strlen($data_for_chart_dates) - 1 );
		
	?>
	</div>
<br>

	<div align="center">

		<img src="http://chart.apis.google.com/chart?
			chs=400x200
		&amp;	chtt=Envios+de+los+ultimos+7+dias
		&amp;	cht=ls
		&amp;	chd=t:<?php echo $data_for_chart; ?>
		&amp;	chds=0,100
		&amp;	chg=20,20
		&amp;	chm=N,000000,0,-1,11
		&amp;	chxt=x,y
		&amp;	chco=0000FF
		&amp;	chl=<?php echo $data_for_chart_dates; ?>"
		alt="Lenguajes enviados a Dino" />


	
	
	
	
		<?php	
			date_default_timezone_set('America/Caracas');

			//$res = mysql_query("SELECT LANG FROM `Ejecucion` WHERE fecha = '" .  ."'");
			$days = 6;
			$data_for_chart  = "";
			$data_for_chart_dates  = "";

			while ( $days >= 0 ) {

				$dia  = mktime(0, 0, 0, date("m") - $days , date("d") , date("Y"));

				$res = mysqli_query($enlace,"SELECT count(solution_id) FROM solution WHERE judgetime like '" . date("Y-m", $dia) . "-% %:%:%'");
				//$res = mysql_query("sELECT count(execID) FROM `Ejecucion` WHERE fecha like '2010-04-% %:%:%'");
				$row = mysqli_fetch_array($res);
				$data_for_chart .= $row[0] . ",";
				$data_for_chart_dates .= date("M|", $dia);	
				$days -- ;	
				
				//echo "<!--  ". "sELECT count(execID) FROM `Ejecucion` WHERE fecha like '" . date("Y-m", $dia) . "-% %:%:%'" ."  -->\n";
				//echo "<!--  ". var_dump($row) ."  -->\n\n";

			}

			$data_for_chart = substr($data_for_chart , 0, strlen($data_for_chart) - 1 );
			$data_for_chart_dates = substr($data_for_chart_dates , 0, strlen($data_for_chart_dates) - 1 );

		?>

	



			<img src="http://chart.apis.google.com/chart?
				chs=400x200
			&amp;	chtt=Envios+de+los+ultimos+meses
			&amp;	cht=ls
			&amp;	chd=t:<?php echo $data_for_chart; ?>
			&amp;	chds=0,1000
			&amp;	chg=20,20
			&amp;	chm=N,000000,0,-1,11
			&amp;	chxt=x,y
			&amp;	chco=0000FF
			&amp;	chl=<?php echo $data_for_chart_dates; ?>"

			alt="Envios a Dino" />


	
	
	</div>


	</div>



	<?php include_once("includes/footer.php"); ?>

</div>

<?php include("includes/ga.php"); ?>
</body>
</html>


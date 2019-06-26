<div class="post">
	<div class="navcenter">
		<a href="index.php">Home</a>&nbsp;&nbsp;&nbsp;
		<?php
		if(isset($_SESSION['userID'])){
			/* esta registrado */

		}else{
			/* no esta registrado*/
			?><a href="registro.php">Registro</a>&nbsp;&nbsp;&nbsp;<?php
		}
		?>
		<a href="problemas.php">Problemas</a>&nbsp;&nbsp;&nbsp;
		<a href="enviar.php">Enviar Solucion</a>&nbsp;&nbsp;&nbsp;
		<a href="runs.php">Ejecuciones</a>&nbsp;&nbsp;&nbsp;
		<a href="rank.php">Rank</a>&nbsp;&nbsp;&nbsp;
		<a href="contest.php">Concursos</a>&nbsp;&nbsp;&nbsp;
		<a href="faq.php">Preguntas frecuentes</a>&nbsp;&nbsp;&nbsp;

	</div>
</div>


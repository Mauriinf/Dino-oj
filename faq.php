<?php 
	session_start();
	include_once("config.php");
	include_once("includes/db_con.php");	
?>
<html>
<head>
		<link rel="stylesheet" type="text/css" href="css/dino_style.css" />
		<title>Dino Online Judge - Preguntas Frecuentes</title>
			<script src="js/jquery-ui.custom.min.js"></script>
		<link type="text/css" rel="stylesheet" href="css/SyntaxHighlighter.css"></link>
		<script language="javascript" src="js/shCore.js"></script>
		<script language="javascript" src="js/shBrushCSharp.js"></script>
		<script language="javascript" src="js/shBrushJava.js"></script>
		<script language="javascript" src="js/shBrushCpp.js"></script>
		<script language="javascript" src="js/shBrushPython.js"></script>
		<script language="javascript" src="js/shBrushXml.js"></script>
<script>
window.onload = function () {

    dp.SyntaxHighlighter.ClipboardSwf = 'flash/clipboard.swf';
    dp.SyntaxHighlighter.HighlightAll('code');
}
</script>
</head>
<body>

<div class="wrapper">
	<?php include_once("includes/head.php"); ?>
	<div><br><br></div>
	<?php include_once("includes/header.php"); ?>
	<div class="post">
<p>	
<b>&iquest; Que es Dino  ?</b><br>
Dino es un juez. En la seccion de <a href="problemas.php">problemas</a> podras encontrar enunciados con una entrada y una salida.
<br>Actúa como una interfaz entre los jueces y los participantes de un Concurso de Programación.

Un concurso de programación es una competencia donde los equipos envían soluciones (programas de computadora) a los jueces. Los equipos reciben un conjunto de problemas informáticos para resolver en un tiempo limitado (por ejemplo, 3 horas).
 Luego, los jueces emiten un fallo de aprobación / rechazo a la solución presentada, que se envía a los equipos. Las clasificaciones de los equipos se calculan en función de las soluciones, cuándo se enviaron las soluciones y cuántos intentos se hicieron para resolver el problema. La prueba de los jueces es una prueba de caja negra donde los equipos no tienen acceso a los datos de prueba de los jueces.
</p>


<p>
<b>&iquest; Que lenguajes puede revisar Dino  ?</b><br>
Dino puede evaluar codigo escrito en  <i>Java</i>, <i>C</i> y <i>C++</i>.<br><br>
</p>

<p>
<b>&iquest; Como reconoce Dino los distinto lenguajes  ?</b><br>
Por la extension del codigo fuente, cuando subes un archivo que termina en .java, Dino tratara de compilarlo y ejecutarlo como codigo fuente de java. Pero si subes un archivo .cpp lo tratara como un codigo fuente de C++.<br><br>
</p>
<p>
<b>&iquest; Cuales son las extensiones que Dino asociara a cada lenguaje  ?</b><br>

.java - Java <br>
.c - C <br>
.cpp - C++<br>

</p>

<p>
<b>&iquest; Donde esta la entrada y salida ?</b><br>
Todos los casos de prueba para cada problema se encuentra en el archivo <b><code>data.in</code></b> en el directorio donde se ejecutara tu programa.
 Asi tambien, todo lo que tu programa escriba en el archivo llamado <b><code>data.out</code></b> sera tu respuesta final.<br><br>
</p>

<p>
<b>&iquest; Como se debe llamar mi clase en Java ?</b><br>
La clase debe llamarse <b><code>Main</code></b> de lo contrario obtendras un error.<br><br>
</p>

<p>
<b>&iquest; Que compiladores e interpretes usa Dino ?</b><br>
gcc version 4.3.2 (Debian 4.3.2-1.1)<br>
javac 1.6.0_12<br>><br>
</p>


<p>
<b>&iquest; Con que parametros compila Dino ?</b><br>
<b>Java </b> <code>javac Main.java</code><br>
<b>C </b> <code>gcc fileName -O2 -ansi -fno-asm -Wall -lm -static -DONLINE_JUDGE</code><br>
<b>C++ </b> <code>g++ fileName -O2 -ansi -fno-asm -Wall -lm -static -DONLINE_JUDGE</code><br><br>
</p>


<p>
<b>&iquest; Porque sigo obteniendo un RUN-TIME ERROR ?</b><br>
Tu programa debera regresar un 0 al termino de su ejecucion, de lo contrario obtendras un error de ejecucion.<br><br>
</p>

<!--
<p>
<b>&iquest; Como funcionan los concursos ?</b><br>
<ul>
	<li>Los concursos deben durar por lo menos 30 minutos y 3 horas como maximo.</li>
	<li>Una vez creado un concurso, no se pueden editar sus detalles.</li>
	<li>Los concursos no pueden agendarse a mas de 2 semanas.</li>
	<li>No puede haber mas de 3 concursos al mismo tiempo.</li>
	<li>No puede haber mas de 1 concurso de un mismo organizador al mismo tiempo.</li>
	<li>Todos los concursos son publicos, cualquiera con una cuenta en teddy puede participar.</li>
	<li>Maximo 6 problemas por concurso.
</ul>
</p>

<p>
<b>&iquest; Quien puede organizar concursos ?</b><br>
Debes cumplir con estos lineamientos:<br>
<ul>
	<li>
		Tener un minimo de 5 problemas resueltos.
	</li>
</ul>
</p>
-->
<p>
<b>Ejemplos</b><br>
He aqui ejemplos de soluciones al problema 1:<br><br>
<b>Java :</b>
</p>
<textarea name="code" class="java" cols="60" rows="10">
import java.io.*;  
import java.util.Scanner;  
  
class Main {  
    public static void main(String[] args) {  
    	Scanner sc=new Scanner(System.in);  
	while( sc.hasNextInt() )
        	println( sc.nextInt()+sc.nextInt() );

    }  
}
</textarea>

<br>
<b>C :</b>
<textarea name="code" class="c" cols="60" rows="10">
#include <stdio.h>

int main(void)
{
	int a, b;

	while( scanf("%d %d", &a, &b) != EOF  ){
		printf("%d\n", a + b);	
	}


	return (0);
}
</textarea>


	</div>




	<?php include_once("includes/footer.php"); ?>

</div>
<?php include("includes/ga.php"); ?>
</body>
</html>


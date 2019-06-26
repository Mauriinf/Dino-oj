<?php

	$enlace =new mysqli($DINO_DB_SERVER, $DINO_DB_USER, $DINO_DB_PASS,$DINO_DB_NAME);
	if($enlace->connect_error){
	  die("conexion fallida: ".$enlace->connect_error);
	}
	
	
//verificamos la conexxion



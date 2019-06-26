<?php

include_once("../config.php");
include_once("../includes/db_con.php");


if(isset($_REQUEST['cid'])){
	$cid = addslashes($_REQUEST['cid']);	
}else{
	$cid = "-1";
}



$consulta = "SELECT solution_id as EjecID,user_id as UserName,problem_id as ProbID,result as Estado,time as Tiempo,judgetime as Fecha,language as Lenguaje FROM solution WHERE contest_id = '{$cid}' order by judgetime desc LIMIT 100";

$res = mysqli_query($enlace,$consulta);

$json = array();

while($row = mysqli_fetch_assoc($res)){
	array_push($json, $row);
}

echo json_encode($json);

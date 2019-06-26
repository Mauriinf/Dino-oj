<?php

include_once("../config.php");
include_once("../includes/db_con.php");


if(isset($_REQUEST['cid'])){
	$cid = addslashes($_REQUEST['cid']);	
}else{
	$cid = "-1";
}




$consulta="SELECT * FROM (SELECT problem.titulo as titulo,problem.problem_id as pid FROM concursoproblema,problem WHERE concursoproblema.pid=problem.problem_id AND concursoproblema.cid='{$cid}' )
             problem left join (select problem_id pid1,count(distinct(user_id)) accepted from solution where result=4 and contest_id='{$cid}' group by pid1) p1 on problem.pid=p1.pid1 
             left join (select problem_id pid2,count(1) envios from solution where contest_id='{$cid}' group by pid2) p2 on problem.pid=p2.pid2";
$res = mysqli_query($enlace,$consulta);
$json = array();

while($row = mysqli_fetch_assoc($res)){
	array_push($json, $row);
}

echo json_encode($json);

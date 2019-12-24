<?php

include_once("../config.php");
include_once("../includes/db_con.php");

date_default_timezone_set('America/Caracas');

if(isset($_REQUEST['cid'])){
	$cid = addslashes($_REQUEST['cid']);
}else{
	$cid = "-1";
}


$resultado = mysqli_query($enlace, "select Inicio from contest where ( contest_id = {$cid}  ) ;" );
$row = mysqli_fetch_array($resultado);
$inicio = $row['Inicio'];

$resultado = mysqli_query($enlace, "SELECT user_id as UserName, problem_id as ProbID, result as Estado from solution where ( contest_id = {$cid}  ) ;" );
while($row = mysqli_fetch_array($resultado)){
	//setear el userID
	//	userID->userID 
	$data[ $row[ 'UserName' ] ][ 'UserName' ] = $row['UserName'];
	
	//setear penalty en cero
	//	userID->PENALTY = 0
	if(!isset($data[ $row[ 'UserName' ] ]["PENALTY"])){
		$data[ $row[ 'UserName' ] ]["PENALTY"] = 0;
	}
	
	
	//setear penalty en cero
	//	userID->PENALTY = 0
	if(!isset($data[ $row[ 'UserName' ] ]["ENVIOS"])){
		$data[ $row[ 'UserName' ] ]["ENVIOS"] = 0;
	}
	
	
	//setear penalty en cero
	//	userID->PENALTY = 0
	if(!isset($data[ $row[ 'UserName' ] ]["RANK"])){
		$data[ $row[ 'UserName' ] ]["RANK"] = 0;
	}
	
	//setear ok's en cero
	//	userID->OK = 0
	if(!isset($data[ $row[ 'UserName' ] ]["OK"])){
		$data[ $row[ 'UserName' ] ]["OK"] = 0;
	}

	//set this problem 
	// userID->problemas->probID = 0
	if(!isset($data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ])){
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["ProbID"] = $row['ProbID'];
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["bad"] = 0;
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["ok"] = 0;
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["ok_time"] = 0;
	}
	
	$data[ $row[ 'UserName' ] ]["ENVIOS"]++;
	
	if($row["Estado"] == 4) {
		
		//si resolvio el mismo problema, solo agregar uno al ok total
		if($data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["ok"] == 0 ) $data[ $row[ 'UserName' ] ]["OK"]++;
		
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["ok"]++;
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["ok_time"] = intval( (strtotime($row['Fecha'])-strtotime($inicio))/60 );

	}else{
		$data[ $row[ 'UserName' ] ][ "problemas" ][ $row['ProbID'] ]["bad"]++;
	}

}


//calcular penalty
foreach( $data as $userName => $userArr)
{
	
	foreach( $userArr['problemas'] as $probID => $probArr)
	{
		//estoy en cada problema de cada usuario
		if( $probArr['ok'] == 0 ){
			continue;
		}
		
		$data[ $userName ]['PENALTY'] += ((int)$probArr['bad']) * 20 ;
		$data[ $userName ]['PENALTY'] += ((int)$probArr['ok_time'])  ;
	}
}






// Comparison function
function cmp($a, $b) {
	
	if($a['OK'] == $b['OK']){
		
		if ($a['PENALTY'] == $b['PENALTY']){
			
			if ($a['ENVIOS'] == $b['ENVIOS']){

				return 0;
			}
			
	    	return ($a['ENVIOS'] < $b['ENVIOS']) ? -1 : 1;			
		} 
	        

	    return ($a['PENALTY'] < $b['PENALTY']) ? -1 : 1;
	}

    return ($a['OK'] > $b['OK']) ? -1 : 1;	
	
}


// SORTING
uasort($data, 'cmp');

//agregando el rank
$R = 1;
foreach( $data as $row => $k){
	
	if(isset($old)){
		if(($data[$old]["OK"] == $data[$row]["OK"]) && ($data[$old]["PENALTY"] == $data[$row]["PENALTY"])){
			$data[$row]['RANK'] = $R;
		}else{
			$R++;
			$data[$row]['RANK'] = $R;			
		}
	}else{
		$data[$row]['RANK'] = $R;		
	}

	$old = $row;
}


$json = array();

foreach( $data as $row ){
	array_push($json, $row);
}

echo json_encode($json);


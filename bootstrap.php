<?php
date_default_timezone_set('America/Caracas');


/**
  * Bootstrap
  *
  *
  **/
if(isset($_REQUEST['sid'])){
	session_id($_REQUEST['sid']);	
}
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	




require_once("config.php");

require_once("includes/db_con.php");

require_once("includes/utils.php");








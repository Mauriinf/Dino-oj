<?php

//CONNECT TO PDO
include_once("config.php");
include_once("includes/db_con.php");
//CHECH IF USERNAME EXISTS 
if (isset($_POST))
{
    $usernameposted=$_POST["nick"];
    
    if (strlen($usernameposted)<4) {
    }
    else {
        $sql="SELECT user_id FROM users WHERE user_id='".$usernameposted."'";
        $res = mysqli_query($enlace,$sql) or die('Error al buscar usuario'. mysqli_error());

        if ($res->num_rows == 0)
        {
         echo "<div class='alert alert-success '><i class='fa fa-check'></i> Nombre de usuario disponible</div><input id='usernamechecker' type='hidden' value='1' name='usernamechecker'>";   
        }
         else {
            echo "<div class='alert alert-danger'><i class='fa fa-close'></i> Nombre de usuario NO disponible<input id='usernamechecker' type='hidden' value='0' name='usernamechecker'></div>";
        }
    }
}
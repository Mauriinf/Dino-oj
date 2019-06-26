<?php

//CONNECT TO PDO

include_once("config.php");
include_once("includes/db_con.php");
//CHECH IF USERNAME EXISTS 
if (isset($_POST))
{
    $emailposted=$_POST["email"];
    
    // Validate email
 
    $sql="SELECT user_id FROM users WHERE Email='".$emailposted."'";
    $res = mysqli_query($enlace,$sql) or die('Error al buscar email'. mysqli_error());

    if ($res->num_rows == 0)
    {
     echo "<div class='alert alert-success '> Email disponible</div> <input id='emailchecker' type='hidden' value='1' name='emailchecker'>  ";   
    }
     else {
        echo "<div class='alert alert-danger '> Email en uso <input id='emailchecker' type='hidden' value='0' name='emailchecker'></div>";
    }

}

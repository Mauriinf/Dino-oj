<?php
	function isnullDatosRegistroUsuario($nick,$password,$nombre,$apellido,$email,$ubicacion,$escuela){
		if(strlen(trim($nick)) < 1 || strlen(trim($password)) < 1 || strlen(trim($nombre)) < 1 || strlen(trim($apellido)) < 1 || strlen(trim($email)) < 1|| strlen(trim($ubicacion)) < 1|| strlen(trim($escuela)) < 1)
		{
			return true;
			} else {
			return false;
		}		
	}
	function isNullDatosProblema($titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida){
		if(strlen(trim($titulo)) < 1 || strlen(trim($autor)) < 1 || strlen(trim($tiempo)) < 1 || strlen(trim($memoria)) < 1 || strlen(trim($descripcion)) < 1|| strlen(trim($entrada)) < 1|| strlen(trim($salida)) < 1|| strlen(trim($ejemploentrada)) < 1|| strlen(trim($ejemplosalida)) < 1)
		{
			return true;
			} else {
			return false;
		}		
	}
	function isNullDatosContest($titulo,$descripcion,$inicio,$fin,$comboprivado,$ocultarScore,$password,$user){
		if(strlen(trim($titulo)) < 1 || strlen(trim($descripcion)) < 1 || strlen(trim($inicio)) < 1 || strlen(trim($fin)) < 1 || strlen(trim($comboprivado)) < 1|| strlen(trim($ocultarScore)) < 1|| strlen(trim($user)) < 1)
		{
			return true;
			} else {
			return false;
		}
	}
	function isNull($nombre, $user, $pass, $pass_con, $email){
		if(strlen(trim($nombre)) < 1 || strlen(trim($user)) < 1 || strlen(trim($pass)) < 1 || strlen(trim($pass_con)) < 1 || strlen(trim($email)) < 1)
		{
			return true;
			} else {
			return false;
		}		
	}
	
	function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
			} else {
			return false;
		}
	}
	
	function emailExiste($email)
	{
		global $enlace;
		
		$stmt = $enlace->prepare("SELECT user_id FROM users WHERE Email = ? LIMIT 1");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		$stmt->close();
		
		if ($num > 0){
			return true;
			} else {
			return false;	
		}
	}
	function resultBlock($errors){
		if(count($errors) > 0)
		{
			echo "<div id='error' class='alert alert-danger' role='alert'>
			<a href='#' onclick=\"showHide('error');\">[X]</a>
			<ul>";
			foreach($errors as $error)
			{
				echo "<li>".$error."</li>";
			}
			echo "</ul>";
			echo "</div>";
		}
	}
	function generateToken()
	{
		$gen = md5(uniqid(mt_rand(), false));	
		return $gen;
	}
	
	function hashPassword($password) 
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);
		return $hash;
	}
	
	
	
	
	function enviarEmail($email, $nombre, $asunto, $cuerpo){
		
		require_once 'PHPMailer/PHPMailerAutoload.php';
		
		$mail = new PHPMailer();
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls'; //Modificar
		$mail->Host = 'smtp.office365.com'; //Modificar 'smtp.gmail.com' gmail
		$mail->Port = 587; //Modificar
		
		$mail->Username = 'Judge_Dino_Online@hotmail.com'; //Modificar
		$mail->Password = 'juezdinoinformatica2018'; //Modificar
		
		$mail->setFrom('Judge_Dino_Online@hotmail.com', 'Dino Judge'); //Modificar
		$mail->addAddress($email, $nombre);
		
		$mail->Subject = $asunto;
		$mail->Body    = $cuerpo;
		$mail->IsHTML(true);
		
		if($mail->send())
		return true;
		else
		return false;
	}
	
	function validaIdToken($id, $token){
		global $enlace;
		
		$stmt = $enlace->prepare("SELECT activacion FROM users WHERE id = ? AND token = ? LIMIT 1");
		$stmt->bind_param("is", $id, $token);
		$stmt->execute();
		$stmt->store_result();
		$rows = $stmt->num_rows;
		
		if($rows > 0) {
			$stmt->bind_result($activacion);
			$stmt->fetch();
			
			if($activacion == 1){
				$msg = "La cuenta ya se activo anteriormente.";
				} else {
				if(activarUsuario($id)){
					$msg = 'Cuenta activada.';
					} else {
					$msg = 'Error al Activar Cuenta';
				}
			}
			} else {
			$msg = 'No existe el registro para activar.';
		}
		return $msg;
	}
	
	function validaPassword($var1, $var2)
	{
		if (strcmp($var1, $var2) !== 0){
			return false;
			} else {
			return true;
		}
	}
	
	function isNullLogin($usuario, $password){
		if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1)
		{
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	
	
	
	
	function generaTokenPass($user_id,$email)
	{
		global $enlace;
		
		global $enlace;
		
		$token = generateToken(); 
		$ip= get_client_ip(); 
		$query = "INSERT INTO LostPassword (Token, MailSent,user_id,IP) VALUES('$token','1','$user_id','$ip')";
		
		if ($enlace->query($query) === TRUE) {
			return $token;
		} 		
		return $token;
	}
		
	function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
	function getValor($campo, $campoWhere, $valor)
	{
		global $enlace;
		
		$stmt = $enlace->prepare("SELECT $campo FROM users WHERE $campoWhere = ? LIMIT 1");
		$stmt->bind_param('s', $valor);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		
		if ($num > 0)
		{
			$stmt->bind_result($_campo);
			$stmt->fetch();
			return $_campo;
		}
		else
		{
			return null;	
		}
	}
	function getPasswordRequest($id)
	{
		global $enlace;
		
		$stmt = $enlace->prepare("SELECT password_request FROM usuarios WHERE id = ?");
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($_id);
		$stmt->fetch();
		
		if ($_id == 1)
		{
			return true;
		}
		else
		{
			return null;	
		}
	}
	
	function verificaTokenPass($user_id, $token){
		
		global $enlace;
		
		$stmt = $enlace->prepare("SELECT ID FROM lostpassword WHERE user_id = ? AND Token = ? AND MailSent = 1 LIMIT 1");
		$stmt->bind_param('ss', $user_id, $token);
		$stmt->execute();
		$stmt->store_result();
		$num = $stmt->num_rows;
		
		if ($num > 0)
		{
			$stmt2 = $enlace->prepare("SELECT Estado FROM users WHERE user_id = ? LIMIT 1");
			$stmt2->bind_param('s', $user_id);
			$stmt2->execute();
			$stmt2->store_result();
			$stmt2->bind_result($estado);
			$stmt2->fetch();
			if($estado == 1)
			{
				return true;
			}
			else 
			{
				return false;
			}
			
		}
		else
		{
			return false;	
		}
	}
	
	function cambiaPassword($password, $user_id){
		
		global $enlace;
		
		$stmt = $enlace->prepare("UPDATE users SET Password = ? WHERE user_id = ?");
		$stmt->bind_param('ss', $password, $user_id);
		
		if($stmt->execute()){
			return true;
			} else {
			return false;		
		}
	}	

	function registraProblema($id,$titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida){
		
		global $enlace;
		
		$stmt = $enlace->prepare("INSERT INTO problem (user_id, Titulo,time_limit,memory_limit,descripcion,Entrada,Salida,EjemploEntrada,EjemploSalida,Autor) VALUES(?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param('ssiissssss',$id,$titulo,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida,$autor);
		if ($stmt->execute()){
			return $enlace->insert_id;
			} else {
			return 0;	
		}		
	}	
	function EditarProblema($id,$titulo,$autor,$tiempo,$memoria,$descripcion,$entrada,$salida,$ejemploentrada,$ejemplosalida,$arc){
		
		global $enlace;
		
		$query ="update problem SET Titulo= '{$titulo}',time_limit= '{$tiempo}',memory_limit= '{$memoria}',Source= '{$arc}',descripcion= '{$descripcion}',
		Entrada= '{$entrada}',Salida= '{$salida}',EjemploEntrada= '{$ejemploentrada}',EjemploSalida= '{$ejemplosalida}',Autor= '{$autor}' WHERE  problem_id =  '{$id}' LIMIT 1 ;";
		if ($enlace->query($query) === TRUE){
			return 1;
		} else {
			return 0;	
		}		
	}	
	function registraConcurso($titulo,$descripcion,$inicio,$fin,$comboprivado,$ocultarScore,$password,$user){
		
		global $enlace;
		$text="Pendiente";
		$inicio=date("Y-m-d H:i:s", $inicio);
		$fin=date("Y-m-d H:i:s", $fin);
		$stmt = $enlace->prepare("INSERT INTO contest(Titulo,Descripcion,Inicio,Final,EsPrivado,BloqueoTabla,report,password,Owner) VALUES(?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param('ssssiisss',$titulo,$descripcion,$inicio,$fin,$comboprivado,$ocultarScore,$text,$password,$user) ;
		if ($stmt->execute()){
			return $enlace->insert_id;
			} else {
			return 0;	
		}
	}
	function EditarConcurso($id,$titulo,$descripcion,$inicio,$fin,$comboprivado,$ocultarScore,$password,$user){
		
		global $enlace;
		
		$query ="update contest SET Titulo= '{$titulo}',Descripcion= '{$descripcion}',Inicio= '{$inicio}',Final= '{$fin}',EsPrivado= '{$comboprivado}',
		BloqueoTabla= '{$ocultarScore}',password= '{$password}',Owner= '{$user}' WHERE  contest_id =  '{$id}' LIMIT 1 ;";
		if ($enlace->query($query) === TRUE){
			return 1;
		} else {
			return 0;	
		}		
	}	
	function isNumero($num){
		if (is_numeric($num)){
			return true;
		} else {
			return false;	
		}		
	}	
	function isDate($date){
		if (is_date($date, "dd/mm/yyyy hh:mm:ss")){
			return true;
		} else {
			return false;	
		}		
	}	
	function saltoLinea($str) { 
	  return preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $str); 
	} 
	function registraEnvio($user,$enviar_a,$lenguaje,$codigo){
		global $enlace;
		$ip=$_SERVER['REMOTE_ADDR'] ;
		$num=0;
		$stmt = $enlace->prepare("INSERT INTO solution (user_id, problem_id,language,Source,RemoteIP,num) VALUES(?,?,?,?,?,?)");
		$stmt->bind_param('siissi',$user,$enviar_a,$lenguaje,$codigo,$ip,$num);
		if ($stmt->execute()){
			return $enlace->insert_id;
			} else {
			return 0;	
		}			
	}	
	function registraEnvioConcurso($user,$enviar_a,$cid,$lenguaje,$codigo){
		global $enlace;
		$ip=$_SERVER['REMOTE_ADDR'] ;
		$num=0;
		$stmt = $enlace->prepare("INSERT INTO solution (user_id, problem_id,contest_id,language,Source,RemoteIP,num) VALUES(?,?,?,?,?,?,?)");
		$stmt->bind_param('siiissi',$user,$enviar_a,$cid,$lenguaje,$codigo,$ip,$num);
		if ($stmt->execute()){
			return $enlace->insert_id;
			} else {
			return 0;	
		}			
	}	
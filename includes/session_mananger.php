<?php

	/* 
		sesion activa
	*/
function ok(){
	?>
	<div class="post">
		<div class="navcenter" align=center>

			<style type="text/css">
			#avatar{
				margin-top: -3px;
				margin-right: 3px;
				vertical-align: middle;
				border: 1px solid white;
				}
			</style>

			<table border=0 style="width:100%">

				<tr class="navcenter">
					<td colspan=1>
						<a href="runs.php?user=<?php echo $_SESSION['userID']; ?>">
							<img id="avatar" src="https://secure.gravatar.com/avatar/<?php echo md5( $_SESSION['mail'] ); ?>?s=140" alt="" width="20" height="20"  />
						</a>					
						Bienvenido <b><?php echo $_SESSION['userID']; ?></b> !<br>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="runs.php?user=<?php echo $_SESSION['userID']; ?>"><img src="img/67.png" > Mi perfil</a>					
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="editprofile.php"><img src="img/71.png" > Editar tu perfil</a>
					</td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="problemas.php?userID=<?php echo $_SESSION['userID']; ?>"><img src="img/67.png" > Problemas no resueltos</a>
					</td>

					
					<?php
					//buscar mensajes no leidos para este usuario
					require_once("includes/db_con.php");
					$consulta = "select id from mensaje where para = '{$_SESSION["userID"]}' AND Unread = '1';";

					$resultado = mysqli_query($enlace,$consulta);

					if(mysqli_num_rows($resultado) > 0){
						?>
							<script type="text/javascript">
								var foo = function(){
									$("#mailbox_menu").fadeTo("slow", .4, function(){
										$("#mailbox_menu").fadeTo("slow", 1, foo);
									});
								}

								$(document).ready( foo );
							</script>
						<?php
					}
					?>
					<td id="mailbox_menu">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="inbox.php">
								<img src="img/49.png" > 
								Mensajes (<?php echo mysqli_num_rows($resultado) ?>)
							</a>
					
					</td>
					<?php
					?>

			<?php if (($_SESSION["userMode"] == "ADMIN")||($_SESSION["userMode"] == "OWNER")) { ?>
				<td>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin/"><img src="img/71.png" >Administracion</a>
				</td>
			<?php } ?>


			<td>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="includes/login_app.php?log_out=logout"><img src="img/55.png" > Cerrar Sesion</a>
			</td>

				</tr>
			</table>

		</div>
		
	</div>
	<?php
	}


	/* 
		no hay sesion activa
	*/
	function none(){
	?>
	<script>

	function lost_returned(data){
		$('#message').fadeOut('slow', function() {
			alert(data.responseText);
			try{
				x = jQuery.parseJSON( data.responseText );
			}catch(e){
				//invalid json
				alert("Algoooooo anda mal con Dino. Por favor envia un mail a mauricio_nina@hotmail.com si este problema persiste.");
				location.reload(true);
				return;
			}
		
		
			if(x.success){
				alert("Se ha enviado un correo a este usuario con instrucciones para obtener una nueva contrase&ntilde;a");
				$('#login_area').slideDown('slow');
			}else{
				alert(x.reason);
				$('#login_area').slideDown('slow');
			}
		});//efecto

	}

	function lost(){
		
		if($("#user").val().length < 2){
			alert("Escribe tu nombre de usuario o correo electronico en el campo.");
			return;
		}

		$('#login_area').slideUp('slow', function() {

				$('#wrong').slideUp('slow');

		    		$('#message').slideDown('slow', function() {
					//actual ajax call
					$.ajax({ 
						url: "ajax/lost_pass.php", 
						dataType: 'json',
						data: { 'user' : document.getElementById("user").value },
						context: document.body, 
						complete: lost_returned
					});
			  	});
		  	});

	}
	</script>

	<div class="post" >
		<div id="login_area" class="navcenter">
			<form method="post" onSubmit="return submitdata()">
				<img  src="img/37.png"> <input type="text" style="width:150px;height:26px;border-radius: 5px;border:1px solid #E5E5E5;" value="" id="user" placeholder="usuario">
				<img  src="img/55.png"> <input type="password" style="width:150px;height:26px;border-radius: 5px;border:1px solid #E5E5E5;" value="" id="pswd" placeholder="contrase&ntilde;a">
				<input type="submit" style="width:100px;height:26px;border-radius: 5px;border:1px solid #AAAAAA;" value="Iniciar Sesion">
				<!-- <input type="button" onClick="lost()" id="lost_pass" value="Olvide mi contase&ntilde;a"> -->
			</form>

		</div>
		<div align=center id="wrong" style="display:none;">
			<img  src="img/12.png"> Datos invalidos
		</div>
		<div align=center id="message" style="display:none">
			<img src="img/load.gif">
		</div>
	</div>
	<script>
		//contenido de desvanecimiento
		function submitdata(){

			$('#login_area').slideDown('slow', function() {

				$('#wrong').slideDown('slow');

		    		$('#message').slideDown('slow', function() {
					//actual ajax call
					$.ajax({ 
						url: "includes/login_app.php", 
						dataType: 'json',
						data: { 'user' : document.getElementById("user").value, 'pswd': encriptar( document.getElementById("pswd").value) },
						context: document.body, 
						complete: returned
					});
			  	});
		  	});
			
			return false;
		}
		
		
		function returned( data ){
			$('#message').fadeOut('slow', function() {
				var x = null;
				
				try{
					x = jQuery.parseJSON( data.responseText );
				}catch(e){
					//invalid json
					alert("Algoaaaaa anda mal con Dino. Por favor envia un mail a mauricio_nina@hotmail.com si este problema persiste.");
					console.log(x,e)
					//location.reload(true);
					return;
				}
				
				if(x.badguy){
					document.getElementById("login_area").innerHTML = x.msg;
					$("#login_area").slideDown("slow");
					return;
				}
				
				if(x.sucess){
					location.reload(true);
				}else{
					$("#wrong").slideDown("slow", function (){ 
						$('#login_area').slideDown('slow', function() {
					   		$("#login_area").effect("shake", { times:2 }, 100);
						});					
					});

				}
			});//efecto
		}
	</script>
	<?php
	}



	if(isset($_SESSION['status']) && $_SESSION['status'] == "OK") { 
		ok();
	}else{
		none();
	}

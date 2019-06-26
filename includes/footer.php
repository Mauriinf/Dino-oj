<div class="post" style="background: #060a15; ">

	<div  align=center>
			<table border=0>
				<tr>
					<td class="footer" style="color: white;" colspan=2>
						Hecho por <b><a href="https://www.facebook.com/mauricio.nina.73" style="color:white;">Mauricio Nina</a></b>
						<br><br>
						
					</td>

				</tr>
				<tr>
					<td>
					    <?php $root = file_exists ( "img/uatff.png" ); ?>
					    
						<a href="http://www.uatf.edu.bo/"><img src="<?php echo $root ? '' : '../'; ?>img/uatff.png"></a>
						<img src="<?php echo $root ? '' : '../'; ?>img/inform.png">
						<img src="<?php echo $root ? '' : '../'; ?>img/acm.jpg"> 
					</td>
					<td>
						<script type="text/javascript"><!--
						google_ad_client = "pub-1974587537148067";
						
						/* teddy horizontal negro */
						google_ad_slot = "1962252847";
						google_ad_width = 468;
						google_ad_height = 60;
						//-->
						</script>

							
					</td>
				</tr>
			</table>
		</div>	
</div>


<?php

if( isset($resultado))
	mysqli_free_result($resultado);

/*if( isset($enlace))
	mysql_close($enlace);*/

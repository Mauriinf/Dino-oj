<div class="post" style="border-style: solid; border-color: #58ACFA; ">

	<div  align=center>
			<table border=0>
				
				<tr>
					<td>
					    <?php $root = file_exists ( "img/uatff.png" ); ?>
					    
						<a href="http://www.uatf.edu.bo/"><img src="<?php echo $root ? '' : '../'; ?>img/uatff.png"></a>
						<img src="<?php echo $root ? '' : '../'; ?>img/inform.png">
						<img src="<?php echo $root ? '' : '../'; ?>img/ACM.png"> 
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
				<tr>
					<td   colspan=2>
						Desarrollado por <b><a href="https://www.facebook.com/mauricio.nina.73" >Mauricio Nina</a></b>
						<br><br>
						
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

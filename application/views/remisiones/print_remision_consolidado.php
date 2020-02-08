<!doctype html>
<html lang="es">
<head>
	<meta>
	<title></title>
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/LOGOS_DELMOR1.png" />
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js')?>"></script>
	<style>
		#footer {
			padding: 30px 30px;
			width:1000px;
			height: auto;
			margin: 0 auto;
			font-family: 'arial'!important;
			text-transform: uppercase!important;
			margin-top:10vh;
		}
		.footer {
			margin-top: 80px;
		}
		.footer tr td {
			width: 50%;
			text-align: center;
			padding: 5px 5px;
			border: none;
		}
		table {
			color: black;
			font-size: 10pt;
			font-weight:bold;
			font-family: 'arial'!important;
			text-transform: uppercase!important;
			border-collapse: collapse;
			width: 1000px;
			margin: 0 auto;
			margin-bottom: 5px;
		}
		table th,td{
			text-align: center;
			border: 1px solid black;
		}
		.encabezado{
			margin:0;
			padding: 0;
			font-weight:800;
		}

		.negrita{
			font-weight:700;
			text-align:left;
		}

		/*.contenedor {
			width: 80%;
			height: 100%;
			margin: 0 auto;
			border: 1px solid black;
			border-radius: 2px;
			padding: 2px 2px;
        }*/

		span {
			text-transform: uppercase!important;
			font-weight: bold;
			font-size: 10px;
			margin-left:20%;
		}

		#img{
			border:none;
			width:20px;
		}
		/* .contenedor {
			width: 98%;
			height: 100%;
			margin: 0 auto;
			border: 1px solid black;
			border-radius: 2px;
			padding: 2px 2px;
        } */

		.black{
			background-color:#484747;
			color:white;
			font-weight:bold;
		}

		#consecutivo{
			margin-left:-90px;
			float:right;
			margin-right:20px;
			border-left:1px solid black;
			padding-left:5px;
			font-size: 12px !important;
			line-height: 0.5;
			text-align:center;
		}
		.container{
			margin: 0 auto;
			max-width: 1280px;
			width: 90%;
		}

		#tblMain thead td{
			font-size: 8pt;
		}
	</style>
	<script>
		$(document).ready(function(){
			window.print();
		})
	</script>
</head>

<body>
<?php
date_default_timezone_set("America/Managua");
setlocale(LC_ALL,'Spanish_Nicaragua');
?>
<div class="contenedor">
	<div class="contenedor-secundario">
		<table class="table-produccion">
			<thead>
			<tr>
				<td rowspan="3" id="img">
					<img width="110px" id="img1" src="<?php echo base_url()?>assets/img/LOGOS_DELMOR.png" alt="">
				</td>
				<td class="encabezado" colspan="13">Industrias delmor S.A</td>
			</tr>
			<tr>
				<td class="encabezado" colspan="13">Gerencia de mercadeo y ventas</td>
			</tr>
			<tr>
				<td class="encabezado" colspan="13">Orden de entrega y devolucion de productos ISO-Venta-03-01
					<span id="consecutivo" style="font-size:11pt;">
                    </span> </td>
			</tr>
			<tr>
				<td colspan="1" class="negrita">Nombre del vendedor</td>
				<td colspan="7"><span style="font-size:11pt;">
					<?php
					if (!$det) {
					} else {
						foreach ($det as $key) {
						}
						echo "CONSOLIDADO TOTAL  ".$key["RubroD"]."";
					}
					?>
				</span></td>
			</tr>
			<tr>
				<td colspan="1" class="negrita" style="width:200px;">Ruta/Zona</td>
				<td colspan="7"><span style="font-size:11pt;">
					<?php
					$ruta = '';
					if (!$det) {
					} else {
						foreach ($det as $key) {
						}
						if($this->uri->segment(5) != "NULL"){
							if($this->uri->segment(5) == 78){
								$ruta = '30';
							}elseif ($this->uri->segment(5) == 79) {
								$ruta = '31';
							}elseif ($this->uri->segment(5) == 80) {
                                $ruta = '32';
                            }elseif ($this->uri->segment(5) == 81) {
                                $ruta = '33';
                            }else{
								$ruta = $this->uri->segment(5);
							}
							echo "CONSOLIDADO TOTAL RUTA ".$ruta. " (".$key["TipoD"].")";
						}else{
							echo "CONSOLIDADO TOTAL  (".$key["TipoD"].")";
						}
					}
					?>
					</span></td>
			</tr>
			<tr>
				<td colspan="1" class="negrita">Fecha</td>
				<td><span style="font-size:11pt;">
						<?php
						$fecha = '';
						if (!$enc) {
						} else {
							foreach ($enc as $key) {
								$fecha = $key["FechaEntrega"];
							}
							echo "".utf8_encode(strftime("%A %d de %B del %Y",strtotime($fecha)))."";
						}
						?></span></td>
				<td class="negrita"> Preparado por </td>
				<td>
					<span style="font-size:11pt;">
						<?php
						if (!$det) {
						} else {
							foreach ($det as $key) {
							}
							echo $key["Nombre"]." ".$key["Apellidos"];
						}
						?></span>
				</td>
			</tr>
			<tr>
				<td colspan="1" class="negrita">Código Remisiones</td>
				<td colspan="7"><span style="font-size:11pt;">
						<?php
						$codigos = "";
						if (!$enc) {
						} else {
							foreach ($enc as $key) {
								$codigos .= $key["IdRemision"].",";
							}
							echo substr($codigos,0,-1);
						}
						?></span></td>
			</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>

	<br><br>

	<div class="contenedor-secundario">
		<table class="table-produccion" id="tblMain">
			<thead>
			<tr>
				<th colspan="3">PEDIDO</th>
				<th colspan="4" class="black">CANT. DESPACHO</th>
				<th colspan="2"></th>
				<th colspan="4" class="black">CANT. DEVUELTAS</th>
			</tr>
			<tr class="encabezado">
				<td width="50">CODIGO</td>
				<td width="60">UNID</td>
				<td width="60">LBS</td>
				<td width="60">LOTE 1</td>
				<td width="65">CANT. <br> LOTE 1</td>
				<td width="60">LOTE 2</td>
				<td width="65">CANT. <br> LOTE 2</td>
				<td>PRODUCTOS</td>
				<td>GR/lb</td>
				<td width="60">LOTE 1</td>
				<td width="65">CANT.  <br> LOTE 1</td>
				<td width="60">LOTE 2</td>
				<td width="65">CANT. <br> LOTE 2</td>
			</tr>
			</thead>
			<tbody>
			<?php
			if(!$det){
			}else{
				foreach ($det as $item) {
					echo"
							<tr>
								<td>".$item["CodigoProd"]."</td>
								<td>".number_format($item["Cantidad"],2)."</td>
								<td>".number_format($item["CantidadLBS"],2)."</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td style='font_size:10px;text-align: left; height:30px;'>".$item["DescripcionProd"]."</td>
								<td>".number_format($item["LBS"],2)."</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						";
				}
			}
			?>
			</tbody>
		</table>
	</div>

	<div class="contenedor-secundario">
		<table class="table-produccion" style="width:20% !important; float:left; margin-left:14.8%; margin-top:5vh;">
			<thead>
			</thead>
			<tbody>
			<tr>
				<td style="width:180px;">TOTAL PEDIDO <br>(lbs)</td>
				<?php
				$suma = 0;
				if (!$det) {
				} else {
					foreach ($det as $key) {
						$suma+=$key["CantidadLBS"];
					}

				}
				echo "
                       <td style='width:100px;text-align:right; padding-right:5px;'>".number_format($suma,2)."</td>
                    ";
				?>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="contenedor-secundario">
		<table class="table-produccion" style="width:20% !important; float:right; margin-right:14.8%; margin-top:5vh;">
			<thead>
			</thead>
			<tbody>
			<tr>
				<td style="width:100px;">TOTAL <br> DEVOLUCION</td>
				<td style="width:100px;"></td>
			</tr>
			</tbody>
		</table>
	</div>

	<div id="footer">
		<table class="container" style="">
			<tr>
				<td style="text-align:right; padding-right:10px; padding:5px 5px;">ENTREGUE CONFORME DESPACHO:</td>
				<td style="width:550px;"></td>
			</tr>
			<tr>
				<td style="text-align:right; padding-right:10px; padding:5px 5px;">RECIBIDO CONFORME VENDEDOR:</td>
				<td></td>
			</tr>
			<tr>
				<td style="text-align:right; padding-right:10px; padding:5px 5px;">CAJAS RECIBIDAS:</td>
				<td></td>
			</tr>
			<tr>
				<td style="text-align:right; padding-right:10px; padding:5px 5px;">CAJAS ENTREGADAS:</td>
				<td></td>
			</tr>
			<tr>
				<td style="text-align:right; padding-right:10px; padding:5px 5px;">PREPARADO POR:</td>
				<td></td>
			</tr>
		</table>
	</div>
</div>
</div>
</body>

</html>


<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title></title>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/theme.css" />
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/skins/default.css" />
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/theme-custom.css">
	<script src="<?php echo base_url()?>assets/js/jquery.js"></script>
	<style>
		body{
			font-size: 9px;
		}
		.bold{font-weight: bolder}
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
			[].forEach.call(document.querySelectorAll("#tablaDevoluciones tr"), function(tr){
				let suma = 0;
				[].forEach.call(tr.querySelectorAll("td:not(.string)"), function(td){
					let datos = td.innerHTML;
					let datosSinComa = datos.replace(",","");
					suma += parseFloat(datosSinComa);
				});
				if(suma > 0){
					[].forEach.call(tr.querySelectorAll(".Totales"), function(td){
						td.innerHTML = suma.toFixed(2);
				});
				}
			});

		});
		window.print();
	</script>
</head>
<body>
<section class="panel" id="printHTML">
	<div class="panel-body">
		<div class="invoice">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-2 mt-md">
						<h2 style="font-size:12px;" class="h2 mt-none mb-sm text-dark text-bold">Reporte de Devoluciones por rutas</h2>
					</div>
					<div class="col-sm-2 mt-md">
						<p class="mb-none">
							<span class="text-dark text-semibold">Desde</span>
							<span class="value"></span>
						</p>
						<?php
						  echo $this->uri->segment(2)
						?>
					</div>
					<div class="col-sm-2 mt-md">
						<p class="mb-none">
							<span class="text-dark text-semibold">Hasta</span>
							<span class="value"></span>
						</p>
						<?php
						   echo $this->uri->segment(3)
						?>
					</div>					
					<div class="col-sm-2 mt-md">
						<p class="mb-none">
							<span class="text-dark text-semibold">Impreso el</span>
							<span class="value"></span>
						</p>
						<?php
						date_default_timezone_set("America/Managua");
						echo date("d-m-Y h:i:s A");
						?>
					</div>
				</div>
			</header>

			<div class="" style="margin-top: -18px">
				<table class="table table-striped table-condensed table-bordered" id="tablaDevoluciones">
					<thead>
						<tr>
							<th class="text-center text-bold">Codigo</th>
							<th class="text-center text-bold" style= "width:80px;">Descripcion</th>
				            <th class="text-center text-bold">ruta1</th>
							<th class="text-center text-bold">ruta2</th>
							<th class="text-center text-bold">ruta3</th>
							<th class="text-center text-bold">ruta4</th>
							<th class="text-center text-bold">ruta5</th>
							<th class="text-center text-bold">ruta6</th>
							<th class="text-center text-bold">ruta7</th>
							<th class="text-center text-bold">ruta8</th>
							<th class="text-center text-bold">ruta9</th>
							<th class="text-center text-bold">ruta10</th>
							<th class="text-center text-bold">ruta11</th>
							<th class="text-center text-bold">ruta12</th>
							<th class="text-center text-bold">ruta13</th>
							<th class="text-center text-bold">ruta14</th>
							<th class="text-center text-bold">ruta15</th>
							<th class="text-center text-bold">ruta16</th>
							<th class="text-center text-bold">ruta17</th>
							<th class="text-center text-bold">ruta18</th>
							<th class="text-center text-bold">ruta19</th>
							<th class="text-center text-bold">ruta21</th>
							<th class="text-center text-bold">ruta22</th>
							<th class="text-center text-bold">ruta23</th>
							<th class="text-center text-bold">ruta24</th>
							<th class="text-center text-bold">ruta25</th>
							<th class="text-center text-bold">ruta26</th>
							<th class="text-center text-bold">ruta27</th>
							<th class="text-center text-bold">ruta28</th>
							<th class="text-center text-bold">ruta30</th>
							<th class="text-center text-bold">ruta31</th>
							<th class="text-center text-bold">ruta32</th>
							<th class="text-center text-bold">ruta33</th>
							<th class="text-center text-bold">ruta34</th>
							<th class="text-center text-bold">Total</th>
						</tr>	
					</thead>
				<tbody>
				<?php
				if(!$dev){
				}else {							
						foreach ($dev as $key) {		
								echo '
									<tr>
										<td class="text-center string">'.$key["Codigo"].'</td>
										<td class="text-center string">'.$key["Descripcion"].'</td>
						                <td class="text-center">'.number_format($key["ruta1"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta2"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta3"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta4"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta5"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta6"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta7"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta8"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta9"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta10"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta11"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta12"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta13"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta14"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta15"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta16"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta17"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta18"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta19"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta21"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta22"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta23"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta24"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta25"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta26"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta27"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta28"],2).'</td>
									    <td class="text-center">'.number_format($key["ruta30"],2).'</td>
										<td class="text-center">'.number_format($key["ruta31"],2).'</td>
										<td class="text-center">'.number_format($key["ruta32"],2).'</td>
										<td class="text-center">'.number_format($key["ruta33"],2).'</td>
										<td class="text-center">'.number_format($key["ruta34"],2).'</td>
										<td class="text-center Totales">0.0</td>
									</tr>';				
					}

				}
				?>
				</tbody></table>
			</div>
			<br>
			<br>			
		</div>
</body>
</html>


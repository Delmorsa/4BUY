
<?PHP /* CABECERA DEL ARCHIVO EXCELL*/
    /*header("Content-type:application/charset='UTF-8'");
    header("Content-Disposition: attachment; filename = Reporte de Consecutivos de Facturas.xls");
    header("Pragma: no-cache");
    header("Expires: 0");*/
?>
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
	<style>
		body{
			font-size: 11px;
		}
		.bold{font-weight: bolder}
	</style>
	<script type="text/javascript">
		//window.print();
	</script>
</head>
<body>
<section class="panel" id="printHTML">
	<div class="panel-body">
		<div class="invoice">
			<header class="clearfix">
				<div class="row">
					<div class="col-sm-2 mt-md">
						<h2 style="font-size:12px;" class="h2 mt-none mb-sm text-dark text-bold">Reporte de Consecutivos de Facturas</h2>
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
				<table class="table table-striped table-condensed table-bordered">
					<thead>
						<tr>
							<th class="text-center" >SERIE</th>
							<th class="text-center">MINIMO</th>
							<th class="text-center">MAXIMO</th>
							<th class="text-center">No FACTURAS</th>
						</tr>	
					</thead>
				<tbody>
				<?php
				if(!$consec){
				}else {							
						foreach ($consec as $cuota) {								
							echo '
							<tr>
								<td class="text-left text-bold">'.$cuota["SERIE"].'</td>
								<td class="text-right">'.$cuota["MINIMO"].'</td>
								<td class="text-right">'.$cuota["MAXIMO"].'</td>
								<td class="text-right">'.$cuota["NUMERO"].'</td>
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


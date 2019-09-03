<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 26/2/2019 11:46 2019
 * FileName: Excel_liquidacion.php
 */
$ruta = '';
if(!$det){
}else{
foreach ($det as $item) {
	$ruta = $item["CODVENDEDOR"];
    }
}
$fecha1 = '';$fecha2 = '';
if(!$liq){
}
else {
	foreach ($liq as $key) {
		$fecha1 = date_format(new DateTime($key["FechaInicio"]), "Y-m-d");
		$fecha2 = date_format(new DateTime($key["FechaFinal"]), "Y-m-d");
	}
}
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
	<script src="<?php echo base_url()?>assets/js/jquery.js"></script>
	<style>
		body{
			font-size: 11px;
		}
		.bold{font-weight: bolder}
		/*#tblDetFactLiq{
			width:100% ;
		}
		#tblDetFactLiq thead th, #tblDetFactLiq tfoot th{ padding: 8px 8px; background: #0a6aa1}
		#tblDetFactLiq {border-collapse: separate;border-spacing: 1px;color: white;}
		#tblDetFactLiq tbody th{color:#1F0A71;font-size: 11px; border-collapse: separate;
			border-spacing: 1px ;border-bottom: 1px solid #ddd;}
		#tblDetFactLiq tbody th{ background: #fff;color: #000; font-size: 14px;}
		.info {color:#2B609A;font-weight: bold;}
		.danger{color:#B32025;font-weight: bold;}
		*/
	</style>
	<script type="text/javascript">
		$(document).ready(function () {
            let myArr = new Array(), results = '';
            let ref = '', remision = 0, dev = 0, cargaPaseante = 0;

			$("#tblDetFactLiq tbody tr").each(function (index, element) {
				ref = $(element).find("th").eq(0).html();
				myArr[index] = ref;
            });
			$.each(myArr, function (i, index) {
                results = myArr.filter(i => i === ''+index+'').length;
                if(results > 1){
                    //$(".codigo"+index).eq(results-1).addClass("danger");
                    remision = $(".rem"+index).eq(results-1).html();
                    dev = $(".dev"+index).eq(results-1).html();
					cargaPaseante = (dev/remision)*100;
                    $(".codigo1"+index).eq(results-1).html(Number(cargaPaseante).toFixed(2)+"%");
                    //console.log("el codigo "+index+" se repite "+results);
					//console.log("remi: "+remision+" dev: "+dev);
				}else{
                    remision = $(".rem"+index).eq(0).html();
                    dev = $(".dev"+index).eq(0).html();
                    cargaPaseante = (dev/remision)*100;
                    $(".codigo1"+index).eq(0).html(Number(cargaPaseante).toFixed(2)+"%");
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
							<h2 style="font-size:12px;" class="h2 mt-none mb-sm text-dark text-bold">LIQUIDACION</h2>
							<?php
							$fecha1 = '';$fecha2 = '';
							if(!$liq){
							}
							else{
								foreach ($liq as $key)
								{
									echo "				
										   <p style='font-size:11px;' class='bold text-dark text-semibold'>
											 Desde : ".date_format(new DateTime($key["FechaInicio"]),"Y-m-d h:i:s")." 
										   </p>
										   <p style='font-size:11px;' class='bold text-dark text-semibold'>
											 Hasta: ".date_format(new DateTime($key["FechaFinal"]), "Y-m-d h:i:s")."
											</p>
										 ";
								}
							}
							?>
						</div>
						<div class="col-sm-3 mt-md">
							<?php
							$ruta = ''; $vendedor = '';
							if(!$det){
							}else{
								foreach ($det as $item) {
									$ruta = $item["CODVENDEDOR"];
									$vendedor = $item["NomVendedor"]." ".$item["Apellidos"];
								}
								echo '
											<p style="font-size:11px;" class="h5 mb-xs text-dark text-semibold">Datos vendedor</p>
											<address>
											<p class="mb-none">
												<span class="text-dark">Ruta:</span>
												<span class="value" id="ruta">'.$ruta.'</span>
											</p>
											<p class="mb-none">
												<span class="text-dark">Nombre:</span>
												<span class="value">'.$vendedor.'</span>
											</p>
											<p class="mb-none">
												<span class="text-dark">Tipo Venta:</span>
												<span class="value" id="tipo">Venta</span>
											</p>
											</address>
										';
							}
							?>
						</div>
						<div class="col-sm-2 mt-md">
							<p class="mb-none">
								<span class="text-dark text-semibold">Nota</span>
								<span class="value"></span>
							</p>

							<?php
							if(!$liq){
							}else{
								foreach($liq as $key){
									if($key["Liquidado"] == "N"){
										echo '
													<p class="mb-none">
														<span class="danger">Pendiente liquidar</span>
														<span class="value"></span>
													</p>
												';
									}else{
										echo '
													<p class="mb-none">
														<span class="info text-primary text-semibold">Liquidado</span>
														<span class="value"></span>
													</p>
												';
									}
								}
							}
							?>
						</div>
						<div class="col-sm-2 mt-md">
							<p class="mb-none">
								<span class="text-dark text-semibold">Liquidador</span>
								<span class="value"></span>
							</p>
							<?php
							   echo $this->session->userdata('Name')." ".$this->session->userdata('Apelli');
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
					<table id="tblDetFactLiq" class="table table-striped table-condensed table-bordered">
						<thead>
						<tr class="text-dark">
							<th>Codigo</th>
							<th class="text-right" style="width: 100px">Descrip</th>
							<th class="text-right">Peso <br> Gramos</th>
							<th class="text-right">Precio</th>
							<th class="text-right">Remision</th>
							<th class="text-right">Devol.</th>
							<th class="text-right">UVend <br>Cred</th>
							<th class="text-right">UVend <br>Cont</th>
							<th class="text-right">Unid <br> Total</th>
							<th class="text-right">SubTot</th>
							<th class="text-right">SubTot <br> Cred</th>
							<th class="text-right">Dto</th>
							<th class="text-right">Dt <br> cred</th>
							<th class="text-right">ISC</th>
							<th class="text-right">IVA</th>
							<th class="text-right">Tot <br> Contado</th>
							<th class="text-right">Tot <br> Credito</th>
							<th class="text-right">Libras <br> Vendidas</th>
							<th class="text-right">Lbs <br> Merma</th>
							<th class="text-right">Carga<br> Paseante</th>
						</tr>
						</thead>
						<tbody>
						<?php
						$devolucion = 0;
						$acumulado = 0;
						$paseante = 0;
						$i = 0;
						$bandera = false;
						//$codanterior = '';
						$codsiguiente ='';
						if(!$liqdet){
						}else{
							foreach ($liqdet as $item) {
								echo "
									   <tr style='font-size:9px;'>
											<th class='codigo".$item["Codigo"]."'>".$item["Codigo"]."</th>
										<th data-toggle='tooltip' title='".$item["Descripcion"]."' data-placement='top'>
										".substr($item["Descripcion"],0,15)."</th>
										<th>".$item["PesoGramos"]."</th>
										<th>".number_format($item["Precio"],2)."</th>
								        <th class='rem".$item["Codigo"]."'>".number_format($item["Carga"],2)."</th>
								        <th class='dev".$item["Codigo"]."'>".number_format($item["Devolucion"],2)."</th> 
								        <th>".number_format($item["UnidadesVenCredito"],2)."</th>
										<th>".number_format($item["UnidadesVenContado"],2)."</th>
										<th>".number_format($item["UnidadesVenTotal"],2)."</th>
										<th>".number_format($item["SubtotalContado"],2)."</th>
										<th>".number_format($item["SubtotalCredito"],2)."</th>
										<th>".number_format($item["DescContado"],2)."</th>
										<th>".number_format($item["DescCredito"],2)."</th>
										<th>".number_format(($item["IscContado"]+$item["IscCredito"]),2)."</th>
										<th>".number_format(($item["IvaContado"]+$item["IvaCredito"]),2)."</th>
										<th>".number_format($item["TotalContado"],2)."</th>
										<th>".number_format($item["TotalCredito"],2)."</th>
										<th>".number_format($item["LibrasVendidas"],2)."</th>
										<th>".number_format($item["Merma"],2)."</th>
										<th class='codigo1".$item["Codigo"]."'>0.0%</th>
									  </tr> 
								   ";
								$i++;
							}
							//<th class='codigo1".$item["Codigo"]."'>".number_format(($item["Devolucion"]/$item["Carga"])*100,2)."</th>
						}
						?>
						</tbody>
						<tfoot>
						<?php
						$devolucion = 0;
						$acumulado = 0;
						$i = 0;
						$bandera = false;
						$remision = 0; $dev = 0; $unidCred = 0; $unid = 0; $unidTotal = 0;
						$subtotal = 0; $subcred= 0; $dto = 0; $dtocred = 0; $isc = 0; $isccred = 0;
						$iva = 0; $ivacred = 0; $total = 0; $totalcred = 0; $libras = 0;
						if(!$liqdet){
						}else{
							foreach ($liqdet as $item) {

								 $remision += $item["Carga"];

								$unidCred += $item["UnidadesVenCredito"];
								$unid += $item["UnidadesVenContado"];
								$unidTotal += $item["UnidadesVenTotal"];
								 $subtotal += $item["SubtotalContado"];
								 $subcred += $item["SubtotalCredito"];
								 $dto += $item["DescContado"];
								 $dtocred += $item["DescCredito"];
								 $isc += $item["IscContado"];
								 $isccred += $item["IscCredito"];
								 $iva += $item["IvaContado"];
								 $ivacred += $item["IvaCredito"];
								 $total += $item["TotalContado"];
								 $totalcred += $item["TotalCredito"];
								 $libras += $item["LibrasVendidas"];
							}
							echo "
									   <tr class='bg-primary' style='font-size:8pt;'>
											<th>TOTAL</th>
											<th></th>
											<th></th>
											<th></th>
											<th class='text-right bold'>".$remision."</th>
											<th class='text-right bold'></th>
											<th class='text-right bold'>".$unidCred."</th>
											<th class='text-right bold'>".$unid."</th>
											<th class='text-right bold'>".$unidTotal."</th>
											<th class='text-right bold'>".number_format($subtotal,2)."</th>
											<th class='text-right bold'>".number_format($subcred,2)."</th>
											<th class='text-right bold'>".number_format($dto,2)."</th>
											<th class='text-right bold'>".number_format($dtocred,2)."</th>
											<th class='text-right bold'>".number_format(($isc+$isccred),2)."</th>
											<th class='text-right bold'>".number_format(($iva+$ivacred),2)."</th>
											<th class='text-right bold'>".number_format($total,2)."</th>
											<th class='text-right bold'>".number_format($totalcred,2)."</th>
											<th class='text-right bold'>".number_format($libras,2)."</th>
											<th class='text-right bold'></th>
											<th class='text-right bold'></th>
										</tr> 
								   ";
						}
						?>
						</tfoot>
					</table>
				</div>
				<br>
				<br>
				<div class="row center" style="margin-top: -15px">
					<div class="col-4 col-sm-4 col-md-4">
						<p>----------------------------------------------------------</p>
						  <p>Firma Vendedor</p>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<p>----------------------------------------------------------</p>
						<p>Firma Liquidador</p>
					</div>
					<div class="col-4 col-sm-4 col-md-4">
						<p>----------------------------------------------------------</p>
						<p>Firma Responsable Caja</p>
					</div>
				</div>
				<!--<div class="invoice-summary">
					<div class="row">
						<div class="col-sm-8">
						</div>
						<div class="col-sm-4 col-sm-offset-0">
							<table class="table h5 text-dark">
								<tbody>
								<?php
								/*$sumsubtotal = 0; $total = 0; $isc = 0; $iva = 0;
									echo '
											<tr class="b-top-none">
												<td colspan="1">SUBTOTAL</td>
												<td class="text-left">C$ '.number_format($sumsubtotal,2).'</td>
											</tr>
											<tr>
												<td colspan="1">ISC</td>
												<td class="text-left">C$ '.number_format($isc,2).'</td>
											</tr>
											<tr>
												<td colspan="1">IVA</td>
												<td class="text-left">C$ '.number_format($iva,2).'</td>
											</tr>
											<tr class="h4">
												<td colspan="1">Total</td>
												<td class="">C$ '.number_format($total,2).'</td>
											</tr>
										';*/
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>-->
			</div>



</body>
</html>

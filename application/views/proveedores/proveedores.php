<div class="row">
	<div class='col-12 col-sm-12 col-md-12'>
		<!--<div class="pull-right">
			<button id="btnActualizarFacturas" class="mb-xs mt-xs mr-xs btn btn-primary">
				Actualizar <i class="fa fa-download"></i>
			</button>
		</div> -->
	</div>
</div>
<!-- start: page -->
<div class="row">
	<div class="col-12 col-sm-12 col-md-12">
		<section class="panel col-12 col-sm-12 col-md-12">
			<header class="panel-heading">
				<div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
				</div>

				<h2 class="panel-title">Listado de pagos a proveedores</h2>
			</header>
			<div class="panel-body">
				<div class="pull-right">
						<a id="btnExcel" onclick="tableToExcel('testTable', 'W3C Example Table')"" class="mb-xs mt-xs mr-xs btn btn-primary">
							Excel <i class="fa fa-download"></i>
						</a>
					</div>
				<div>
					<div class="col-12 col-sm-12 col-md-12 col-lg-12" style="width: 100%;overflow-x: scroll;">
						<table class="table table-bordered table-stripedtable-condensed" id="dtPAgos">
							<thead>
							<tr>
								<th>NO Doc</th>
								<th>Ref</th>
								<th>Código</th>
								<th>Cliente</th>							
								<th>Tipo</th>								
								<th>DocTotal</th>
								<th>Comments</th>
								<th>Fecha Fac</th>
								<th>Vencimiento</th>
								<th>Estado</th>
								<th>Corriente</th>
								<th>1-30</th>
								<!--<th>31-60</th>
								<th>61-90</th>
								<th>91-120</th>
								<th>121-+</th>-->
								<th>CheckNum</th>
								<th>BankCode</th>
								<th>TASA</th>
							</tr>
							</thead>
							<tbody style="font-size: 11.5px;">
								<?php foreach ($pagos as $key){
									$vencida = 'Vigente';
									if ($key["Dias"]>0) {
										$vencida = '<p class="text-danger">vencida</p>';
									}
									echo"<tr>
									<td>".$key["DocNum"]."</td>
									<td>".$key["NumAtCard"]."</td>
									<td>".$key["CardCode"]."</td>
									<td>".utf8_encode($key["CardName"])."</td>				
									<td>".$key["TIPO"]."</td>
									<td>".number_format($key["DocTotal"],2)."</td>
									<td>".utf8_encode($key["Comments"])."</td>
									<td>".$key["Fecha_Factura"]."</td>
									<td>".$key["Vencimiento"]."</td>
									<td>".$vencida."</td>
									<td>".number_format($key["Corriente"],2)."</td>
									<td>".number_format($key["1-30"],2)."</td>
									<td>".$key["CheckNum"]."</td>
									<td>".$key["BankCode"]."</td>
									<td>".number_format($key["TASA"],2)."</td>
									</tr>";
								} ?>

							</tbody>							
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
	var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById("dtPAgos")
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>

<script type="text/javascript">
	$(document).ready(function () {

	});

	$("#btnFechaAjax").click(function(){
		let ruta = $("#ddlRutas option:selected").val();
		let fechaInicio = $("#fechaInicio").val();
		let fechaFin = $("#fechaFin").val();

		if(ruta == '' || fechaInicio == '' || fechaFin == ''){
			if(ruta == ''){
				swal({
					type: "error",
					text: "Debe seleccionar una ruta",
					allowOutsideClick: false
				});
			}else if(fechaInicio == ''){
				swal({
					type: "error",
					text: "Debe seleccionar una fecha de inicio",
					allowOutsideClick: false
				});
			}else if(fechaFin == ''){
				swal({
					type: "error",
					text: "Debe seleccionar una fecha final",
					allowOutsideClick: false
				});
			}
		}else if(fechaInicio > fechaFin){
			swal({
				text: "La fecha de inicio debe ser menor a la fecha final",
				type: "error",
				allowOutsideClick: false
			});
		}else{
			$("#dtFacturasAjax").DataTable({
				"ajax": {
					"url": "FacturasAjax/"+ruta+"/"+fechaInicio+"/"+fechaFin,
					"type": "POST"
				},
				"processing": true,
				"info": true,
				"sort": true,
				"destroy": true,
				"responsive": false,
				"autoWidth": false,
				"lengthMenu": [
					[10,20,50,100, -1],
					[10,20,50,100, "Todo"]
				],
				"order": [
					[0, "asc"]
				],
				"language": {
					"info": "Registro _START_ a _END_ de _TOTAL_ entradas",
					"infoEmpty": "Registro 0 a 0 de 0 entradas",
					"zeroRecords": "No se encontro coincidencia",
					"infoFiltered": "(filtrado de _MAX_ registros en total)",
					"emptyTable": "NO HAY DATOS DISPONIBLES",
					"lengthMenu": '_MENU_ ',
					"search": '<i class="fa fa-search"></i>',
					"loadingRecords": "",
					"processing": "Procesando datos  <i class='fa fa-spin fa-refresh'></i>",
					"paginate": {
						"first": "Primera",
						"last": "Última ",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				},
				"columns": [
					{"data" : "FECHA"},
					{"data" : "TIEMPO"},
					{"data" : "IDFACTURASERIE"},
					{"data" : "IDFACTURACONSECUTIVO"},
					{"data" : "CODCLIENTE"},
					{"data" : "NOMBRE"},
					{"data" : "CONDPAGO"},
					{"data" : "SUBTOTAL"},
					{"data" : "DESCUENTO"},
					{"data" : "ISC"},
					{"data" : "IVA"},
					{"data" : "TOTAL_CREDITO"},
					{"data" : "TOTAL_CONTADO"},
					{"data" : "Detalles"}
				],
				drawCallback: function () {
					let api = this.api();
					$( api.table().column(7).footer() ).html(
						"C" + new Intl.NumberFormat("en",
						{
							style: "currency",
							currency:"USD"
						}).format(api.column( 7, {page:'current'} ).data().sum())
					);
					$( api.table().column(8).footer() ).html(
						"C" + new Intl.NumberFormat("en",
						{
							style: "currency",
							currency:"USD"
						}).format(api.column( 8, {page:'current'} ).data().sum())
					);
					$( api.table().column(9).footer() ).html(
						"C" + new Intl.NumberFormat("en",
						{
							style: "currency",
							currency:"USD"
						}).format(api.column( 9, {page:'current'} ).data().sum())
					);
					$( api.table().column(10).footer() ).html(
						"C" + new Intl.NumberFormat("en",
						{
							style: "currency",
							currency:"USD"
						}).format(api.column( 10, {page:'current'} ).data().sum())
					);
					$( api.table().column(11).footer() ).html(
						"C" + new Intl.NumberFormat("en",
						{
							style: "currency",
							currency:"USD"
						}).format(api.column( 11, {page:'current'} ).data().sum().toFixed(2))
					);
					$( api.table().column(12).footer() ).html(
						"C" + new Intl.NumberFormat("en",
						{
							style: "currency",
							currency:"USD"
						}).format(api.column( 12, {page:'current'} ).data().sum().toFixed(2))
					);
				}
			});
		}
	});

	function Sumar(){
		let table = $("#dtFacturasAjax").DataTable();
		//let sumSubtotal = 0, sumDesc = 0, sumISC = 0, sumIVA = 0,sumTotal = 0;
		let num = 0;

		table.rows().eq(0).each(function(index){
			let row = table.row(index);
			let data = row.data();
			/*sumSubtotal += Number(data.SUBTOTAL.replace(",",""));
			sumDesc += Number(data.DESCUENTO.replace(",",""));
			sumISC += Number(data.ISC.replace(",",""));
			sumIVA += Number(data.IVA.replace(",",""));
			sumTotal += Number(data.TOTAL.replace(",",""));*/
			num += table.row().length;
		});

		/*$("#SubTotal").html(sumSubtotal.toFixed(2));
		$("#Desc").html(sumDesc.toFixed(2));
		$("#ISC").html(sumISC.toFixed(2));
		$("#IVA").html(sumIVA.toFixed(2));
		$("#Total").html(sumTotal.toFixed(2));*/
		$("#numFact").html(num);
	}

	$('#dtFacturasAjax').on( 'init.dt', function () {
		Sumar();
	} );

	function detalleFactura(idEncabezado) {
		$("#tabla").html("");
		$("#subtotal").html();
		$("#isc").html();
		$("#iva").html();
		$("#total").html();
		let subtotal = 0, subtotalsuma = 0, iva = 0, isc = 0, total = 0;
		$.ajax({
			url: "detallesFacturasAjax/"+idEncabezado,
			type: "POST",
			async:true,
			success: function (data) {
				$.each(JSON.parse(data), function (i, item) {
					subtotal = item.TOTAL - item.IVA - item.ISC;
					subtotalsuma += subtotal;
					iva += Number(item.IVA);
					isc += Number(item.ISC);
					total += Number(item.TOTAL);

					$("#DocumentoDet").val(item.IDFACTURA);
					$("#FechaDet").val(item.FECHA);
					$("#RutaDet").val(item.CODVENDEDOR);
					$("#PagoDet").val(item.CODCONDPAGO);
					$("#ClienteDet").val(item.NOMBRE);

					$("#tabla").append(
						"<tr>"+
						"<td>"+Number(item.NUMLINEA).toFixed(0)+"</td>"+
						"<td>"+Number(item.CODIGO).toFixed(0)+"</td>"+
						"<td>"+item.DESCRIPCION+"</td>"+
						"<td>"+Number(item.CANTIDAD).toFixed(0)+"</td>"+
						"<td>"+Number(item.PRECIO).toFixed(2)+"</td>"+
						"<td>"+Number(subtotal).toFixed(2)+"</td>"+
						"<td>"+Number(item.IVA).toFixed(2)+"</td>"+
						"<td>"+Number(item.ISC).toFixed(2)+"</td>"+
						"<td>"+Number(item.TOTAL).toFixed(2)+"</td>"+
						"<td>"+Number(item.CODALMACEN).toFixed(0)+"</td>"+
						"</tr>"
					);
				});
				$("#subtotal").html("C" + new Intl.NumberFormat("en",{style: "currency",currency:"USD"}).format(subtotalsuma.toFixed(2)));
				$("#isc").html("C" + new Intl.NumberFormat("en",{style: "currency",currency:"USD"}).format(isc.toFixed(2)));
				$("#iva").html("C" + new Intl.NumberFormat("en",{style: "currency",currency:"USD"}).format(iva.toFixed(2)));
				$("#total").html("C" + new Intl.NumberFormat("en",{style: "currency",currency:"USD"}).format(total.toFixed(2)));
			}
		});
		$("#modalDetFactura").modal("show");
	}

$("#btnIntegrar").click(function(){
	$("#loading").modal("show");
	let form_data = {
		ruta: $("#ddlRutas option:selected").val(),
		fechaInicio: $("#fechaInicio").val(),
		fechaFin: $("#fechaFin").val(),
		fechaInt: $("#fechaInt").val()
	};

	$.ajax({
		url: "Integrar",
		type: "POST",
		data: form_data,
		success: function (data) {
			$("#loading").modal("hide");
			swal({
				text: "Facturas integradas",
				type: "success",
				allowOutsideClick: false
			}).then(result => {
				$("#dtFacturasAjax").DataTable({
					"ajax": {
						"url": "FacturasAjax/"+$("#ddlRutas option:selected").val()+"/"+$("#fechaInicio").val()+"/"+$("#fechaFin").val(),
						"type": "POST"
					},
					"processing": true,
					"responsive": false,
					"info": true,
					"sort": true,
					"destroy": true,
					"lengthMenu": [
						[10,20,50,100, -1],
						[10,20,50,100, "Todo"]
					],
					"order": [
						[0, "asc"]
					],
					"language": {
						"info": "Registro _START_ a _END_ de _TOTAL_ entradas",
						"infoEmpty": "Registro 0 a 0 de 0 entradas",
						"zeroRecords": "No se encontro coincidencia",
						"infoFiltered": "(filtrado de _MAX_ registros en total)",
						"emptyTable": "NO HAY DATOS DISPONIBLES",
						"lengthMenu": '_MENU_ ',
						"search": '<i class="fa fa-search"></i>',
						"loadingRecords": "",
						"processing": "Procesando datos  <i class='fa fa-spin fa-refresh'></i>",
						"paginate": {
							"first": "Primera",
							"last": "Última ",
							"next": "Siguiente",
							"previous": "Anterior"
						}
					},
					"columns": [
						{"data" : "FECHA"},
						{"data" : "TIEMPO"},
						{"data" : "IDFACTURASERIE"},
						{"data" : "IDFACTURACONSECUTIVO"},
						{"data" : "CODCLIENTE"},
						{"data" : "NOMBRE"},
						{"data" : "CONDPAGO"},
						{"data" : "SUBTOTAL"},
						{"data" : "DESCUENTO"},
						{"data" : "ISC"},
						{"data" : "IVA"},
						{"data" : "TOTAL_CREDITO"},
						{"data" : "TOTAL_CONTADO"},
						{"data" : "Detalles"}
					],
					drawCallback: function () {
						let api = this.api();
						$( api.table().column(7).footer() ).html(
							"C" + new Intl.NumberFormat("en",
							{
								style: "currency",
								currency:"USD"
							}).format(api.column( 7, {page:'current'} ).data().sum())
						);
						$( api.table().column(8).footer() ).html(
							"C" + new Intl.NumberFormat("en",
							{
								style: "currency",
								currency:"USD"
							}).format(api.column( 8, {page:'current'} ).data().sum())
						);
						$( api.table().column(9).footer() ).html(
							"C" + new Intl.NumberFormat("en",
							{
								style: "currency",
								currency:"USD"
							}).format(api.column( 9, {page:'current'} ).data().sum())
						);
						$( api.table().column(10).footer() ).html(
							"C" + new Intl.NumberFormat("en",
							{
								style: "currency",
								currency:"USD"
							}).format(api.column( 10, {page:'current'} ).data().sum())
						);
						$( api.table().column(11).footer() ).html(
							"C" + new Intl.NumberFormat("en",
							{
								style: "currency",
								currency:"USD"
							}).format(api.column( 11, {page:'current'} ).data().sum().toFixed(2))
						);
						$( api.table().column(12).footer() ).html(
							"C" + new Intl.NumberFormat("en",
							{
								style: "currency",
								currency:"USD"
							}).format(api.column( 12, {page:'current'} ).data().sum().toFixed(2))
						);
					}
				});
			});
		}
	});

});
</script>

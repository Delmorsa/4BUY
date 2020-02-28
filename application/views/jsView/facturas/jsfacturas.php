<script type="text/javascript">
$(document).ready(function(){
	$("#searchSelect_regex").select2({
		allowClear: true,
		placeholder: '--- Ruta---'
	});

	$("#searchSelect_regexTipo").select2({
		allowClear: true,
		placeholder: '--- Tipo Factura ---'
	});
	cargarFacturas();
/*	setInterval(function () {
		cargarFacturas();
	},120000);*/
});

$("#btnActualizarFacturas").click(function () {
	if($("#fechaFac1").val() > $("#fechaFac2").val()){
		swal({
			text: "La fecha de inicio no puede ser mayor a la fecha fin",
			type: "error",
			allowOutsideClick: false
		});
	}else{
		cargarFacturas();
	}
});

function cargarFacturas(){
	let sumSubtotal = 0, sumDesc = 0, sumISC = 0, sumIVA = 0,sumTotal = 0;
	let table = $("#dtFacturas").DataTable({
		"ajax": {
			"url": "MostrarFacturas",
			"type": "POST",
			"data": function ( d ) {
				d.fecha1 = $("#fechaFac1").val();
				d.fecha2 = $("#fechaFac2").val();
				d.ruta = $("#searchSelect_regex option:selected").val();
				d.tipo = $("#searchSelect_regexTipo option:selected").val(); 
				// d.custom = $('#myInput').val();
				// etc
			}
		},
		"processing": true,
		"serverSide": true,
		"orderMulti": false,
		"info": true,
		"sort": true,
		"destroy": true,
		"responsive": true,
		"lengthMenu": [
			[10,20,50,100, -1],
			[10,20,50,100, "Todo"]
		],
		"order": [
			[3, "desc"]
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
		{"data" : "ESTADOAPP"},
		{"data" : "ESTADOAPP1"},
		{"data" : "IDFACTURASERIE"},
		{"data" : "IDFACTURACONSECUTIVO"},
		{"data" : "CODCLIENTE"},
		{"data" : "NOMBRE"},
		{"data" : "CODVENDEDOR"},
		{"data" : "SUBTOTAL"},
		{"data" : "DESCUENTO"},
		{"data" : "ISC"},
		{"data" : "IVA"},
		{"data" : "TOTAL"},
		{"data" : "Detalles"}
	],
		"columnDefs": [
		{
			"targets": [1],
			"visible": false,
			"orderable": false
		}
	],
		drawCallback: function () {
		let api = this.api();
		let arrayDet =  new Array(), i = 0;
		let sub = 0, desc = 0, isc = 0, iva = 0, total = 0;
		table.rows().eq(0).each(function (index) {
			let row = table.row(index);
			let data = row.data();
			if(data.ESTADOAPP == 4){
				sub += parseFloat(data.SUBTOTAL.replace(",",""));
				desc += parseFloat(data.DESCUENTO.replace(",",""));
				isc += parseFloat(data.ISC.replace(",",""));
				iva += parseFloat(data.IVA.replace(",",""));
				total += parseFloat(data.TOTAL.replace(",",""));
			}
		});



		$( api.table().column(8).footer() ).html(
			"C" + new Intl.NumberFormat("en",
			{
				style: "currency",
				currency:"USD"
			}).format(api.column( 8, {page:'current'} ).data().sum() - sub)
		);
		$( api.table().column(9).footer() ).html(
			"C" + new Intl.NumberFormat("en",
			{
				style: "currency",
				currency:"USD"
			}).format(api.column( 9, {page:'current'} ).data().sum() - desc)
		);
		$( api.table().column(10).footer() ).html(
			"C" + new Intl.NumberFormat("en",
			{
				style: "currency",
				currency:"USD"
			}).format(api.column( 10, {page:'current'} ).data().sum() - isc)
		);
		$( api.table().column(11).footer() ).html(
			"C" + new Intl.NumberFormat("en",
			{
				style: "currency",
				currency:"USD"
			}).format(api.column( 11, {page:'current'} ).data().sum() - iva)
		);
		$( api.table().column(12).footer() ).html(
			"C" + new Intl.NumberFormat("en",
			{
				style: "currency",
				currency:"USD"
			}).format(api.column( 12, {page:'current'} ).data().sum().toFixed(2) - total)
		);
	}
	});
}


<!--region SUMA DE CANTIDADES Y LIBRAS-->
/*function Sumar(){
	let table = $("#dtFacturas").DataTable();
	let sumSubtotal = 0, sumDesc = 0, sumISC = 0, sumIVA = 0,sumTotal = 0;

	table.rows().eq(0).each(function(index){
		let row = table.row(index);
		let data = row.data();
		sumSubtotal += Number(data.SUBTOTAL.replace(",",""));
		sumDesc += Number(data.DESCUENTO.replace(",",""));
		sumISC += Number(data.ISC.replace(",",""));
		sumIVA += Number(data.IVA.replace(",",""));
		sumTotal += Number(data.TOTAL.replace(",",""));
	});

	$("#SubTotal").html("C" + new Intl.NumberFormat("en",{style: "currency", currency:"USD"}).format(sumSubtotal.toFixed(2)));
	$("#Desc").html("C" + new Intl.NumberFormat("en",{style: "currency", currency:"USD"}).format(sumDesc.toFixed(2)));
	$("#ISC").html("C" + new Intl.NumberFormat("en",{style: "currency", currency:"USD"}).format(sumISC.toFixed(2)));
	$("#IVA").html("C" + new Intl.NumberFormat("en",{style: "currency", currency:"USD"}).format(sumIVA.toFixed(2)));
	$("#Total").html("C" + new Intl.NumberFormat("en",{style: "currency", currency:"USD"}).format(sumTotal.toFixed(2)));
}*/
<!--endregion-->
</script>

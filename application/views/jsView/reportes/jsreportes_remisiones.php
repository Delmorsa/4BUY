<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 13/3/2019 15:10 2019
 * FileName: jsreportes_remisiones.php
 */
?>
<script type="text/javascript">

	$("#chkTodos").change(function () {
		if($(this).prop("checked") == true){
			$("#chkOrden").prop("checked",true);
			$("#chkPreventa").prop("checked",true);
			$("#chkRecargo").prop("checked",true);
			$("#chkAdelanto").prop("checked",true);
            $("#chkWalmart").prop("checked",true);
            $("#chkcasaMant").prop("checked",true);
            $("#chkindepend").prop("checked",true);
		}else{
			$("#chkOrden").prop("checked",false);
			$("#chkPreventa").prop("checked",false);
			$("#chkRecargo").prop("checked",false);
			$("#chkAdelanto").prop("checked",false);
            $("#chkWalmart").prop("checked",false);
            $("#chkcasaMant").prop("checked",false);
            $("#chkindepend").prop("checked",false);
		}
	});

$("#btnReportesRem").click(function () {
	let array = new Array();
	$("input[name='chk']:checked").each(function () {
		array.push($(this).val());
	});
	if($("#fechaEntrega").val() > $("#fechaFin").val()){
		swal({
			text: "La fecha de inicio no puede saer mayor a la fecha final",
			type: "error",
			allowOutsideClick: false
		});
	}else if($("#fechaEntrega").val() == "" || $("#fechaFin").val() == ""){
		swal({
			text: "Debe ingresar ambas fechas",
			type: "error",
			allowOutsideClick: false
		});
	}else if(array == ""){
		swal({
			text: "Debe seleccinar un tipo",
			type: "error",
			allowOutsideClick: false
		});
	}else if($("#Rubro option:selected").val() == ""){
		swal({
			text: "Debe debe seleccionar un rubro",
			type: "error",
			allowOutsideClick: false
		});
	}else{
		let table = $("#tblRemisionesReport").DataTable({
			"ajax": {
				"url": "ReportesRemision",
				"type": "POST",
				"data": function ( d ) {
					d.fechaEntrega = $("#fechaEntrega").val();
					d.fechaFin = $("#fechaFin").val();
					d.codRubro = $("#Rubro option:selected").val();
					d.codTipo = array;
					d.ruta = $("#Ruta option:selected").val();
				}
			},
			"processing": true,
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
				{"data" : "FechaEntrega"},
				{"data" : "CantTotal"},
				{"data" : "TotalLbs"},
				{"data" : "Rubro"},
				{"data" : "Tipo"},
				{"data" : "IdRuta"},
				{"data" : "FechaLiq"},
				{"data" : "FechaEdita"},
				{"data" : "FechaBaja"},
				{"data" : "Estado"},
				{"data" : "Detalles"}
			]
		});
	}
});

function showDetails(idRemision,rubro,tipo,fecha,cant,cantTotal){
	$("#link").attr('href','PrintRemision/'+idRemision);
	$("#txtRubro").html(rubro);
	$("#txtTipo").html("("+tipo+")");
	$("#FechaEntDt").val(fecha);
	$("#CantTotal").val(cant);
	$("#CantlbsTotal").val(cantTotal);
	let table = $("#tblDetalles").DataTable({
		"ajax": {
			"url": "DetallesReportesRemision/"+idRemision,
			"type": "POST"
		},
		"processing": true,
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
			{"data" : "IdRuta"},
			{"data" : "Vendedor"},
			{"data" : "Referencia"},
			{"data" : "CodigoProd"},
			{"data" : "DescripcionProd"},
			{"data" : "LBS"},
			{"data" : "Cantidad"},
			{"data" : "CantidadLBS"}
		]
	});
	$("#modalDetalles").modal("show");
}
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#tblDevoluciones").DataTable({
			"scrollX": true,
			"destroy": true,
			"lengthMenu": [
			[10,20,50,100, -1],
			[10,20,50,100, "Todo"]
		],
		fixedColumns:   {
            leftColumns: 1
        },
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
		}
		});	
	});


$("#btnGenerarRpt").click(function(){
	if($("#fechaFac1").val() > $("#fechaFac2").val()){
		swal({
			text: "La fecha de inicio no puede ser mayor a la fecha final",
			type: "error",
			allowOutsideClick: false
		});
	}else if($("#fechaFac1").val() == "" || $("#fechaFac2").val() == ""){
		swal({
			text: "Debe ingresar ambas fechas",
			type: "error",
			allowOutsideClick: false
		});
	}else{
		$("#tblDevoluciones").DataTable({
			"ajax":{
				"url": "devolucionesPorRutas",
				"type": "POST",
				"data": function (d){
					d.fecha1 = $("#fechaFac1").val();
					d.fecha2 = $("#fechaFac2").val();	
				}
			},
			"scrollX": true,
			"destroy": true,
			"processing": true,
			"lengthMenu": [
			[10,20,50,100, -1],
			[10,20,50,100, "Todo"]
		],
		fixedColumns:   {
            leftColumns: 2
        },
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
		{"data" : "Codigo"},
		{"data" : "Descripcion"},
		{"data" : "ruta1"},
		{"data" : "ruta2"},
		{"data" : "ruta3"},
		{"data" : "ruta4"},
		{"data" : "ruta5"},
		{"data" : "ruta6"},
		{"data" : "ruta7"},
		{"data" : "ruta8"},
		{"data" : "ruta9"},
		{"data" : "ruta10"},
		{"data" : "ruta11"},
		{"data" : "ruta12"},
		{"data" : "ruta13"},
		{"data" : "ruta14"},
		{"data" : "ruta15"},
		{"data" : "ruta16"},
		{"data" : "ruta17"},
		{"data" : "ruta18"},
		{"data" : "ruta19"},
		{"data" : "ruta21"},
		{"data" : "ruta22"},
		{"data" : "ruta23"},
		{"data" : "ruta24"},
		{"data" : "ruta25"},
		{"data" : "ruta26"},
		{"data" : "ruta27"},
		{"data" : "ruta28"},
		{"data" : "ruta30"},
		{"data" : "ruta31"},
		{"data" : "ruta32"},
		{"data" : "ruta33"},
		{"data" : "ruta34"}
	]
	});	
	}
	
});

$("#printDevoluciones").click(function(){
	if($("#fechaFac1").val() > $("#fechaFac2").val()){
		swal({
			text: "La fecha de inicio no puede ser mayor a la fecha final",
			type: "error",
			allowOutsideClick: false
		});
	}else if($("#fechaFac1").val() == "" || $("#fechaFac2").val() == ""){
		swal({
			text: "Debe ingresar ambas fechas",
			type: "error",
			allowOutsideClick: false
		});
	}else{
		let win = window.open('printDevoluciones/'+$("#fechaFac1").val()+"/"+$("#fechaFac2").val(), '_blank');
	}
});
</script>
<script type="text/javascript">
$(document).ready(function(){
    $("#Ruta").select2({
		allowClear: true,
		placeholder: '--- Seleccione una ruta---'
	});
});

$("#btnActualizar").click(function(){
    if($("#fechainicio").val() > $("#fechafin").val()){
		swal({
			text: "La fecha de inicio no puede ser mayor a la fecha final",
			type: "error",
			allowOutsideClick: false
		});
	}else if($("#fechainicio").val() == "" || $("#fechafin").val() == ""){
		swal({
			text: "Debe ingresar ambas fechas",
			type: "error",
			allowOutsideClick: false
		});
	}else{
        let table = $("#tblrptVentas").DataTable({
			"ajax": {
				"url": "ajax_reporte_facturas_empleados",
				"type": "POST",
				"data": function ( d ) {
					d.fechainicio = $("#fechainicio").val();
					d.fechafinal = $("#fechafin").val();
					d.ruta = $("#Ruta option:selected").val();
				}
			},
			"processing": true,
			"info": true,
			"sort": true,
			"destroy": true,
			"lengthMenu": [
				[10,20,50,100, -1],
				[10,20,50,100, "Todo"]
			],
			"order": [
				[1, "asc"]
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
				{"data" : "CODCLIENTE"},
				{"data" : "NOMBRE"},
				{"data" : "IDFACTURA"},
				{"data" : "CODVENDEDOR"},
				{"data" : "NOMBREVENDEDOR"},
				{"data" : "total"},
				{"data" : "CANTIDAD"}
			]		
		});
    }
});

$("#printRptVentasDep").click(function(){
	if($("#fechainicio").val() > $("#fechafin").val()){
		swal({
			text: "La fecha de inicio no puede ser mayor a la fecha final",
			type: "error",
			allowOutsideClick: false
		});
	}else if($("#fechainicio").val() == "" || $("#fechafin").val() == ""){
		swal({
			text: "Debe ingresar ambas fechas",
			type: "error",
			allowOutsideClick: false
		});
	}else{
        let parametro = '';
        if($("#Ruta option:selected").val() == ''){
            parametro = 0;
        }else{
            parametro = $("#Ruta option:selected").val();
        }
		let win = window.open('printVentasDep/'+$("#fechainicio").val()+"/"+$("#fechafin").val()+"/"+parametro, '_blank');
	}	
});
</script>
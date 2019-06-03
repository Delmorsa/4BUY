<script type="text/javascript">
	$(document).ready(function(){
		$("#Rutas").select2({
			placeholder: "--- Seleccione una ruta ---",
			allowClear: true
		});
		$("#datatable,#datatablePend,#datatableLiq").DataTable();
		$("#datatableLAnul").DataTable();
	});

	$("#rutasFilter").on("change", function () {
		let table = $("#datatableLiq").DataTable();
		table.columns(2).search(this.value).draw();
	});

$("#newperidobtn").click(function () {
	$("#tituloModal").html("Nuevo periodo de liquidacion");
	$("#idperiod").val("");
	$("#Rutas option:selected").val("").trigger('change.select2');
	$("#Rutas option:selected").text("").trigger('change.select2');
	$("#fechaIn").val("");
	$("#fechaFin").val("");
	$("#HoraIn").val("").timepicker();
	$("#HoraFin").val("").timepicker();
	$("#btnActualizar").hide();
	$("#btnGuardar").show();
	$("#Rutas").attr("disabled",false).trigger('change.select2');
	$("#rowEnEspera").hide();
	$("#pLiquidacion").modal("show");
});

$("#btnGuardar").click(function () {
	swal({
		text: "¿Estas seguro que deseas crear un nuevo periodo de liquidacion?"+
			" Este proceso inactivara el perido en uso y no se podra revertir",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: "Cancelar",
		allowOutsideClick: false
	}).then(result => {
		$("#pLiquidacion").modal("hide");
		$("#loading").show();
		if(result.value){
			let sms = '', tipo = '';
			let form_data = {
				Rutas: $("#Rutas option:selected").val(),
				fechaIn: $("#fechaIn").val(),
				fechaFin: $("#fechaFin").val(),
				HoraIn: $("#HoraIn").val(),
				HoraFin: $("#HoraFin").val()
			};

			$.ajax({
				url: "guardarPeriodo",
				type: "POST",
				data: form_data,
				beforeSend: function(){
					if($("#Rutas").val() == "" || $("#fechaIn").val() == "" || $("#fechaFin").val() == "" ||
						$("#HoraIn").val() == "" || $("#HoraFin").val() == ""){
						swal({
							text: "todos los campos son requeridos",
							type: "error",
							allowOutsideClick: false
						});
						$("#loading").hide();
						$.ajax.abort();
					}
					if($("#fechaIn").val() > $("#fechaFin").val()){
						swal({
							text: "La fecha de inicio no puede ser mayor a la fecha de finalizacion",
							type: "error",
							allowOutsideClick: false
						});
						$("#loading").hide();
						$.ajax.abort();
					}
				},
				success: function(data){
					$("#loading").hide();
					if(data){
						let obj = jQuery.parseJSON(data);
						$.each(obj, function (key, value) {
							sms = value["mensaje"];
							tipo = value["tipo"];
						});
						swal({
							type: tipo,
							text: sms,
							allowOutsideClick: false
						}).then(result => {
							location.reload();
						});
					}
				}
			});
		}
	});
});


$("#btnActualizar").click(function () {
	swal({
		text: "¿Estas seguro que deseas actualizar este periodo de liquidacion?. Este proceso podría afectar las facturas pendiente",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: "Cancelar",
		allowOutsideClick: false
	}).then(result => {
		if(result.value){
			$("#pLiquidacion").modal("hide");
			$("#loading").show();
			let sms = '', tipo = '', en_esp = '';
			if($("#EnEspera").prop("checked") == true){
				en_esp = "P";
			}else{
				en_esp = "Y";
			}

			let form_data = {
				idperiod: $("#idperiod").val(),
				Rutas: $("#Rutas option:selected").val(),
				fechaIn: $("#fechaIn").val(),
				fechaFin: $("#fechaFin").val(),
				HoraIn: $("#HoraIn").val(),
				HoraFin: $("#HoraFin").val(),
				Activo: en_esp
			};

			$.ajax({
				url: "actualizarPeriodo",
				type: "POST",
				data: form_data,
				beforeSend: function(){
					if($("#Rutas").val() == "" || $("#fechaIn").val() == "" || $("#fechaFin").val() == ""){
						swal({
							text: "todos los campos son requeridos",
							type: "error",
							allowOutsideClick: false
						});
						$("#loading").hide();
						$.ajax.abort();
					}
				},
				success: function(data){
					if(data){
						$("#loading").hide();
						let obj = jQuery.parseJSON(data);
						$.each(obj, function (key, value) {
							sms = value["mensaje"];
							tipo = value["tipo"];
						});
						swal({
							type: tipo,
							text: sms,
							allowOutsideClick: false
						}).then(result => {
							location.reload();
						});
					}
				}
			});
		}
	});
});

function editar(id,ruta,fechain,fechafin,horain,horafin,enEsp){
	$("#tituloModal").html("Actualizar periodo de liquidacion");
	$("#idperiod").val(id);
	$("#Rutas option:selected").val(ruta).trigger('change.select2');
	$("#Rutas option:selected").text("Vendedor RUTA "+ruta).trigger('change.select2');
	$("#fechaIn").val(fechain);
	$("#fechaFin").val(fechafin);
	$("#HoraIn").val(horain);
	$("#HoraFin").val(horafin);
	$("#btnActualizar").show();
	$("#btnGuardar").hide();
	$("#Rutas").attr("disabled",true).trigger('change.select2');
	$("#rowEnEspera").show();
	if(enEsp == "P"){
		$("#EnEspera").prop("checked",true);
	}else{
		$("#EnEspera").prop("checked",false);
	}
	$("#pLiquidacion").modal("show");
}

function AnularPeriodo(idperiodo) {
	swal({
		text: "¿Estas seguro que deseas anular este periodo de liquidacion?, Este proceso no se podar revertir",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: "Cancelar",
		allowOutsideClick: false
	}).then(result => {
		if(result.value){
			let sms = '', tipo = '';
			$.ajax({
				url: "AnularPeriodo/"+idperiodo,
				type: "POST",
				success: function (data) {
					let obj = jQuery.parseJSON(data);
					$.each(obj, function (index, value) {
						sms = value["mensaje"];
						tipo = value["tipo"];
					});

					swal({
						type: tipo,
						text: sms,
						allowOutsideClick: false
					}).then(result => {
						location.reload();
					});
				}
			});
		}
	});
}
</script>

<script type="text/javascript">

	
	let idCanalGlobal;
	let estadoGlobal;
	$(document).ready(function(){		
		//alert("asdsa");
		$("#Rutas").select2({
			placeholder: "--- Seleccione una ruta ---",
			allowClear: true
		});

		$("#selectVendedores").select2({
			placeholder: 'Seleccione un empleado (opcional)',
			allowClear: true,
			ajax: {
				url: '<?php echo base_url("index.php/traerEmpleadoPeriodo")?>',
				dataType: 'json',
				type: "POST",
				quietMillis: 100,
				data: function (term) {
					return {
						q: term,
						tipo: $('#selectTipo').val()
					};
				},
				results: function (data) {
					$("#campo").empty();
					let res = [];
					for(let i  = 0 ; i < data.length; i++) {
						res.push({id:data[i].Id, text:data[i].Nombre});						
					}
					return {
						results: res
					}
				},
				cache: true
			}
		}).trigger('change.select2');
	});

	$('#cancelarNuevo').click(function(){
		$('#inputNuevoCanal').val("");
		$("#modalNuevo").modal("hide");
	});

	$('#cancelarEditar').click(function(){
		$('#inputEditarCanal').val("");
		$("#modalEditar").modal("hide");
	});



	function editar(idCanal,nombre) {
		$('#inputEditarCanal').val(nombre);
		idCanalGlobal = idCanal;
		$("#modalEditar").modal("show");
	}

	function darBaja(idCanal,nombre,estado) {
		idCanalGlobal = idCanal;
		estadoGlobal = estado;
			$('#mensajeBaja').text("¿Esta segur@ que desea dar de alta este canal? ("+nombre+")");
		if (estado) {
			$('#mensajeBaja').text("¿Esta segur@ que desea dar de baja este canal? ("+nombre+")");
		}
		//$('#inputEditarCanal').val(nombre);
		$("#modalBaja").modal("show");
	}

	$('#guardarEditar').click(function(){
		let bandera = true;
		let nombre = $("#inputEditarCanal").val()
		if (nombre.length <4) {
			swal({
				text: "La descripción debe tener al menos 4 caracteres",
				type: "error",
				allowOutsideClick: false
			});
			bandera = false;
		}

		
		let form_data = {			
			nombre: nombre,
			IdCanal: idCanalGlobal
		};
		if (bandera) {
			$.ajax({
				url: "<?php echo base_url("index.php/EditarCanal")?>",
				type: "POST",
				data: form_data,
				beforeSend: function (){					
				},
				success: function(data){
					let obj = jQuery.parseJSON(data);
					$.each(obj, function (index, value) {
						sms = value["mensaje"];
						tipo = value["tipo"];
					});

					$("#loading").modal("hide");
					swal({
						type: tipo,
						text: sms,
						allowOutsideClick: false
					}).then(result => {
						location.reload();
					});
				},
				error: function(){
					swal({
						type: "error",
						text: "Ocurrio un error inesperado al intentar guardar," +
						" Contáctece con el administrador"
					});
				}
			});
		}
	});


	$('#guardarNuevo').click(function(){
		let bandera = true;		

		if ($('#desde').val() == '' || $('#hasta').val() == '') {
			swal({
				text: "Las fechas no pueden estar vacias",
				type: "error",
				allowOutsideClick: false
			});
			bandera = false;
		}
		if(new Date($('#hasta').val()) <= new Date($('#desde').val()))
		{
			swal({
				text: "La fecha inicial debe ser menor a la fecha final",
				type: "error",
				allowOutsideClick: false
			});
			bandera = false;
		}
		
		var vendedor = $('#selectVendedores').val();
		if ($('#selectVendedores').val() == "") {
			vendedor = "null";
		}

		let form_data = {
			desde: $('#desde').val(),
			hasta: $('#hasta').val(),
			vendedor: vendedor,
			estado: $('#selectEstado').val(),
			tipo: $('#selectTipo').val()
		};
		if (bandera) {
			let band = '';
			$.ajax({
				url: "<?php echo base_url("index.php/GuardarEncabezadoPeriodo")?>",
				type: "POST",
				data: form_data,
				beforeSend: function (){					
				},
				success: function(data){
					let obj = jQuery.parseJSON(data);
					$.each(obj, function (index, value) {
						sms = value["mensaje"];
						tipo = value["tipo"];
						band = value["tipo"];
					});

					$("#loading").modal("hide");
					swal({
						type: tipo,
						text: sms,
						allowOutsideClick: false
					}).then(result => {
						
						if (band == 'success') {
							location.reload();
						}
					});
				},
				error: function(){
					swal({
						type: "error",
						text: "Ocurrio un error inesperado al guardar," +
						" Contáctece con el administrador"
					});
				}
			});
		}
	});

	$('#guardarBaja').click(function(){
		let bandera = true;
				
		let form_data = {			
			//nombre: nombre,
			IdCanal: idCanalGlobal,
			estado: estadoGlobal
		};
		if (bandera) {
			$.ajax({
				url: "<?php echo base_url("index.php/GuardarBajaCanal")?>",
				type: "POST",
				data: form_data,
				beforeSend: function (){					
				},
				success: function(data){
					let obj = jQuery.parseJSON(data);
					$.each(obj, function (index, value) {
						sms = value["mensaje"];
						tipo = value["tipo"];
					});

					$("#loading").modal("hide");
					swal({
						type: tipo,
						text: sms,
						allowOutsideClick: false
					}).then(result => {
						location.reload();
					});
				},
				error: function(){
					swal({
						type: "error",
						text: "Ocurrio un error inesperado al guardar," +
						" Contáctece con el administrador"
					});
				}
			});
		}
	});


	function ActualizarEstado(id, estado) {
	if (estado == 1) {
		var titulo = "Dar de baja";
		var mensaje = "Estas seguro que deseas dar de baja a este periodo?";
	} else {
		var titulo = "Restaurar";
		var mensaje = "Estas seguro que deseas restaurar este periodo?";
	}
	swal({
		title: titulo,
		text: mensaje,
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Aceptar',
		cancelButtonText: "Cancelar",
    	allowOutsideClick: false
	}).then(result => {
		if (result.value) {
			$.ajax({
				url: "ActualizarEstadoPeriodo/" + id + "/" + estado,
				type: "POST",
        		async: true,
				success: function (JSON) {
					let obj = jQuery.parseJSON(JSON);
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
				},
				error: function () {
					swal({
						text: 'No se pudo completar la operación, si el problema persiste contáctece con el administrador.',
						type: "error",
            			allowOutsideClick: false
					});
				}
			});
		}
	});


}

	$('#guardarCopia').click(function(){
		//$("#loading").modal("show");
		let form_data = {
			tipo: $('#selectCopiarTipo').val(),
			mesOrigen: $('#mesOrigen').val(),
			anioOrigen: $('#anioOrigen').val(),
			mesDestino: $('#mesDestino').val(),
			anioDestino: $('#anioDestino').val()
		};
		//if (bandera) {
			let band = '';
			$.ajax({
				url: "<?php echo base_url("index.php/copiarPeriodos")?>",
				type: "POST",
				data: form_data,
				beforeSend: function (){					
				},
				success: function(data){
					let obj = jQuery.parseJSON(data);
					$.each(obj, function (index, value) {
						sms = value["mensaje"];
						tipo = value["tipo"];
						band = value["tipo"];
					});

					$("#loading").modal("hide");
					swal({
						type: tipo,
						text: sms,
						allowOutsideClick: false
					}).then(result => {
						
						if (band == 'success') {
							location.reload();
						}
					});
				},
				error: function(){
					swal({
						type: "error",
						text: "Ocurrio un error inesperado al guardar," +
						" Contáctece con el administrador"
					});
				}
			});
		//}
	});


</script>
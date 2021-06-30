<script type="text/javascript">
	let idCanalGlobal;
	let idPeriodoGlobal;
	let estadoGlobal;

	let recargar = false;
	$(document).ready(function(){
		$('[contenteditable="true"]').keypress(function(e) {
		    var x = event.charCode || event.keyCode;
		    if (isNaN(String.fromCharCode(e.which)) && x!=46 || x===32 || x===13 || (x===46 && event.currentTarget.innerText.includes('.'))) e.preventDefault();
		});
		//alert("asdsa");
		//$('#Modaladvertencia').modal('show');
		$("#Rutas").select2({
			placeholder: "--- Seleccione una ruta ---",
			allowClear: true
		});		
		//$("#datatable").DataTable();
		$("#thisid").select2({
			placeholder: '--- Desde ---',
			allowClear: true,
			ajax: {
				url: '<?php echo base_url("index.php/ProductosList")?>',
				dataType: 'json',
				type: "POST",
				quietMillis: 100,
				data: function (term) {
					return {
						q: term
					};
				},
				results: function (data) {
					$("#campo").empty();
					let res = [];
					for(let i  = 0 ; i < data.length; i++) {
						res.push({id:data[i].ItemCode, text:'('+data[i].ItemCode+') '+data[i].ItemName});
						$("#campo").append('<input type="hidden" name="" id="'+data[i].ItemCode+'txtpeso" class="form-control" value="'+data[i].ItemCode+'">');
					}
					return {
						results: res
					}
				},
				cache: true
			}
		}
		).trigger('change.select2');
	});

	$( "#selectTipo" ).change(function() {

		let form_data = {
			idPeriodo: idPeriodoGlobal,
			idImpulsadora: idUsuarioGlobal,
			idCategoria: $( "#selectTipo" ).val()
		};

		$.ajax({
			url: "<?php echo base_url("index.php/traerComisionImpulsadora")?>",
			type: "POST",
			data: form_data,
			beforeSend: function (){					
			},
			success: function(data){
				let obj = jQuery.parseJSON(data);
				$.each(obj, function (index, value) {
					console.log(value["mensaje"]);
					$('#valorComision').val(value["valor"]);
					return;
				});
			},
			error: function(){
				swal({
					type: "error",
					text: "Ocurrio un error inesperado al intentar traer la comisión," +
					" Contáctece con el administrador"
				});
			}
		});
	});

	$('#btnGuardar').click(function(){	//guardar array de comisiones
		
		$("#loading").modal("show");
		var detalle = new Array();
		let i = 0,contador = 0,bandera = true;
		//var table = document.getElementById ("datatable");
		$('#datatable tr').each(function(){				
			$(this).find('td[contenteditable]').each(function(){ 
				if (parseFloat(this.textContent) > 0) {
					detalle[i] = [];
					detalle[i][0] = this.id;
	                detalle[i][1] = this.textContent;
	                i++;	
	                contador++;
				}								
			});
		});
		//console.log(detalle);
		let form_data = {
			idPeriodo: $('#IdPeriodo').val(),
			detalle: detalle
		};
		if (contador>0) {

			$.ajax({
				url: "<?php echo base_url("index.php/GuardarEdicionPeriodo")?>",
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
					$("#loading").modal("hide");
				}
			});

		}else{
			$("#loading").modal("hide");
			swal({
				type: "error",
				text: "No ha ingresado ninguna regla para pago de comisiones"
			});	
		}
		
	});

	$('#cancelarNuevo').click(function(){
		$('#inputNuevoCanal').val("");
		$("#modalNuevo").modal("hide");
	});

	$('#cancelarEditar').click(function(){
		$('#inputEditarCanal').val("");
		$("#modalEditar").modal("hide");
	});


	$('#modalEditar').on('hidden.bs.modal', function (e) {
		if (recargar) {location.reload();}	  
	});

	function editar(idUsuario,valor,nombre,idperiodo) {				
		idPeriodoGlobal = idperiodo;
		idUsuarioGlobal = idUsuario;
		$("#valorComision").val(valor);		
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
		//let nombre = $("#inputEditarCanal").val()
		let valorComision = $("#valorComision").val();
		if (isNaN(valorComision)) {
			swal({
				text: "El valor no es un número",
				type: "error",
				allowOutsideClick: false
			});
			bandera = false;
		}
		
		if (valorComision == 0 || valorComision < 0) {
			swal({
				text: "El valor debe ser mayor a 0",
				type: "error",
				allowOutsideClick: false
			});
			bandera = false;
		}

		let form_data = {
			valorComision: valorComision,
			idPeriodo: idPeriodoGlobal,
			idUsuario: idUsuarioGlobal,
			//idCategoria : $('#selectTipo').val()
		};

		if (bandera) {
			$.ajax({
				url: "<?php echo base_url("index.php/EditarValorJefeImpulsadora")?>",
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
						recargar = true;
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
		let nombre = $("#inputNuevoCanal").val()
		if (nombre.length <4) {
			swal({
				text: "La descripción debe tener al menos 4 caracteres",
				type: "error",
				allowOutsideClick: false
			});
			bandera = false;
		}

		
		let form_data = {
			nombre: nombre
		};
		if (bandera) {
			$.ajax({
				url: "<?php echo base_url("index.php/GuardarCanal")?>",
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



</script>
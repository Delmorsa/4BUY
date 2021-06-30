<script type="text/javascript">
	let idCanalGlobal;
	let estadoGlobal;
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
				url: '<?php echo base_url("index.php/ClientesList")?>',
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
						res.push({id:data[i].Codigo, text:'('+data[i].Codigo+') '+data[i].Nombre+' '+data[i].NombreComercial});
						$("#campo").append('<input type="hidden" name="" id="'+data[i].Codigo+'txtpeso" class="form-control" value="'+data[i].Codigo+'">');
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


	$('#btnAddRuta').click(function(){
		//alert("asdas");return;
		let cliente = $("#thisid").val();
		let bandera = true;
		//var tabla2 = $("#datatableRutasCanales").DataTable();
		var table = $('#datatableRutasCanales').DataTable();

		table.rows().eq(0).each(function(index){
			let row = table.row(index);
			let data = row.data();

			if(cliente == data.Codigo){
				bandera = false;
				swal({
					text: "Cliente ya se encuentra agregada",
					type: "error",
					allowOutsideClick: false
				});
			}
		});

		if (bandera) {
			tabla.row.add({
		        "IdCliente":  $("#thisid").val(),
		        "Nombre":  $("#thisid").select2('data').text,
		        "Opcion":  '<a href="javascript:void(0)" class="btn btn-xs btn-danger rowDelete"><i class="fa fa-trash-o"></i></a>'
    		}).draw(false);
		}

	});

	$('#datatableRutasCanales').on("click", "tr .rowDelete", function(){
		tabla.row($(this).parents('tr')).remove().draw(false);
	});

	
	
	function editarAdelanto(idImpulsadora,nombre,adelanto) {
		$('#inputEditarCanal').val(nombre);
		idCanalGlobal = idImpulsadora;
		$("#modalEditar").modal("show");
		
		$('#inputAdelanto').val(adelanto);
		$('#nameAdelanto').html(nombre);
	}

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
		var table = $('#datatableRutasCanales').DataTable();
		var detalle = new Array();
        let i = 0;
        //var form_data = new FormData();
		//alert( 'Rows '+table.rows().count()+' are selected' );
		table.rows().eq(0).each(function(index){
			let row = table.row(index);
			let data = row.data();
			console.log(data.IdRuta);
			detalle[i] = [];
            detalle[i][0] = data.IdCliente;
            detalle[i][1] = data.Nombre;
            i++;		
		});	
		let form_data = {			
			//nombre: nombre,
			detalle: JSON.stringify(detalle),
			idImpulsadora: idCanalGlobal
		};
			$.ajax({
				url: "<?php echo base_url("index.php/GuardarClientesImpulsadoras")?>",
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
						" Contáctese con el administrador"
					});
				}
			});
		//}
	});

	$('#guardarEditarAdelanto').click(function(){
		let bandera = true;
		var detalle = new Array();
        let i = 0;
        let adelanto = $('#inputAdelanto').val();

        if (adelanto <0) {
        	swal({
        		type: "error",
        		text: "El monto no puede ser menos a 0"
        	});
        	bandera = false;
        }

        if (bandera) {
		let form_data = {
			//nombre: nombre,
			adelanto: $('#inputAdelanto').val(),
			idImpulsadora: idCanalGlobal
		};
			$.ajax({
				url: "<?php echo base_url("index.php/GuardarAdelantoImpulsadoras")?>",
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
						" Contáctese con el administrador"
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
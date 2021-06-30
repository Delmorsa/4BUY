<script type="text/javascript">
	let idCanalGlobal;
	let estadoGlobal;

	let tabla;
	$(document).ready(function(){
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
				url: '<?php echo base_url("index.php/VendedoresCanalaesAjax")?>',
				dataType: 'json',
				type: "POST",
				quietMillis: 100,
				data: function (term) {
					return {
						q: term,
						idCanal: idCanalGlobal
					};
				},
				results: function (data) {
					$("#campo").empty();
					let res = [];
					for(let i  = 0 ; i < data.length; i++) {
						res.push({id:data[i].IdRuta, text:data[i].Nombre});
						$("#campo").append('<input type="hidden" name="" id="'+data[i].IdRuta+'" class="form-control" value="'+data[i].Nombre+'">');
					}
					return {
						results: res
					}
				},
				cache: true
			}
		}).trigger('change.select2');
		
	});

	$('#btnAddRuta').click(function(){
		let vendedor = $("#thisid").val();
		let bandera = true;
		//var tabla2 = $("#datatableRutasCanales").DataTable();
		var table = $('#datatableRutasCanales').DataTable();

		table.rows().eq(0).each(function(index){
			let row = table.row(index);
			let data = row.data();

			if(vendedor == data.IdRuta){
				bandera = false;
				swal({
					text: "Ruta ya se encuentra agregada",
					type: "error",
					allowOutsideClick: false
				});
			}
		});

		if (bandera) {
			tabla.row.add({
		        "IdRuta":  $("#thisid").val(),
		        "Nombre":  $("#thisid").select2('data').text,
		        "Opcion":  '<a href="javascript:void(0)" class="btn btn-xs btn-danger rowDelete"><i class="fa fa-trash-o"></i></a>'
    		}).draw(false);
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


	$('#datatableRutasCanales').on("click", "tr .rowDelete", function(){
		tabla.row($(this).parents('tr')).remove().draw(false);
	});

	function editar(idCanal,nombre) {
		$('#inputEditarCanal').val(nombre);
		idCanalGlobal = idCanal;
		$("#modalEditar").modal("show");

		tabla = $("#datatableRutasCanales").DataTable({
				"ajax": {
					"url": "traerRutasCanal",
					"type": "POST",
					"data":{
						idCanal: idCanalGlobal
					}
				},
				//"processing": true,
				"responsive": false,
				"info": true,
				"sort": true,
				"destroy": true,
				"searching": false,
				"paginate": false,
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
					//"loadingRecords": "",
					//"processing": "Procesando datos  <i class='fa fa-spin fa-refresh'></i>",
					"paginate": {
						"first": "Primera",
						"last": "Última ",
						"next": "Siguiente",
						"previous": "Anterior"
					}
				},
				"columns": [
					{"data" : "IdRuta"},
					{"data" : "Nombre"},
					{"data" : "Opcion","class":"text-center"}
				]				
			});
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
            detalle[i][0] = data.IdRuta;
            detalle[i][1] = data.Nombre;
            i++;		
		});
	
		let form_data = {			
			//nombre: nombre,
			detalle: JSON.stringify(detalle),
			idcanal: idCanalGlobal
		};
			$.ajax({
				url: "<?php echo base_url("index.php/GuardarVendedoresCanal")?>",
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
						" Contáctese con el administrador"
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
						" Contáctese con el administrador"
					});
				}
			});
		}
	});



</script>
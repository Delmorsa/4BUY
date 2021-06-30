<script type="text/javascript">

	//$('#modalCopiar').modal("show")
	let idCanalGlobal;
	let estadoGlobal;
	$(document).ready(function(){		
		//alert("asdsa");
		$("#Rutas").select2({
			placeholder: "--- Seleccione una ruta ---",
			allowClear: true
		});				
	});
	
	$("#selectTipo").select2();
	$("#selectFiltro").select2({
			placeholder: 'Seleccione un trabajador (opcional)',
			allowClear: true,
			ajax: {
				url: '<?php echo base_url("index.php/filtrarTrabajador")?>',
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
						$("#campo").append('<input type="hidden" name="" id="'+data[i].Id+'txtpeso" class="form-control" value="'+data[i].Nombre+'">');
					}
					return {
						results: res
					}
				},
				cache: true
			}
		}
	).trigger('change.select2');


	$('#btnGenerar').click(function(){

		let bandera = true;		

		if ($('#desde').val() == '' || $('#hasta').val() == '') {
			swal({
				type: "error",
				text: "Seleccione un rango de fecha valido"
			});
			bandera = false;
		}
		let form_data = {
			tipo: $('#selectTipo').val(),
			trabajador: $('#selectFiltro').val(),
			desde: $('#desde').val(),
			hasta: $('#hasta').val()
		};

		if (bandera) {
			let table = $("#datatable").DataTable({
						"ajax": {
							"url": "generarReportePago",
							"type": "POST",
							"data": function ( d ) {
								d.tipo = $('#selectTipo').val();
								d.trabajador = $('#selectFiltro').val();
								d.desde = $('#desde').val();
								d.hasta = $('#hasta').val();
							}
						},
						"processing": true,
						"serverSide": true,
						"orderMulti": false,
						"searching": false,
						"paginate": false,
						"info": false,
						"sort": true,
						"destroy": true,
						"responsive": true,						
						"order": [
							[0, "desc"]
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
							{"data" : "Nombre","class": "font-weight-bold"},
							{"data" : "Categoria"},
							{"data" : "Grupo"},
							{"data" : "Libras"},
							{"data" : "Devolucion"},
							{"data" : "TotalLibras","class": "font-weight-bold"},
							{"data" : "Comision"},
							{"data" : "Total","class": "font-weight-bold"}
						]
			});

			//devoluciones

			let table2 = $("#datatableDevoluciones").DataTable({
				"ajax": {
					"url": "generarReportePagoDevoluciones",
					"type": "POST",
					"data": function ( d ) {
						d.tipo = $('#selectTipo').val();
						d.trabajador = $('#selectFiltro').val();
						d.desde = $('#desde').val();
						d.hasta = $('#hasta').val();
					}
				},
				"processing": true,
				"serverSide": true,
				"orderMulti": false,
				"searching": false,
				"paginate": false,
				"info": false,
				"sort": true,
				"destroy": true,
				"responsive": true,						
				"order": [
				[0, "desc"]
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
					{"data" : "Nombre","class": "font-weight-bold"},
					//{"data" : "CommisGrp"},
					{"data" : "GroupName"},
					//{"data" : "CodCategoria"},
					{"data" : "Categoria"},
					{"data" : "Libras","class": "font-weight-bold"}
				]
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

	$("#printRptVentasDep").click(function(){
		
		if($("#desde").val() > $('#hasta').val()){
			swal({
				text: "La fecha de inicio no puede ser mayor a la fecha final",
				type: "error",
				allowOutsideClick: false
			});
		}else if($("#desde").val() == "" || $("#hasta").val() == ""){
			swal({
				text: "Debe ingresar ambas fechas",
				type: "error",
				allowOutsideClick: false
			});
		}else{
	        let parametro = '';
	        if($('#selectFiltro').val() == ''){
	            parametro = "0";
	        }else{
	            parametro = $('#selectFiltro').val();
	        }

	    	if ($('#selectTipo').val() == 1) {
					let win = window.open('printReportePago/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 2) {
					let win = window.open('printReportePagoSupervisores/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 3) {
					let win = window.open('printReportePagoGerenteVentas/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 4) {				
					let win = window.open('printReporteImpulsadorasPago/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 5) {
					let win = window.open('printReporteImpulsadorasEspecial/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 6) {
					let win = window.open('printReporteJefeImpulsadoras/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}

		}
	});

	
	$("#printRptVentasDepConsolidado").click(function(){
		
		if($("#desde").val() > $('#hasta').val()){
			swal({
				text: "La fecha de inicio no puede ser mayor a la fecha final",
				type: "error",
				allowOutsideClick: false
			});
		}else if($("#desde").val() == "" || $("#hasta").val() == ""){
			swal({
				text: "Debe ingresar ambas fechas",
				type: "error",
				allowOutsideClick: false
			});
		}else{
	        let parametro = '';
	        if($('#selectFiltro').val() == ''){
	            parametro = "0";
	        }else{
	            parametro = $('#selectFiltro').val();
	        }

	    	if ($('#selectTipo').val() == 1) {
					let win = window.open('printReportePagoConsolidado/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 2) {
					let win = window.open('printReportePagoSupervisoresConsolidado/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 3) {
					let win = window.open('printReportePagoGerenteVentas/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 4) {				
					let win = window.open('printReporteImpulsadorasPagoConsolidado/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 5) {
					let win = window.open('printReporteImpulsadorasEspecialConsolidado/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
			if ($('#selectTipo').val() == 6) {
					let win = window.open('printReporteJefeImpulsadorasConsolidado/'+$('#selectTipo').val()+"/"+parametro+"/"+$("#desde").val()+"/"+$("#hasta").val(), '_blank');
			}
		}
	});
	
</script>
<?php
/**
 * Created by Cesar.
 * User: Cesar
 * Date: 3/1/2019
 * Time: 11:01
 */
?>
<script type="text/javascript">
	$(document).ready(function () {
		if($("#chkRotador").prop("checked") == true){
			$("#textRotador").show();
		}

		$("#tblRemisionesDet").DataTable({
			"autoWidth": false,
			"info": false,
			"sort": false,
			"destroy":true,
			"paging": false,
			"ordering": true,
			"searching":false,
			"order": [
				[0, "asc"]
			],
			/*"dom": 'T<"clear">lfrtip',
             "tableTools": {
                 "sSwfPath": "< echo base_url(); ?>assets/data/swf/copy_csv_xls_pdf.swf",
             },*/
			"pagingType": "full_numbers",
			"lengthMenu": [
				[10, 20, 100, -1],
				[10, 20, 100, "Todo"]
			],
			"language": {
				"info": "Registro _START_ a _END_ de _TOTAL_ deshueses",
				"infoEmpty": "Registro 0 a 0 de 0 deshueses",
				"zeroRecords": "No se encontro coincidencia",
				"infoFiltered": "(filtrado de _MAX_ registros en total)",
				"emptyTable": "NO HAY DATOS DISPONIBLES",
				"lengthMenu": '_MENU_ ',
				"search": '<i class=" material-icons">search</i>',
				"loadingRecords": "Cargando...",
				"paginate": {
					"first": "Primera",
					"last": "Última ",
					"next": "Siguiente",
					"previous": "Anterior"
				}
			}
		});
		$("#SumCant").text("0.00");
		$("#SumLbs").text("0.00");
		$("#cantidad").numeric();
		$("#ddlRutas").select2({
			allowClear: true
		});
		$("#Rubro").select2({
			allowClear: true
		});
		Sumar();

		if($("#chkRecargo").prop("checked") == true){
			$("#modalComment").modal("show");
		}
	});

	function Sumar(){
		let table = $("#tblRemisionesDet").DataTable();
		let sumCant = 0;
		let sumLbs = 0;

		table.rows().eq(0).each(function(index){
			let row = table.row(index);
			let data = row.data();
			sumCant += Number(data[3]);
			sumLbs += Number(data[4]);
		});

		$("#SumCant").text(sumCant.toFixed(2));
		$("#SumLbs").text(sumLbs.toFixed(2));
	}

	<!--<editor-fold desc="Cargar productos">-->
	$("#thisid").select2(
		{
			placeholder: '--- Seleccione un Producto ---',
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
					var res = [];
					for(var i  = 0 ; i < data.length; i++) {
						res.push({id:data[i].ItemCode, text:data[i].ItemName});
						$("#campo").append('<input type="hidden" name="" id="'+data[i].ItemCode+'txtpeso" class="form-control" value="'+data[i].SWeight1+'">');
					}
					return {
						results: res
					}
				},
				cache: true
			}
		}
	).trigger('change.select2');
	<!--<end editor-fold>-->

	<!--region Cargar vendedor en caja de texto por ruta-->
	$("#ddlRutas").change(function () {
		if ($("#ddlRutas option:selected").val() == ""){
			$("#vendedor").val("");
		} else{
			$.ajax({
				url: "<?php echo base_url("index.php/GetVendedorAjax")?>" + "/" + $("#ddlRutas option:selected").val(),
				type: "POST",
				async: true,
				success: function(data){
					$.each(JSON.parse(data), function (i, item) {
						$("#vendedor").val(item["Nombre"]);
					});
				}
			});
		}
	});
	<!--endregion-->

	$("#thisid").change(function (){
		$("#buttonsRem").hide();
		if($("#ddlRutas option:selected").val() == ""){
			swal({
				type: "info",
				text: "No se pudo cargar el stock porque no ha especificado una ruta"
			});
			$("#loaderButtons").hide();
			$("#buttonsRem").hide();
		}else{
			$("#loaderButtons").show();
			$("#cantidad").focus();
			if($(this).val() != ''){
				$.ajax({
					url: "<?php echo base_url("index.php/GetStockProdAjax")?>"+"/"+$(this).val(),//+"/"+$("#ddlRutas option:selected").val(),
					type: "POST",
					async: true,
					success: function (data) {
						if($("#ddlRutas option:selected").val() != ""){
							$.each(JSON.parse(data), function (i, item) {
								$("#txtstockProd").val(Number(item["EXISTENCIA"]).toFixed(2));
								$("#txtbodegaProd").val(Number(item["CODBODEGA"]));
							});
							$("#loaderButtons").hide();
							$("#buttonsRem").show();
						}
					}
				});
			}
		}
	});

	function notificar(texto,icono,tipo)
   {
	   new PNotify({
		   title: "Notificaciones",
		   text: texto,
		   addclass: ""+tipo+" stack-bottom-left", //notification-primary
		   icon: icono,
		   hide: true,
		   delay: 3000,
		   buttons: {
			   sticker: false
		   }
	   });
   }

	$("#btnAgregar").click(function(){
		let texto = $("#s2id_thisid").children().find("span").text(),
			codigo = $("#thisid").val(),
			peso = $("#"+codigo+"txtpeso").val(),
			cant = $("#cantidad").val(),
			cantlbs = ((cant * peso) / 454),
			stock = $("#txtstockProd").val(),
			bodega = $("#txtbodegaProd").val();
		let bandera = true;

		if (codigo != "" && cant != "") {
			if(Number(cant) > Number(stock)){
				swal({
					html: 'No hay stock suficiente para la operacion solicitada. <br/>' +
						"Cod Producto: <strong>"+codigo+"</strong> bodega: <strong>"+Number(bodega)+"</strong> "+
						"en stock: <strong>"+Number(stock).toFixed(2)+"</strong>",
					type: "error",
					allowOutsideClick: false
				});
			}else{
				let it = 0, datos = new Array();
				let table = $("#tblRemisionesDet").DataTable({
					"autoWidth": false,
					"info": false,
					"sort": false,
					"destroy":true,
					"paging": false,
					"ordering": true,
					"searching":false,
					"order": [
						[0, "asc"]
					],
					/*"dom": 'T<"clear">lfrtip',
                     "tableTools": {
                         "sSwfPath": "< echo base_url(); ?>assets/data/swf/copy_csv_xls_pdf.swf",
                     },*/
					"pagingType": "full_numbers",
					"lengthMenu": [
						[10, 20, 100, -1],
						[10, 20, 100, "Todo"]
					],
					"language": {
						"info": "Registro _START_ a _END_ de _TOTAL_ deshueses",
						"infoEmpty": "Registro 0 a 0 de 0 deshueses",
						"zeroRecords": "No se encontro coincidencia",
						"infoFiltered": "(filtrado de _MAX_ registros en total)",
						"emptyTable": "NO HAY DATOS DISPONIBLES",
						"lengthMenu": '_MENU_ ',
						"search": '<i class=" material-icons">search</i>',
						"loadingRecords": "Cargando...",
						"paginate": {
							"first": "Primera",
							"last": "Última ",
							"next": "Siguiente",
							"previous": "Anterior"
						}
					}
				});


				table.rows().eq(0).each(function(index){
					let row = table.row(index);
					let data = row.data();
					datos[it] = data[0];
					if(data[0] === codigo){
                        bandera = false;
							let sum = $("#tblRemisionesDet tbody tr").find("td:eq(3)").eq(it).html();
							let sumlbs = $("#tblRemisionesDet tbody tr").find("td:eq(4)").eq(it).html();
							let suma = Number(cant) + Number(sum);
							let sumalbs = Number(cantlbs) + Number(sumlbs);
							var oTable = $('#tblRemisionesDet').dataTable();
					         oTable.fnUpdate( [codigo,texto, parseFloat(peso).toFixed(3), suma, sumalbs.toFixed(4)], index ); // Row
							/*$("#tblRemisionesDet tbody tr").find("td:eq(3)").eq(i).html(suma);
							$("#tblRemisionesDet tbody tr").find("td:eq(4)").eq(i).html(sumalbs.toFixed(4));*/
							Sumar();		
							//$("#SumCant").text(suma.toFixed(2));
			                //$("#SumLbs").text(sumalbs.toFixed(2));

							notificar("Se sumo "+cant+" a la cantidad del producto "+codigo+" ",
									"fa fa-check",
									"notification-success");
					}
					it++;
				});

			/*	for (let i = 0; i < datos.length; i++) {
					if(datos[i] === codigo){
						bandera=false;
						let sum = $("#tblRemisionesDet tbody tr").find("td:eq(3)").eq(i).html();
						let suma = Number(cant) + Number(sum);
						$("#tblRemisionesDet tbody tr").find("td:eq(3)").eq(i).html(suma);
						notificar("Se sumo "+cant+" a la cantidad del producto "+codigo+" ",
								"fa fa-check",
								"notification-success")
					}
				}*/

				if (bandera) {
					table.row.add([
						codigo,
						texto,
						parseFloat(peso).toFixed(3),
						cant,
						parseFloat(cantlbs).toFixed(4)])
						.draw(false);
					$("#cantidad").val('');
					$("#btnSaveRem").show();
				}else{
					$("#btnSaveRem").hide();
				}
			}
		}else{
			swal({
				text: "Debe seleccionar un producto y/o ingresar la cantidad",
				type: "error",
				allowOutsideClick: false
			});
		}
		Sumar();
	});

	$("#tblRemisionesDet tbody").on("click", "tr", function(){
		$(this).toggleClass("danger");
		if($(this).hasClass("danger")){
			$("#btnSaveRem").hide();
			$("#buttonsRem").show();
			$("#btnDelete").show();
		}else{
			$("#btnSaveRem").show();
			$("#buttonsRem").hide();
		}
	});

	$("#btnDelete").click(function() {
		let table = $("#tblRemisionesDet").DataTable();
		table.row(".danger").remove().draw(false);
		Sumar();
		$("#btnSaveRem").show();
	});

	$("#chkRecargo").click(function () {
		if($(this).prop("checked") == true){
			$("#modalComment").modal("show");
		}
	});

	$("#btnSaveRem").on("click", function(){
		swal({
			text: "¿Estas seguro que todos los datos están correctos?",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Aceptar',
			cancelButtonText: "Cancelar",
			allowOutsideClick: false
		}).then(result => {
			if(result.value){
				$("#loading").modal("show");
				let tipo = 0,i = 0, array = new Array();
				let table = $("#tblRemisionesDet").DataTable();
				let cant = $("#cantidad").val(),
				stock = $("#txtstockProd").val(),
				bodega = $("#txtbodegaProd").val();
				let coment, rotador;
				if($("#chkOrden").prop("checked")== true){
					tipo = 1;
					coment = "";
				}else if($("#chkPreventa").prop("checked")== true){
					tipo = 2;
					coment = "";
				}else if($("#chkRecargo").prop("checked")== true){
					tipo = 3;
					coment = $("#Comentario").val();
				}else if($("#chkAdelanto").prop("checked")== true){
					tipo = 4;
					if($("#chkTitular").prop("checked") == true){
						rotador = "";
					}
					if($("#chkRotador").prop("checked") == true){
						rotador = $("#RotadorNom").val();
					}

				}else if($("#chkWalmart").prop("checked")== true){
                    tipo = 5;
                    coment = "";
                }else if($("#chkCsaMantica").prop("checked")== true){
                    tipo = 6;
                    coment = "";
                }else if($("#chkIndependiente").prop("checked")== true){
                    tipo = 7;
                    coment = "";
                }

				table.rows().eq(0).each(function(index){
					let row = table.row(index);
					let datos = row.data();
					array[i] = datos[0] +","+ datos[1] +","+ datos[2] +","+ datos[3] +","+ datos[4];
					i++;
				});

				let form_data = {
					datos: array,
					top: [$("#IdRem").val(),$("#ddlRutas option:selected").val(),$("#vendedor").val(),
						$("#Rubro option:selected").val(),$("#fechaEntrega").val(),$("#fechaLiquida").val(),tipo,$("#Ref").val(),
						$("#Consecutivo").val(),0,coment,rotador]
				};

				$.ajax({
					url: "<?php echo base_url("index.php/ActualizarRemision")?>",
					type: "POST",
					data: form_data,
					beforeSend:function(){
						if ($("#ddlRutas option:selected").val() == "" ||
							$("#Rubro option:selected").val() == "" || $("#fechaEntrega").val() == ''
							|| $("#fechaLiquida").val() == ''){
							$("#loading").modal("hide");
							swal({
								type: "error",
								text: "Todos los campos son requeridos!",
								allowOutsideClick: false
							});
							$.ajax.abort();
						}else if($("#Consecutivo").val() == ''){
							$("#loading").modal("hide");
							swal({
								type: "error",
								text: "El número de consecutivo no puede estar vacío",
								allowOutsideClick: false
							});
							$.ajax.abort();
						}
					},
					success: function(data){
						if(data == "FALSE") {
							$("#loading").modal("hide");
							swal({
								text: 'No tienes permiso para realizar esta operación',
								type: 'warning',
								allowOutsideClick: false
							});
						}else{
							$("#loading").modal("hide");
							swal({
								type: "success",
								text: "Datos guardados correctamente",
								allowOutsideClick: false
							}).then(result => {
								location.reload();
							});
						}
					},
					error: function(){
						$("#loading").modal("hide");
						swal({
							type: "error",
							text: "Ocurrió un error inesperado al procesar los datos, si el problema persiste contáctece con el administrador",
							allowOutsideClick: false
						});
					}
				});
			}
		});
	});


	<!--region Mostrar opciones de tipo de vendedor al seleccionar Tipo: Adelanto-->
	$("#chkAdelanto").click(function () {
		if($(this).prop("checked") == true){
			$("#modalVendedor").modal("show");
		}
	});

	$("#chkTitular").click(function () {
		if($(this).prop("checked") == true){
			$("#textRotador").hide();
		}
	});

	$("#chkRotador").click(function () {
		if($(this).prop("checked") == true){
			$("#textRotador").show();
		}
	});
	<!--endregion-->

</script>

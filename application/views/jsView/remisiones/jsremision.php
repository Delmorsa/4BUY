<script type="text/javascript">
$(document).ready(function(){
	CargarConsecutivo();
	setInterval(function () {
		CargarConsecutivo();
	},5000);

	$("#SumCant").text("0.00");
	$("#SumLbs").text("0.00");
	$("#cantidad").numeric();
	$("#ddlRutas").select2({
		allowClear: true,
		placeholder: '--- Seleccione una ruta---'
	});
	$("#Rubro").select2({
		allowClear: true,
		placeholder: '--- Seleccione un rubro---'
	});

	$("#thisid").select2({
		placeholder: '--- Seleccione un Producto ---',
		allowClear: true
	});

});

$("#fileUpload").change(function(e){
	let table = $("#tblRemisiones").DataTable();
	table.destroy();
	if($(this).val() != ""){
	let reader = new FileReader();
	reader.readAsArrayBuffer(e.target.files[0]);
	reader.onload = function(e){
		let data = new Uint8Array(reader.result);
		let wb = XLSX.read(data, {type:'array'});
		let htmlstr = "<td>"+XLSX.write(wb,{sheet:"", type:'binary',bookType:'html'})+"</td>";
		$("#wrapper table tbody")[0].innerHTML += htmlstr;

		let cuerpo = $("#wrapper>table>tbody>tr>td>table>tbody").html();
		$("#wrapper").html("");
		$("#wrapper").append('<table id="tblRemisiones" class="display table table-condensed table-bordered table-responsive table-striped mb-none table-sm"" style="width:100%">' +
                ' <thead>' +
                '  <tr>' +
                '  <th>Codigo</th>' +
                '  <th>Descripción</th>' +
                '  <th>GR</th>' +
								'  <th>Cantidad</th>' +
								'  <th>Cantidad LBS</th>' +
								'  <th>Precio</th>' +
                ' </tr>' +
                '</thead>' +
                ' <tbody>'+cuerpo+
                '</tbody>');
		$("#tblRemisiones").DataTable({
				"autoWidth": false,
				"info": false,
				"sort": false,
				"processing": true,
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
				},
			  "initComplete":	function(settings, json){
			  	let table = $("#tblRemisiones").DataTable();
			  	table.rows().eq(0).each(function(index){
			  		let row = table.row(index);
			  		let data = row.data();
			  		$.ajax({
							method: "POST",
							async: true,
							url: "<?php echo base_url("index.php/GetStockProdAjax")?>"+"/"+Number(data[0])
						}).success(function(response){
							let obj = jQuery.parseJSON(response);
							$.each(obj, function(i,inde){
								let oTable = $('#tblRemisiones').dataTable();
								let gr = (data[3] * inde["GRAMOS"])/454;
								let gramos = inde["GRAMOS"];
								let cantidad = gr.toFixed(2);
								oTable.fnUpdate( [data[0],data[1], parseFloat(gramos).toFixed(0), data[3], cantidad, data[5]],index );
								console.log(gr);
						 });
					});
			  	});
			 }
		});
    }
}else{
		$("#wrapper").html('<table id="tblRemisiones" class="display table table-condensed table-bordered table-responsive table-striped mb-none table-sm"" style="width:100%">' +
                '        <thead>' +
                '            <tr>' +
                '                <th>Codigo</th>' +
                '                <th>Descripción</th>' +
                '                <th>GR</th>' +
								'                <th>Cantidad</th>' +
								'                <th>Cantidad LBS</th>' +
								'                <th>Precio</th>' +
                '            </tr>' +
                '        </thead>' +
                '        <tbody>'+
                '</tbody>');
		$("#tblRemisiones").DataTable({
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
	}
});

function CargarConsecutivo(){
	let cons = "";
	$.ajax({
		url: "UltimoConsecutivo",
		type: "POST",
		success: function (data) {
			let obj = jQuery.parseJSON(data);
			$.each(obj, function (index,value) {
				cons = value["Consecutivo"];
			});
			$("#Consecutivo").val(cons);
		}
	});
}

<!--region SUMA DE CANTIDADES Y LIBRAS-->
function Sumar(){
	let table = $("#tblRemisiones").DataTable();
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
<!--endregion-->

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
	let table= $("#tblRemisiones").DataTable();
	table.destroy();
	let texto = $("#s2id_thisid").children().find("span").text(),
	codigo = $("#thisid").val(),
	peso = $("#"+codigo+"txtpeso").val(),
	cant = $("#cantidad").val(),
	cantlbs = ((cant * peso) / 454),
	stock = $("#txtstockProd").val();
	let bandera = true;

	if (codigo != "" && cant != "") {
		if(Number(cant) > Number(stock)){
			swal({
				html: 'No hay stock suficiente para la operacion solicitada. <br/>' +
				"Cod Producto: <strong>"+codigo+"</strong> "+
				"en stock: <strong>"+Number(stock).toFixed(2)+"</strong>",
				type: "error",
				allowOutsideClick: false
			});
		}else{
			let it = 0, datos = new Array();
			table = $("#tblRemisiones").DataTable({
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
					bandera=false;
					let sum = $("#tblRemisiones tbody tr").find("td:eq(3)").eq(it).html();
					let sumlbs = $("#tblRemisiones tbody tr").find("td:eq(4)").eq(it).html();
					let precio = $("#tblRemisiones tbody tr").find("td:eq(5)").eq(it).html();
					let suma = Number(cant) + Number(sum);
					let sumalbs = Number(cantlbs) + Number(sumlbs);
					let oTable = $('#tblRemisiones').dataTable();
			         oTable.fnUpdate( [codigo,texto, parseFloat(peso).toFixed(0), suma, sumalbs.toFixed(2), precio],index ); // Row
					/*$("#tblRemisiones tbody tr").find("td:eq(3)").eq(i).html(suma);
					$("#tblRemisiones tbody tr").find("td:eq(4)").eq(i).html(sumalbs.toFixed(4));*/
					Sumar();
					//$("#SumCant").text(suma.toFixed(2));
	                //$("#SumLbs").text(sumalbs.toFixed(2));

					notificar("Se sumo "+cant+" a la cantidad del producto "+codigo+" ",
								"fa fa-check",
								"notification-success");
				}
				it++;
			});

			if (bandera) {
				table.row.add([
					codigo,
					texto,
					parseFloat(peso).toFixed(0),
					cant,
					parseFloat(cantlbs).toFixed(2),
					"0"])
					.draw(false);
				$("#cantidad").val('');
				$("#btnSaveRem").show();
				Sumar();
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
});

$("body").on("click", "tr", function(){
	$(this).toggleClass("danger");
});

$("#btnDelete").click(function() {
	let table = $("#tblRemisiones").DataTable();
   table.row(".danger").remove().draw(false);
   Sumar();
   $("#btnSaveRem").show();
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
			let table = $("#tblRemisiones").DataTable();
			let coment = '', rotador = '';
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
				}else{
					rotador = 'NULL';
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
				array[i] = datos[0] +","+ datos[1] +","+ datos[2] +","+ datos[3] +","+ datos[4]+","+ datos[5];
				i++;
			});

			let form_data = {
				datos: array,
				top: [$("#ddlRutas option:selected").val(),$("#vendedor").val(),
					$("#Rubro option:selected").val(),$("#fechaEntrega").val(),$("#fechaLiquida").val(),tipo,$("#Ref").val(),0,coment,rotador]
			};

			console.log(form_data);

			$.ajax({
				url: "SaveRemision",
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
					if(data == false) {
						$("#loading").modal("hide");
						swal({
							text: 'No tienes permiso para realizar esta operación',
							type: 'warning',
							allowOutsideClick: false
						});
					}else{
						//debugger;
						$("#loading").modal("hide");
						let cond = '';
						obj = jQuery.parseJSON(data);
						$.each(obj, function (i, value) {
							cond = value;
						});
						if(cond == "guarda"){
							swal({
								type: "success",
								text: "Datos guardados correctamente",
								allowOutsideClick: false
							}).then(result => {
								location.reload();
							});
						}else{
							swal({
								type: "error",
								text: "Ya existe una remision con consecutivo "+$("#Consecutivo").val()+"",
								allowOutsideClick: false
							});
						}
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

<!--region Cargar vendedor en caja de texto por ruta-->
$("#ddlRutas").change(function () {
	let nombre = '';
	if ($("#ddlRutas option:selected").val() == ""){
		$("#vendedor").val(nombre);
	} else{
		$.ajax({
			url: "GetVendedorAjax/" + $("#ddlRutas option:selected").val(),
			type: "POST",
			async: true,
			success: function(data){
				$.each(JSON.parse(data), function (i, item) {
					nombre = item["Nombre"];
				});
				$("#vendedor").val(nombre);
			}
		});
	}
		$("#fechaEntrega").val('');
		$("#fechaLiquida").val('');
	    $("#chkOrden").prop("checked",true);
		$("#Ref").val('');
		//$("#Consecutivo").val('');
		$("#Rubro").val("0").trigger('change.select2');
	    $("#Comentario").val('');
	    $("#thisid").val([]).trigger('change.select2');
		$("#txtstockProd").val('');
		$("#txtbodegaProd").val('');
	    $("#cantidad").val('');
	    let table = $("#tblRemisiones").DataTable();
	    table.clear().draw();
	    $("#loaderButtons").hide();
		$("#buttonsRem").hide();

	$("#thisid").select2({
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
					let res = [];
					for(let i  = 0 ; i < data.length; i++) {
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

});
<!--endregion-->

<!--region CARGAR STOCK POR PRODUCTO-->
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
				url: "<?php echo base_url("index.php/GetStockProdAjax")?>"+"/"+$(this).val(),
				type: "POST",
				async: true,
				success: function (data) {
					if($("#ddlRutas option:selected").val() != ""){
						$.each(JSON.parse(data), function (i, item) {
							$("#txtstockProd").val(Number(item["EXISTENCIA"]).toFixed(2));
							//$("#txtbodegaProd").val(Number(item["CODBODEGA"]));
						});
						$("#loaderButtons").hide();
						$("#buttonsRem").show();
					}
				}
			});
		}
	}
});
<!--endregion-->

<!--region Mostrar campo de comentarios al seleccionar Tipo: Recargo-->
$("#chkRecargo").click(function () {
	if($(this).prop("checked") == true){
		$("#modalComment").modal("show");
	}
});
<!--endregion-->

<!--region Mostrar opciones de tipo de vendedor al seleccionar Tipo: Adelanto-->
$("#chkAdelanto").click(function () {
	if($(this).prop("checked") == true){
		$("#chkTitular").prop("checked",true);
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

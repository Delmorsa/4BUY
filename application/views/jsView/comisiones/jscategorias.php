<script type="text/javascript">
	$(document).ready(function(){
		//alert("asdsa");
		//$('#Modaladvertencia').modal('show');

		$("#Rutas").select2({
			placeholder: "--- Seleccione una ruta ---",
			allowClear: true
		});
		
		//$("#datatable").DataTable();

	});



		$('#btnFiltrar').click(function(){
			$("#loading").modal("show");
				$.ajax({
					url: '<?php echo base_url("index.php/actualizarCategorias") ?>',
					type: 'POST',
					//data: form_data,
					success: function(data)
					{
						$("#loading").modal("hide");
						let obj = jQuery.parseJSON(data);
						$.each(obj, function(index, val) {
							mensaje = val["mensaje"];
							tipo = val["tipo"]; 
						});
						Swal.fire({
							type: tipo,
							text: mensaje,
							allowOutsideClick: false
						}).then((result)=>{
							location.reload();
						});				
					},error:function(){
						Swal.fire({
							type: "error",
							text: "Error inesperado, Intentelo de Nuevo",
							allowOutsideClick: false
						});
						$("#loading").modal("hide");
					}
				});
		});

	$('#btnGuardar').click(function(){
		var tabla = $('#datatable').DataTable();
		
		var nFilas = $("#datatable tbody tr").length;
		var nColumnas = $("#datatable tr:last td").length;		
		var bandera = true;
				
		//console.log(tabla.data('tr').count());
		if (nColumnas <= 1) {
			swal({
				title: 'Aviso',
				text: 'Se necesita al menos 1 artículo',
				type: 'warning',
				allowOutsideClick: false
			});
			bandera = false;
		}
		if (bandera) {//guardado si es correcto
			//$("#loading").modal("show");
			    let mensaje = '', tipo = '',	
				datos = new Array(), i = 0;
			    mensaje = '', tipo = '',	
				table = $("#datatable").DataTable();
				let CodCategoria = null;
				let Categoria = null;
				if (nFilas == 1) {//guardar la categoria
					console.log($('#datatable tbody tr:first td:nth-child(2)').text());
					if ($('#datatable tbody tr:first td:nth-child(2)').text()!= '') {
						CodCategoria = $('#datatable tbody tr:first td:nth-child(4)').text();
					}
					if ($('#datatable tbody tr:first td:nth-child(3)').text()!= '') {
						Categoria = $('#datatable tbody tr:first td:nth-child(5)').text();
					}
				}

				table.rows().eq(0).each(function(i, index){
					let row = table.row(index);
					let data = row.data();
					//console.log(data)
					datos[i] = [];
					datos[i][0] = data.ItemCode;
					datos[i][1] = data.ItemName;
					datos[i][2] = data.SalUnitMsr;
					datos[i][3] = data.CodCategoria;
					datos[i][4] = data.Categoria;
					datos[i][5] = data.Stock;
					datos[i][6] = data.WhsCode;
					datos[i][7] = data.WhsName;
					datos[i][8] = data.BatchNum;
					datos[i][9] = data.ExpDate;
					datos[i][10] = data.InDate;
					datos[i][11] = data.AvgPrice;
					i++;
				});
				//alert(CodCategoria+' -> '+Categoria )
				//console.log(datos);
				let form_data = {
					codBodega: $("#bodega option:selected" ).val(),
					descBodega: $("#bodega option:selected" ).text(),
					desde: $("#thisid").select2('val'),
					hasta: $("#thisid2").select2('val'),
					CodCategoria: CodCategoria,
					Categoria: Categoria,
				    datos: JSON.stringify(datos)
				};
				console.log(JSON.stringify(datos))

				$.ajax({
					url: '<?php echo base_url("index.php/guardarCongelacion") ?>',
					type: 'POST',
					data: form_data,
					success: function(data)
					{
						$("#loading").modal("hide");
						let obj = jQuery.parseJSON(data);
						$.each(obj, function(index, val) {
							mensaje = val["mensaje"];
							tipo = val["tipo"]; 
						});
						Swal.fire({
							type: tipo,
							text: mensaje,
							allowOutsideClick: false
						}).then((result)=>{
							location.reload();
						});				
					},error:function(){
						Swal.fire({
							type: "error",
							text: "Error inesperado, Intentelo de Nuevo",
							allowOutsideClick: false
						});
						$("#loading").modal("hide");
					}
				});
		}
		
	});


</script>

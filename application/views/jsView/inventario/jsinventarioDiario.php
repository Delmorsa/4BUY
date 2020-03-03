<script type="text/javascript">
  $(document).ready(function() {
    $("#ddlCategorias").select2({
  		allowClear: true,
  		placeholder: '--- Tipo ---'
  	});
  });

  $("#fileUpload").change(function(e){
    $("#btnSaveInv").hide();
    let table = $("#tblInventario").DataTable();
    table.destroy();
    if($(this).val() != ""){

      let reader = new FileReader();
      reader.readAsArrayBuffer(e.target.files[0]);
      reader.onload = function(e){

        let data = new Uint8Array(reader.result);
        let wb = XLSX.read(data, {type: 'array'});
        let htmlstr = "<td>"+XLSX.write(wb, {sheet:"", type:"binary", bookType:"html"})+"</td>";
        $("#wrapper table tbody")[0].innerHTML += htmlstr;

        let cuerpo = $("#wrapper>table>tbody>tr>td>table>tbody").html();
		    $("#wrapper").html("");

        $("#wrapper").append('<table id="tblInventario" class="display table table-condensed table-bordered table-responsive table-striped mb-none table-sm"" style="width:100%">' +
                        ' <thead>' +
                        '  <tr>' +
                        '  <th>CODIGO</th>' +
                        '  <th>DESCRIPCION</th>' +
                        '  <th>GRM</th>' +
        								'  <th>UND/LBRS</th>' +
        								'  <th>LIBRAS</th>' +
                        '  <th>CATEGORIA</th>' +
                        ' </tr>' +
                        '</thead>' +
                        ' <tbody>'+cuerpo+
                        '</tbody>');
        $("#tblInventario").DataTable({
      				"autoWidth": false,
      				"info": false,
      				"sort": false,
      				"processing": true,
      				"destroy":true,
      				"paging": false,
      				"ordering": false,
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
                let contador = 0;
      			  	let tablA = $("#tblInventario").DataTable();
      			  	tablA.rows().eq(0).each(function(index){
      			  		let row = tablA.row(index);
      			  		let data = row.data();
      			  		$.ajax({
      							method: "POST",
      							async: true,
      							url: "<?php echo base_url("index.php/getCategoriaById")?>"+"/"+Number(data[0])
      						}).success(function(response){
      							let obj = jQuery.parseJSON(response);
      							$.each(obj, function(i,inde){
      								let oTable = $('#tblInventario').dataTable();
      								let cat = inde["Categoria"];
      								oTable.fnUpdate( [data[0],data[1], data[2], data[3], data[4], cat],index );
                      contador++;
      						 });
                   if(contador == tablA.data().length){
                    $("#btnSaveInv").show();
                   }
      					});
      			   });
      			 }
      		});
      }
    }else{
      $("#wrapper").html('<table id="tblInventario" class="display table table-condensed table-bordered table-responsive table-striped mb-none table-sm"" style="width:100%">' +
                '        <thead>' +
                '        <tr>' +
                '         <th>CODIGO</th>' +
                '         <th>DESCRIPCION</th>' +
                '         <th>GRM</th>' +
                '         <th>UND/LBRS</th>' +
                '         <th>LIBRAS</th>' +
                '         <th>CATEGORIA</th>' +
                '        </tr>' +
                '        </thead>' +
                '        <tbody>'+
                '</tbody>');
      $("#tblInventario").DataTable({
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
              				}
              		});
    }
  });

  $("body").on("click", "tr", function(){
  	$(this).toggleClass("danger");
  });

  $("#btnDelete").click(function() {
  	let table = $("#tblInventario").DataTable({
                    "autoWidth": false,
                    "info": false,
                    "sort": false,
                    "processing": false,
                    "destroy":true,
                    "paging": false,
                    "ordering": false,
                    "searching":false,
                    "lengthMenu": [
                			[-1],
                			["Todo"]
                		],
                    "order": [
                      [0, "asc"]
                    ]});
     table.row(".danger").remove().draw(false);
  });

  $("#btnSaveInv").click(function (){
    let tipo = $("#ddlCategorias option:selected").val(),
    tipoText = $("#ddlCategorias option:selected").text(),
    fecha = $("#fechaInventario").val();
    let table = $("#tblInventario").DataTable({
                    "autoWidth": false,
                    "info": false,
                    "sort": false,
                    "processing": false,
                    "destroy":true,
                    "paging": false,
                    "ordering": false,
                    "searching":false,
                    "order": [
                      [0, "asc"]
                    ]});
    if(table.data().length < 1){
      swal({
        type: "error",
        text: "No hay datos en la tabla",
        allowOutsideClick: false
      });
    }else if(tipoText == "" || fecha == ""){
      swal({
        type: "error",
        text: "Debe seleccionar un tipo y agregar una fecha",
        allowOutsideClick: false
      });
    }else{
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
          let mensaje = '', icon = '', array = new Array(), i = 0;

          table.rows().eq(0).each(function(index){
    				let row = table.row(index);
    				let datos = row.data();
            array[i] = [];
    				array[i][0] = datos[0];
            array[i][1] = datos[1];
            array[i][2] = datos[2];
            array[i][3] = datos[3];
            array[i][4] = datos[4];
            array[i][5] = datos[5];
    				i++;
    			});

          let form_data = {
                  encabezado: [fecha,tipo],
          				datos: JSON.stringify(array)
          			};

            $.ajax({
              url: 'guardarInventario',
              type: 'POST',
              data: form_data,
              success: function(data){
                $("#loading").modal("hide");

                let obj = jQuery.parseJSON(data);
                $.each(obj, function(index, el) {
                  mensaje = el["mensaje"];
                  icon = el["tipo"];
                });

                swal({
                  text: mensaje,
                  type: icon,
                  allowOutsideClick: false
                }).then(result => {
                  location.reload();
                });
              }
            });
        }

      });
    }
  });
</script>

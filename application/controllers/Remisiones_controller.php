<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remisiones_controller extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Remisiones_model');
		$this->load->model("Vendedores_model");
		$this->load->library('session');
		
		if ($this->session->userdata("logged") != 1) {
            redirect(base_url() . 'index.php', 'refresh');
        }   
       /* if ($this->session->userdata("IdRol") != 1) {
            redirect('Error_403','refresh');
        }*/
	}

	public function index(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1007");
		if($permiso){
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('remisiones/inventario');
			$this->load->view('footer/footer');
			$this->load->view('jsView/remisiones/jsinventario');
		}else{
			redirect('Error_403','refresh');
		}

	}

	public function inventario(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

	   $result = $this->Hana_model->inventario($start,$length,$search);
	   $resultado = $result["datos"];
	   $totalDatos = $result["numDataTotal"];

	   $datos = array();
	   foreach ($resultado as $key) {
	   		$array = array();
	   		$array["ItemCode"] = $key["ItemCode"];
            $array["ItemName"] = utf8_encode($key["ItemName"]);
            $array["SalUnitMsr"] = utf8_encode($key["SalUnitMsr"]);
            $array["WhsCode"] = utf8_encode($key["WhsCode"]);
            $array["WhsName"] = utf8_encode($key["WhsName"]);
            $array["OnHand"] = $key["OnHand"];
            $datos[] = $array;
	   	}

	   	$totalDatosObtenidos = count($result["datos"]);

	   	$json_data = array(
	   		"draw" => intval($this->input->get_post('draw')),
	   		"recordsTotal" => intval($totalDatosObtenidos),
	   		"recordsFiltered" => intval($totalDatos),
	   		"data" => $datos
	   	);

	   	echo json_encode($json_data);

	}

	public function inventarioSinStock(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

	   $result = $this->Hana_model->inventarioSinStock($start,$length,$search);
	   $resultado = $result["datos"];
	   $totalDatos = $result["numDataTotal"];

	   $datos = array();
	   foreach ($resultado as $key) {
	   		$array = array();
	   		$array["ItemCode"] = $key["ItemCode"];
            $array["ItemName"] = utf8_encode($key["ItemName"]);
            $array["SalUnitMsr"] = utf8_encode($key["SalUnitMsr"]);
            $array["WhsCode"] = utf8_encode($key["WhsCode"]);
            $array["WhsName"] = utf8_encode($key["WhsName"]);
            $array["OnHand"] = $key["OnHand"];
            $datos[] = $array;
	   	}

	   	$totalDatosObtenidos = count($result["datos"]);

	   	$json_data = array(
	   		"draw" => intval($this->input->get_post('draw')),
	   		"recordsTotal" => intval($totalDatosObtenidos),
	   		"recordsFiltered" => intval($totalDatos),
	   		"data" => $datos
	   	);

	   	echo json_encode($json_data);
	}

	public function inventarioRutas(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$result = $this->Hana_model->inventarioRutas($start,$length,$search);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		foreach ($resultado as $key) {
			$array = array();
			$array["ItemCode"] = $key["ItemCode"];
            $array["ItemName"] = utf8_encode($key["ItemName"]);
            $array["SalUnitMsr"] = utf8_encode($key["SalUnitMsr"]);
            $array["WhsCode"] = utf8_encode($key["WhsCode"]);
            $array["WhsName"] = utf8_encode($key["WhsName"]);
            $array["OnHand"] = $key["OnHand"];
            $array["SlpCode"] = $key["SlpCode"];
            $array["SlpName"] = $key["SlpName"];
            $datos[] = $array;
		}

		$totalDatosObtenidos = count($result["datos"]);
		$json_data = array(
	   		"draw" => intval($this->input->get_post('draw')),
	   		"recordsTotal" => intval($totalDatosObtenidos),
	   		"recordsFiltered" => intval($totalDatos),
	   		"data" => $datos
	   	);
		echo json_encode($json_data);
	}

	public function inventarioRutasSinStock(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$result = $this->Hana_model->inventarioRutasSinStock($start,$length,$search);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		foreach ($resultado as $key) {
			$array = array();
			$array["ItemCode"] = $key["ItemCode"];
            $array["ItemName"] = utf8_encode($key["ItemName"]);
            $array["SalUnitMsr"] = utf8_encode($key["SalUnitMsr"]);
            $array["WhsCode"] = utf8_encode($key["WhsCode"]);
            $array["WhsName"] = utf8_encode($key["WhsName"]);
            $array["OnHand"] = $key["OnHand"];
            $array["SlpCode"] = $key["SlpCode"];
            $array["SlpName"] = $key["SlpName"];
            $datos[] = $array;
		}

		$totalDatosObtenidos = count($result["datos"]);
		$json_data = array(
	   		"draw" => intval($this->input->get_post('draw')),
	   		"recordsTotal" => intval($totalDatosObtenidos),
	   		"recordsFiltered" => intval($totalDatos),
	   		"data" => $datos
	   	);
		echo json_encode($json_data);
	}

	//bloque de codigo para remisiones
	public function ultimoConsecutivo(){
		$this->Remisiones_model->ultimoConsecutivo();
	}

	public function remisiones(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1008");
		if($permiso){
			$data["permiso"] =  $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1039");
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('remisiones/remisiones',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/remisiones/jsremision');
		}else{
			redirect('Error_403','refresh');
		}
	}

	//Cargar productos
	public function getProductosRutas(){
		$var = $this->input->post("q"); 
		$this->Hana_model->getProductosRutas($var);
	}

	public function getProductosMermas(){
		$var = $this->input->post("q"); 
		$this->Hana_model->getProductosMermas($var);
	}

	public function guardarRemision(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1009");
		if($permiso){
			$this->Remisiones_model->guardarRemision(
				$this->input->post("top"),
				$this->input->post("datos")
			);
			return true;
		}else{
			return false;
		}

	}

	public function getVendedorAjax($ruta){
		$this->Remisiones_model->getVendedorAjax($ruta);
	}

	//region Cargar Vista Lista de remisiones
	public function listaRemisiones(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1010");
		if($permiso){
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('remisiones/lista_remisiones');
			$this->load->view('footer/footer');
			$this->load->view('jsView/remisiones/jslistaremision');
		}else{
			redirect('Error_403','refresh');
		}

	}
	//endregion

	public function listaOrdenes(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$result = $this->Remisiones_model->listaOrdenes($start,$length,$search);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		foreach ($resultado->result_array() as $key) {
			$array = array();
			$array["IdRemision"] = utf8_encode($key["IdRemision"]);
			$array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
			$array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
			$array["CantTotal"] = utf8_encode($key["CantTotal"]);
			$array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
			$array["FechaLiq"] = $key["FechaLiq"];
			$array["FechaCrea"] = $key["FechaCrea"];
			$array["FechaEdita"] = $key["FechaEdita"];
			$array["FechaBaja"] = $key["FechaBaja"];
			if ($key["Estado"] == 1){
				$array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
			}else if($key["Estado"] == 0){
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
			}else{
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
			}
			$array["CodRubro"] = $key["CodRubro"];
			$array["Rubro"] = $key["Rubro"];
			$array["CodTipo"] = $key["CodTipo"];
			$array["Tipo"] = $key["Tipo"];
			$array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
			$datos[] = $array;
		}

		$totalDatosObtenidos = $resultado->num_rows();
		$json_data = array(
			"draw" => intval($this->input->get_post('draw')),
			"recordsTotal" => intval($totalDatosObtenidos),
			"recordsFiltered" => intval($totalDatos),
			"data" => $datos
		);
		echo json_encode($json_data);
	}

	public function listaPreventas(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$result = $this->Remisiones_model->listaPreventas($start,$length,$search);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		foreach ($resultado->result_array() as $key) {
			$array = array();
			$array["IdRemision"] = utf8_encode($key["IdRemision"]);
			$array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
			$array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
			$array["CantTotal"] = utf8_encode($key["CantTotal"]);
			$array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
			$array["FechaLiq"] = $key["FechaLiq"];
			$array["FechaCrea"] = $key["FechaCrea"];
			$array["FechaEdita"] = $key["FechaEdita"];
			$array["FechaBaja"] = $key["FechaBaja"];
			if ($key["Estado"] == 1){
				$array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
			}else if($key["Estado"] == 0){
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
			}else{
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
			}
			$array["CodRubro"] = $key["CodRubro"];
			$array["Rubro"] = $key["Rubro"];
			$array["CodTipo"] = $key["CodTipo"];
			$array["Tipo"] = $key["Tipo"];
			$array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
			$datos[] = $array;
		}

		$totalDatosObtenidos = $resultado->num_rows();
		$json_data = array(
			"draw" => intval($this->input->get_post('draw')),
			"recordsTotal" => intval($totalDatosObtenidos),
			"recordsFiltered" => intval($totalDatos),
			"data" => $datos
		);
		echo json_encode($json_data);
	}

	public function listaRecargos(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$result = $this->Remisiones_model->listaRecargos($start,$length,$search);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		foreach ($resultado->result_array() as $key) {
			$array = array();
			$array["IdRemision"] = utf8_encode($key["IdRemision"]);
			$array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
			$array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
			$array["CantTotal"] = utf8_encode($key["CantTotal"]);
			$array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
			$array["FechaLiq"] = $key["FechaLiq"];
			$array["FechaCrea"] = $key["FechaCrea"];
			$array["FechaEdita"] = $key["FechaEdita"];
			$array["FechaBaja"] = $key["FechaBaja"];
			if ($key["Estado"] == 1){
				$array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
			}else if($key["Estado"] == 0){
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
			}else{
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
			}
			$array["CodRubro"] = $key["CodRubro"];
			$array["Rubro"] = $key["Rubro"];
			$array["CodTipo"] = $key["CodTipo"];
			$array["Tipo"] = $key["Tipo"];
			$array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
			$datos[] = $array;
		}

		$totalDatosObtenidos = $resultado->num_rows();
		$json_data = array(
			"draw" => intval($this->input->get_post('draw')),
			"recordsTotal" => intval($totalDatosObtenidos),
			"recordsFiltered" => intval($totalDatos),
			"data" => $datos
		);
		echo json_encode($json_data);
	}

    public function listaWalmart(){
        $start = $this->input->get_post('start');
        $length = $this->input->get_post('length');
        $search = $this->input->get_post('search')['value'];

        $result = $this->Remisiones_model->listaWalmart($start,$length,$search);
        $resultado = $result["datos"];
        $totalDatos = $result["numDataTotal"];

        $datos = array();
        foreach ($resultado->result_array() as $key) {
            $array = array();
            $array["IdRemision"] = utf8_encode($key["IdRemision"]);
            $array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
            $array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
            $array["CantTotal"] = utf8_encode($key["CantTotal"]);
            $array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
            $array["FechaLiq"] = $key["FechaLiq"];
            $array["FechaCrea"] = $key["FechaCrea"];
            $array["FechaEdita"] = $key["FechaEdita"];
            $array["FechaBaja"] = $key["FechaBaja"];
            if ($key["Estado"] == 1){
                $array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
            }else if($key["Estado"] == 0){
                $array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
            }else{
                $array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
            }
            $array["CodRubro"] = $key["CodRubro"];
            $array["Rubro"] = $key["Rubro"];
            $array["CodTipo"] = $key["CodTipo"];
            $array["Tipo"] = $key["Tipo"];
            $array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
            $datos[] = $array;
        }

        $totalDatosObtenidos = $resultado->num_rows();
        $json_data = array(
            "draw" => intval($this->input->get_post('draw')),
            "recordsTotal" => intval($totalDatosObtenidos),
            "recordsFiltered" => intval($totalDatos),
            "data" => $datos
        );
        echo json_encode($json_data);
    }

    public function listaCasaMant(){
        $start = $this->input->get_post('start');
        $length = $this->input->get_post('length');
        $search = $this->input->get_post('search')['value'];

        $result = $this->Remisiones_model->listaCasaMant($start,$length,$search);
        $resultado = $result["datos"];
        $totalDatos = $result["numDataTotal"];

        $datos = array();
        foreach ($resultado->result_array() as $key) {
            $array = array();
            $array["IdRemision"] = utf8_encode($key["IdRemision"]);
            $array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
            $array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
            $array["CantTotal"] = utf8_encode($key["CantTotal"]);
            $array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
            $array["FechaLiq"] = $key["FechaLiq"];
            $array["FechaCrea"] = $key["FechaCrea"];
            $array["FechaEdita"] = $key["FechaEdita"];
            $array["FechaBaja"] = $key["FechaBaja"];
            if ($key["Estado"] == 1){
                $array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
            }else if($key["Estado"] == 0){
                $array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
            }else{
                $array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
            }
            $array["CodRubro"] = $key["CodRubro"];
            $array["Rubro"] = $key["Rubro"];
            $array["CodTipo"] = $key["CodTipo"];
            $array["Tipo"] = $key["Tipo"];
            $array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
            $datos[] = $array;
        }

        $totalDatosObtenidos = $resultado->num_rows();
        $json_data = array(
            "draw" => intval($this->input->get_post('draw')),
            "recordsTotal" => intval($totalDatosObtenidos),
            "recordsFiltered" => intval($totalDatos),
            "data" => $datos
        );
        echo json_encode($json_data);
    }

    public function listaIndependiente(){
        $start = $this->input->get_post('start');
        $length = $this->input->get_post('length');
        $search = $this->input->get_post('search')['value'];

        $result = $this->Remisiones_model->listaIndependiente($start,$length,$search);
        $resultado = $result["datos"];
        $totalDatos = $result["numDataTotal"];

        $datos = array();
        foreach ($resultado->result_array() as $key) {
            $array = array();
            $array["IdRemision"] = utf8_encode($key["IdRemision"]);
            $array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
            $array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
            $array["CantTotal"] = utf8_encode($key["CantTotal"]);
            $array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
            $array["FechaLiq"] = $key["FechaLiq"];
            $array["FechaCrea"] = $key["FechaCrea"];
            $array["FechaEdita"] = $key["FechaEdita"];
            $array["FechaBaja"] = $key["FechaBaja"];
            if ($key["Estado"] == 1){
                $array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
            }else if($key["Estado"] == 0){
                $array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
            }else{
                $array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
            }
            $array["CodRubro"] = $key["CodRubro"];
            $array["Rubro"] = $key["Rubro"];
            $array["CodTipo"] = $key["CodTipo"];
            $array["Tipo"] = $key["Tipo"];
            $array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
            $datos[] = $array;
        }

        $totalDatosObtenidos = $resultado->num_rows();
        $json_data = array(
            "draw" => intval($this->input->get_post('draw')),
            "recordsTotal" => intval($totalDatosObtenidos),
            "recordsFiltered" => intval($totalDatos),
            "data" => $datos
        );
        echo json_encode($json_data);
    }

	public function listaAdelantos(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$result = $this->Remisiones_model->listaAdelantos($start,$length,$search);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		foreach ($resultado->result_array() as $key) {
			$array = array();
			$array["IdRemision"] = utf8_encode($key["IdRemision"]);
			$array["IdUsuario"] = utf8_encode($key["IdUsuario"]);
			$array["FechaEntrega"] = utf8_encode($key["FechaEntrega"]);
			$array["CantTotal"] = utf8_encode($key["CantTotal"]);
			$array["TotalLbs"] = utf8_encode($key["TotalLbs"]);
			$array["FechaLiq"] = $key["FechaLiq"];
			$array["FechaCrea"] = $key["FechaCrea"];
			$array["FechaEdita"] = $key["FechaEdita"];
			$array["FechaBaja"] = $key["FechaBaja"];
			if ($key["Estado"] == 1){
				$array["Estado"] = '<span class="badge badge-pill bg-primary">Activo</span>';
			}else if($key["Estado"] == 0){
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Inactivo</span>';
			}else{
				$array["Estado"] = '<span class="badge badge-pill bg-danger">Cerrado</span>';
			}
			$array["CodRubro"] = $key["CodRubro"];
			$array["Rubro"] = $key["Rubro"];
			$array["CodTipo"] = $key["CodTipo"];
			$array["Tipo"] = $key["Tipo"];
			$array["Detalles"] = "<p style='text-align:center;' class='expand text-primary'><i id='Rem".$key["IdRemision"]."' class='center fa fa-expand'></i></p>";
			$datos[] = $array;
		}

		$totalDatosObtenidos = $resultado->num_rows();
		$json_data = array(
			"draw" => intval($this->input->get_post('draw')),
			"recordsTotal" => intval($totalDatosObtenidos),
			"recordsFiltered" => intval($totalDatos),
			"data" => $datos
		);
		echo json_encode($json_data);
	}

	public function detallesRemisiones($idRemision){
		$query = $this->Remisiones_model->detallesRemisiones($idRemision);
		$json = array();
		if ($query != false){
			foreach ($query as $item) {
			  $data = array(
				  "IdRemision" => $item["IdRemision"],
				  "IdUsuario" => $item["IdUsuario"],
				  "Nombre" => $item["Nombre"]." ".$item["Apellidos"],
				  "IdRuta" => $item["IdRuta"],
				  "Vendedor" => $item["Vendedor"],
				  "CodRubro" => $item["CodRubro"],
				  "Rubro" => $item["Rubro"],
				  "CodTipo" => $item["CodTipo"],
				  "Tipo" => $item["Tipo"],
				  "Referencia" => $item["Referencia"],
				  "Consecutivo" => $item["Consecutivo"],
				  "Estado" => $item["Estado"],
				  "Comentario" => $item["Comentario"],
				  "FechaEntrega" => $item["FechaEntrega"],
				  "NombreRotador" => $item["NombreRotador"]
			  );
			  $json[] = $data;
			}
			echo json_encode($json);
		}else{
			echo "false";
		}
	}

	public function editarRemision($idRemision){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1011");
		if($permiso){
			//$result = $this->Remisiones_model->validarFechaEntrega($idRemision);
			$data["enc"] = $this->Remisiones_model->editarRemisionEnc($idRemision);
			$data["det"] = $this->Remisiones_model->editarRemisionDet($idRemision);
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			//if($result == false){
				$this->load->view('remisiones/editar_remision',$data);
			/*}else{
				redirect('Fecha_Caduca','refresh');

			}*/
			$this->load->view('footer/footer');
			$this->load->view('jsView/remisiones/jsdetremisiones');
		}else{
			redirect('Error_403','refresh');
		}
	}

	public function actualizarRemision(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1012");
		if($permiso){
			$this->Remisiones_model->actualizarRemision(
				$this->input->post("top"),
				$this->input->post("datos")
			);
			echo "TRUE";
		}else{
			echo "FALSE";
		}

	}

	public function darBajaRemision($idRemision,$estado){
		if($estado == 1){
			$estado = 2;
		}else{
			$estado = 1;
		}
		$this->Remisiones_model->darBajaRemision($idRemision,$estado);
	}

	public function viewPrintRemision($idRemision){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1014");
		if($permiso){
			$data["enc"] = $this->Remisiones_model->printRemisionEnc($idRemision);
			$data["det"] = $this->Remisiones_model->editarRemisionDet($idRemision);
			//$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('remisiones/print_remision',$data);
		}else{
			redirect('Error_403','refresh');
		}
	}

	public function viewPrintRemisionCons($fechaEntrega, $codRubro, $codTipo,$codRuta){
			$data["enc"] = $this->Remisiones_model->printRemisionEncCons($fechaEntrega, $codRubro, $codTipo);
			$data["det"] = $this->Remisiones_model->detallesRemisionesCons($fechaEntrega, $codRubro, $codTipo,$codRuta);
			//$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('remisiones/print_remision_consolidado',$data);
	}

	public function getStockProdAjax($itemcode){
		$this->Hana_model->getStockProdAjax($itemcode);
	}

	public function Traslados()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1029");
		if($permiso){
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('remisiones/Traslados',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/remisiones/jsTraslados');
		}else{
			redirect('Error_403','refresh');
		}
	}

	public function mostrarTraslados($fecha1, $fecha2){
		$this->Hana_model->mostrarTraslados($fecha1, $fecha2);
	}

	public function detalleTraslados($docEntry){
		$this->Hana_model->detalleTraslados($docEntry);
	}

	/*public function guardarRemisionExcel(){
		$objReader = PHPExcel_IOFactory::createReader("Excel2007");
		$this->Hana_model->guardarRemisionExcel(
			$objReader->load($_FILES["fileSelect"]["tmp_name"])
		);
	}*/
}

/* End of file Remisiones_controller.php */
/* Location: ./application/controllers/Remisiones_controller.php */

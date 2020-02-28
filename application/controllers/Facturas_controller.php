<?php
/**
 * Created by Cesar.
 * User: Cesar
 * Date: 7/1/2019
 * Time: 15:51
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturas_controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("Notifcaciones_model");
		$this->load->model("Facturas_model");
		$this->load->library("session");
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}

	public function index()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1015");
		if($permiso){
			$data["rutas"] = $this->Facturas_model->getRutasFacturas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('facturas/facturas',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/facturas/jsfacturas');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	//region MOSTRAR NOTIFICACIONES
	public function mostrarNotificaciones(){
		$json = array();
		$result = $this->Notifcaciones_model->mostrarNotificaciones();
		$numFact = $result["numFacturas"];
		$anular = $result["anularFacturas"];
		foreach ($result as $key) {
			$json[0]["facturas"] = $numFact;
			$json[0]["anulaciones"] = $anular;
			//$i++;
		}
		echo json_encode($json);
	}
	//endregion

	public function mostrarFacturas(){
		$start = $this->input->get_post('start');
		$length = $this->input->get_post('length');
		$search = $this->input->get_post('search')['value'];

		$fecha1 = $this->input->get_post('fecha1');
		$fecha2 = $this->input->get_post('fecha2');
		$ruta = $this->input->get_post('ruta');
		$tipo = $this->input->get_post('tipo');

		$result = $this->Facturas_model->mostrarFacturas($start,$length,$search,$fecha1, $fecha2, $ruta, $tipo);
		$resultado = $result["datos"];
		$totalDatos = $result["numDataTotal"];

		$datos = array();
		$estado = '';
		foreach ($resultado->result_array() as $key) {
			$serie = explode("-",$key["IDFACTURA"]);

			$array = array();
			$array["IDENCABEZADO"] = utf8_encode($key["IDENCABEZADO"]);
			$array["IDFACTURASERIE"] = $serie[0];
			$array["IDFACTURACONSECUTIVO"] = $serie[1];
            $array["CODCONDPAGO"] = $key["CODCONDPAGO"];
			$array["CONDPAGO"] = $key["CONDPAGO"];
            $array["COMENTARIOANULACION"] = $key["COMENTARIOANULACION"];
            $array["FECHA"] = date_format(new DateTime($key["FECHA"]),"Y-m-d H:i:s");
            $array["TIEMPO"] = $key["TIEMPO"];
            $array["CODLISTAPRECIO"] = $key["CODLISTAPRECIO"];
            $array["LISTAPRECIO"] = $key["LISTAPRECIO"];
            $array["CODCLIENTE"] = $key["CODCLIENTE"];
            $array["NOMBRE"] = $key["NOMBRE"];
            $array["NOMBRECOMERCIAL"] = $key["NOMBRECOMERCIAL"];
            $array["RUC"] = $key["RUC"];
            $array["CODVENDEDOR"] = $key["CODVENDEDOR"];
            $array["NOMBREVENDEDOR"] = $key["NOMBREVENDEDOR"];
            switch ($key["ESTADOAPP"]){
				case 1:
					$estado = '<span class="text-warning text-semibold">Sin integrar</span>';
					break;
				case 2:
					$estado = '<span class="text-info text-semibold">Pendiente de Integrar</span>';
					break;
				case 3:
					$estado = '<span class="text-success text-semibold">Integrada</span>';
					break;
				default:
					$estado = '<span class="text-danger text-semibold">Anulada</span>';
					break;
			}
			$array["ESTADOAPP"] = $key["ESTADOAPP"];
            $array["ESTADOAPP1"] = $estado;
            $array["SUBTOTAL"] = number_format($key["SUBTOTAL"],2);
            $array["DESCUENTO"] = number_format($key["DESCUENTO"],2);
            $array["ISC"] = number_format($key["ISC"],2);
            $array["IVA"] = number_format($key["IVA"],2);
			$array["TOTAL"] = number_format($key["TOTAL"],2);
			$array["Detalles"] = "<a id='Fact".$key["IDENCABEZADO"]."' href='DetalleFacturas/".$key["IDENCABEZADO"]."' style='text-align:center !important;'
			class='btn btn-sm btn-link btn-block center'><i class='fa fa-expand left'></i></a>";
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

	public function detalleFacturas($idencabezado){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1016");
		if($permiso){
			$data["detalles"] = $this->Facturas_model->detallesFacturas($idencabezado);
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('facturas/detalle_factura',$data);
 			$this->load->view('footer/footer');
			$this->load->view('jsView/facturas/jsdetalle_factura');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function detalleFacturasPrint($idencabezado){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1017");
		if ($permiso) {
			$data["detalles"] = $this->Facturas_model->detallesFacturas($idencabezado);
			$this->load->view('facturas/detalle_factura_print',$data);
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function anularFactura($refFactura){
		$comentario = $this->input->get_post("comentarioAnula");
		$this->Hana_model->anularFactura($refFactura,$comentario);
	}

	public function listaFactxAnular(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1030");
		if($permiso){
			$data["lista"] = $this->Facturas_model->getListaFactAnul();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('facturas/facturas_anular_lista',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/facturas/jsfacturas_anular_lista');
		}else{
			redirect("Error_403", "refresh");
		}

	}

	public function actualizarItemFactura(){
		$iddetalle = $this->input->get_post("iddetalle");
		$cant = $this->input->get_post("cant");
		$total = $this->input->get_post("total");
		$this->Facturas_model->actualizarItemFactura($iddetalle, $cant, $total);
	}
}

/* End of file Controllername.php */

<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Canales_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
		$this->load->model("Canales_model");
		$this->load->library("session");
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}


	public function index(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){			
			$data["lista"] = $this->Canales_model->getAllCanales();			
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/Canales',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jscanales');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function GuardarCanal()
	{
		
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$this->Canales_model->GuardarCanal($this->input->get_post("nombre"));			
		}else{
			$mensaje[0]["mensaje"] = "no tiene permiso";
		    $mensaje[0]["tipo"] = "error";		    
		    echo json_encode($mensaje);
		    return;
		}
	}

	public function EditarCanal()
	{
		$this->Canales_model->EditarCanal($this->input->get_post("IdCanal"),$this->input->get_post("nombre"));
	}

	public function GuardarBajaCanal()
	{
		$this->Canales_model->GuardarBajaCanal($this->input->get_post("IdCanal"),$this->input->get_post("estado"));
	}

	public function VendedoresCanalaes()
	{
		
		$data["lista"] = $this->Canales_model->getCanales();
		$this->load->view('header/header');
		$this->load->view('header/menu');
		$this->load->view('comisiones/vendedoresCanales',$data);
		$this->load->view('footer/footer');
		$this->load->view('jsView/comisiones/jsvendedorescanales');
	}

	public function VendedoresCanalaesAjax()
	{
		$this->Canales_model->VendedoresCanalaesAjax($this->input->get_post("idCanal"),$this->input->get_post("q"));
	}

	public function traerRutasCanal()
	{
		$this->Canales_model->traerRutasCanal($this->input->get_post("idCanal"));
	}

	public function GuardarVendedoresCanal()
	{
		$this->Canales_model->GuardarVendedoresCanal($this->input->get_post("idcanal"),$this->input->get_post("detalle"));
	}
}

/* End of file Liquidacion_controller.php */
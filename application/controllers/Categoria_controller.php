<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:08
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Liquidacion_model");
		$this->load->model("Categoria_model");
		$this->load->library("session");
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}

	
	public function index(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$data["lista"] = $this->Categoria_model->getCategorias();			
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/Categorias',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jscategorias');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function actualizarCategorias()
	{
		//$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1042");
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$this->Hana_model->actualizarCategorias();
		}else{
			$mensaje[0]["mensaje"] = "no tiene permiso";
		    $mensaje[0]["tipo"] = "error";
		    //$this->db->trans_commit();
		    echo json_encode($mensaje);
		    return;
		}
	}

}
/* End of file Liquidacion_controller.php */
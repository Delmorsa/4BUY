<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario_controller extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('session');
    $this->load->model('Inventario_model');
		if ($this->session->userdata("logged") != 1) {
            redirect(base_url() . 'index.php', 'refresh');
        }
        if ($this->session->userdata("IdRol") != 1) {
            redirect('Error_403','refresh');
        }
  }

  function index()
  {
    $permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1039");
		if($permiso){
      $this->load->view('header/header');
      $this->load->view('header/menu');
      $this->load->view('inventario/inventario');
      $this->load->view('footer/footer');
      $this->load->view('jsView/inventario/jsinventarioDiario');
    }else{
			redirect("Error_403", "refresh");
		}
  }

  public function guardarInventario(){
			$this->Inventario_model->guardarInventario(
				$this->input->post("encabezado"),
				$this->input->post("datos")
			);
	}

  public function getCategoriaById($itemcode)
  {
    $this->Hana_model->getCategoriaById($itemcode);
  }

}

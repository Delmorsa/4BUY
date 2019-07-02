<?php
/**
 * Created by Cesar Mejía.
 * User: Sistemas
 * Date: 22/3/2019 17:01 2019
 * FileName: Cuotas_controller.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuotas_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library("session");
		$this->load->model("Cuotas_model");
		if ($this->session->userdata("logged") != 1) {
			redirect(base_url() . 'index.php', 'refresh');
		}
	}

	public function index()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1033");
		if($permiso){
			$data["cuotas"] = $this->Cuotas_model->getCuotas();
			$data["rutas"] = $this->Hana_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('cuotas/cuotas',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/cuotas/jsCuotas');
		}else{
			redirect("Error_403", "refresh");
		}

	}

	public function CuotaList(){
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1036");
		if($permiso){
			$data["rutas"] = $this->Cuotas_model->getRutas();
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('cuotas/cuotasLista',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/cuotas/jsCuotasLista');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function Exportar($fechaInicio, $fechaFin, $IdRuta,$mes)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1037");
		if($permiso){
			$data["cuotas"] = $this->Cuotas_model->ExportarReporteCuotas($fechaInicio, $fechaFin, str_replace("%20"," ",$IdRuta),$mes);
			$data["rubros"] = $this->Cuotas_model->getRubros($IdRuta);
			$this->load->view('Exportar/Exportar_Cuotas',$data);
			$this->load->view('jsView/cuotas/jsCuotas');
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function guardarCuota(){
		$mes = $this->input->get_post("mes");
		$anio = $this->input->get_post("anio");
		$ruta = $this->input->get_post("ruta");
		$numruta = $this->input->get_post("numruta");
		$cuotamens = $this->input->get_post("cuotamens");
		$dias = $this->input->get_post("dias");
		$this->Cuotas_model->guardarCuota($mes, $anio, $ruta, $numruta, $cuotamens, $dias);
	}

	public function actualizarCuota(){
		$id = $this->input->get_post("id");
		$cuotamens = $this->input->get_post("cuotamens");
		$dias = $this->input->get_post("dias");
		$this->Cuotas_model->actualizarCuota($id, $cuotamens, $dias);
	}

	public function ReporteCuotas(){
		$fechaInicio = $this->input->get_post("fecha1");
		$fechaFin = $this->input->get_post("fecha2");
		$IdRuta = $this->input->get_post("ruta");
		$mes = $this->input->get_post("mes");
		$this->Cuotas_model->ReporteCuotas($fechaInicio, $fechaFin, $IdRuta,$mes);
		//echo $this->db->last_query();
	}
	
	function grafica($fechaInicio, $fechaFin, $IdRuta,$mes)
	{

		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1037");
		if($permiso){

			$data["cuotas"] = $this->Cuotas_model->ExportarReporteCuotas2($fechaInicio, $fechaFin, str_replace("%20"," ",$IdRuta),$mes);
			//echo json_encode($data["cuotas"]);
			$data["rubros"] = $this->Cuotas_model->getRubros($IdRuta);
			//echo json_encode($data["rubros"]);
		}else{
			echo 0;
		}
	}

	public function librasXdia()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1037");
		if($permiso){
		$this->Cuotas_model->librasXdia($this->input->get_post("fecha1"), $this->input->get_post("fecha2"), $this->input->get_post("idruta"));	
		}else{
			echo 0;
		}
	}


}

/* End of file Cuotas_controller.php */

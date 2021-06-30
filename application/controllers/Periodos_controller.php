<?php
class Periodos_controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("Periodos_model");
		$this->load->library('session');
		if ($this->session->userdata("logged") != 1) {
            redirect(base_url() . 'index.php', 'refresh');
        }
	}

	public function index()
	{
		
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$data["vendedores"] = $this->Periodos_model->traerVendedores();
			$data["sup"] = $this->Periodos_model->traerPeriodos();
			$data["meses"] = $this->Periodos_model->traerMeses();
			//echo json_encode($data["meses"]);
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/Periodos',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jsperiodo');
		} else {
			redirect('Error_403','refresh');
		}

	}

	public function EditarPeriodo($id)
	{

		//$permiso = $this->Periodos_model->validarPermiso($this->session->userdata("id"), "1057");
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$data["canales"] = $this->Periodos_model->traerCanales();//SUPERMERCADO, FORANEO, ETC
			//$data["grupos"] = $this->Periodos_model->traerGrupos();//MORTADELA,CHORIZO, ETC
			$data["categorias"] = $this->Periodos_model->traerCategoriasGrupo();//A,B,C Y MORTADELA,CHORIZO, ETC
			$data["encabezadoPeriodo"] = $this->Periodos_model->traerEncabezadoPeriodo($id);//A,B,C
			$data["detallePeriodo"] = $this->Periodos_model->traerDetallePeriodo($id);//A,B,C
			$data["tieneDetalle"] = $this->Periodos_model->tieneDetalle($id);//A,B,C

			//echo json_encode($data["detallePeriodo"]);
			
			//echo "asdsad: ".$data["tieneDetalle"];
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/EditarPeriodo',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jscomision');
		} else {
			redirect('Error_403','refresh');
		}
	}

	public function GuardarEdicionPeriodo()
	{
		$this->Periodos_model->GuardarEdicionPeriodo($this->input->get_post("idPeriodo"),$this->input->get_post("detalle"));
	}

	public function GuardarEncabezadoPeriodo()
	{		
		$this->Periodos_model->GuardarEncabezadoPeriodo($this->input->get_post("desde"),$this->input->get_post("hasta"),$this->input->get_post("vendedor"),$this->input->get_post("estado"),$this->input->get_post("tipo"));

		//echo $this->input->get_post("tipo");
	}

	public function ActualizarEstadoPeriodo($id,$estado)
	{
		$this->Periodos_model->ActualizarEstadoPeriodo($id,$estado);
	}

	public function copiarPeriodos()
	{
		$this->Periodos_model->copiarPeriodos($this->input->post("mesOrigen"),$this->input->post("anioOrigen"),$this->input->post("mesDestino"),$this->input->post("anioDestino"),$this->input->post("tipo"));
	}

	public function pagoComisiones()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if($permiso){
			$data["canales"] = $this->Periodos_model->traerCanales();//SUPERMERCADO, FORANEO, ETC			
			

			//echo json_encode($data["detallePeriodo"]);

			//echo "asdsad: ".$data["tieneDetalle"];
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/PagoComision',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jspagocomision');
		} else {
			redirect('Error_403','refresh');
		}

	}
	
	public function generarReportePago()
	{
		
		$this->Periodos_model->generarReportePago($this->input->post("tipo"),$this->input->post("trabajador"),$this->input->post("desde"),$this->input->post("hasta"));
	}

	public function filtrarTrabajador()
	{
		$this->Periodos_model->filtrarTrabajador($this->input->post("q"),$this->input->post("tipo"));
	}

	public function generarReportePagoDevoluciones()
	{

		$this->Periodos_model->generarReportePagoDevoluciones($this->input->post("tipo"),$this->input->post("trabajador"),$this->input->post("desde"),$this->input->post("hasta"));
	}

	public function printReportePago($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){

			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1);
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();
					
			$this->load->view('Reportes/reporte_pago_vendedores',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}

	public function printReportePagoConsolidado($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){
		
			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1,"consolidar");
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_vendedores_consolidado',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}

	
	public function printReportePagoSupervisores($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){
			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1);
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();
					
			$this->load->view('Reportes/reporte_pago_supervisores',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}

	public function printReportePagoSupervisoresConsolidado($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){
			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1,"consolidar");
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

			//echo json_encode($data["devoluciones"]);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_supervisores_consolidado',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}

	public function printReportePagoGerenteVentas($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){
			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1,"consolidar");
			/*$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);*/

			//echo json_encode($data["ventas"]);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_gerente_ventas',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}

	public function printReporteImpulsadorasPagoConsolidado($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){
			$data["ventas"] = $this->Hana_model->generarPagoImpulsadoras($tipo,$trabajador,$desde,$hasta,1,"consolidar");		
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_impulsadora_consolidado',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}
	public function printReporteImpulsadorasPago($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){
		//echo $trabajador;
			$data["ventas"] = $this->Hana_model->generarPagoImpulsadoras($tipo,$trabajador,$desde,$hasta,1,null);		
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			//$this->load->view('Reportes/reporte_pago_supervisora_detallado',$data);
			$this->load->view('Reportes/reporte_pago_impulsadora_detallado',$data);
		} else {
			redirect('Error_403','refresh');
		}
	}	

	public function traerEmpleadoPeriodo(){
		$var = $this->input->post("q"); 
		$tipo = $this->input->post("tipo"); 
		$this->Periodos_model->traerEmpleadoPeriodo($var,$tipo);
	}
}
?>

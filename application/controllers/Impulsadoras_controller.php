<?php
class Impulsadoras_controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("Periodos_model");
		$this->load->model("Impulsadoras_model");
		$this->load->library('session');
		if ($this->session->userdata("logged") != 1) {
            redirect(base_url() . 'index.php', 'refresh');
        }
	}

	public function index()
	{
		
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");//pendiente permiso
		if($permiso){
			$data["impulsadoras"] = $this->Impulsadoras_model->traerImpulsadoras();
			$data["sup"] = $this->Periodos_model->traerPeriodos();
			$data["meses"] = $this->Periodos_model->traerMeses();
			//echo json_encode($data["impulsadoras"]);
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/ClientesImpulsadoras',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jsclientesimpulsadoras');
		} else {
			redirect('Error_403','refresh');
		}

	}

	public function ClientesList(){
		$var = $this->input->post("q"); 
		$this->Hana_model->getClientes($var);
	}

	
	public function traerClientesImpulsadoras()
	{
		$this->Impulsadoras_model->traerClientesImpulsadoras($this->input->get_post("idImpulsadora"));
	}

	public function GuardarClientesImpulsadoras()
	{
		$this->Impulsadoras_model->GuardarClientesImpulsadoras($this->input->get_post("idImpulsadora"),$this->input->get_post("detalle"));
	}

	public function ComisionImpulsadoras()
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");//pendiente permiso
		if($permiso){
			$data["impulsadoras"] = $this->Impulsadoras_model->traerImpulsadoras();
			$data["sup"] = $this->Periodos_model->traerPeriodos();
			$data["meses"] = $this->Periodos_model->traerMeses();
			
			//echo json_encode($data["impulsadoras"]);return
			//echo json_encode($data["impulsadoras"]);
			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/ComisionImpulsadoras',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jscomisionesimpulsadoras');
		} else {
			redirect('Error_403','refresh');
		}
	}

	public function GuardarAdelantoImpulsadoras()
	{
		$this->Impulsadoras_model->GuardarAdelantoImpulsadoras($this->input->post("adelanto"),$this->input->post("idImpulsadora"));
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
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if ($permiso) {
			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1);
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();
					
			$this->load->view('Reportes/reporte_pago_vendedores',$data);
		}else{
			redirect("Error_403", "refresh");
		}
	}
	public function printReportePagoConsolidado($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if ($permiso) {

			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1,"consolidar");
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_vendedores_consolidado',$data);
		}else{
			redirect("Error_403", "refresh");
		}
	}
	

	
	public function printReportePagoSupervisores($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if ($permiso) {
	
			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1);
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();
					
			$this->load->view('Reportes/reporte_pago_supervisores',$data);
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function printReportePagoSupervisoresConsolidado($tipo,$trabajador,$desde,$hasta)
	{

		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if ($permiso) {

			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1,"consolidar");
			$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

			//echo json_encode($data["devoluciones"]);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_supervisores_consolidado',$data);
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function printReportePagoGerenteVentas($tipo,$trabajador,$desde,$hasta)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060");
		if ($permiso) {

			$data["ventas"] = $this->Periodos_model->generarReportePago($tipo,$trabajador,$desde,$hasta,1,"consolidar");
			/*$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);*/

			//echo json_encode($data["ventas"]);
			$data["desde"] = $desde;
			$data["hasta"] = $hasta;
			$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
			$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

			$this->load->view('Reportes/reporte_pago_gerente_ventas',$data);
		}else{
			redirect("Error_403", "refresh");
		}
	}

	public function traerEmpleadoPeriodo(){
		$var = $this->input->post("q"); 
		$tipo = $this->input->post("tipo"); 
		$this->Periodos_model->traerEmpleadoPeriodo($var,$tipo);
	}

	public function traerCategorias()
	{	
	
		$query = $this->db->query("SELECT  * from C_Categorias where Estado = 1 order by Nombre");

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	public function EditarPeriodoImpulsadora($id)
	{		
		
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){

			$data["encabezadoPeriodo"] = $this->Impulsadoras_model->traerEncabezadoPeriodo($id);//A,B,C
			$data["detallePeriodo"] = $this->Impulsadoras_model->traerDetallePeriodo($id);//A,B,C
			$data["impulsadoras"] = $this->Impulsadoras_model->traerImpulsadorasValor($id);
			
			$data["categorias"] = $this->traerCategorias();

			//echo json_encode($data["impulsadoras"]);
			//return;

			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/EditarPeriodoImpulsadora',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jscomisionimpulsadora');

		} else {
			redirect('Error_403','refresh');
		}
	}

	public function EditarPeriodoJefeImpulsadora($id)
	{
		$permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1060"); // pendiente el permiso
		if($permiso){

			$data["encabezadoPeriodo"] = $this->Impulsadoras_model->traerEncabezadoPeriodo($id);//A,B,C

			$data["detallePeriodo"] = $this->Impulsadoras_model->traerDetallePeriodoJefeImpulsadoras($id);//A,B,C

			$data["impulsadoras"] = $this->Impulsadoras_model->traerJefeImpulsadorasValor($id);

			$data["categorias"] = $this->traerCategorias();

			$this->load->view('header/header');
			$this->load->view('header/menu');
			$this->load->view('comisiones/EditarPeriodoJefeImpulsadora',$data);
			$this->load->view('footer/footer');
			$this->load->view('jsView/comisiones/jscomisionjefeimpulsadora');

		} else {
			redirect('Error_403','refresh');
		}
	}

	public function EditarValorJefeImpulsadora()
	{
		$this->Impulsadoras_model->EditarValorJefeImpulsadora($this->input->get_post("valorComision"),$this->input->get_post("idPeriodo"),$this->input->get_post("idUsuario"));
	}
	public function EditarValorImpulsadora()
	{
		$this->Impulsadoras_model->EditarValorImpulsadora($this->input->get_post("valorComision"),$this->input->get_post("idPeriodo"),$this->input->get_post("idUsuario"),$this->input->get_post("idCategoria"));
	}

	public function traerComisionImpulsadora()
	{		
		$this->Impulsadoras_model->traerComisionImpulsadora($this->input->get_post("idImpulsadora"),$this->input->get_post("idCategoria"),$this->input->get_post("idPeriodo"));
	}


	public function printReporteImpulsadorasEspecial($tipo,$trabajador,$desde,$hasta)//falta pasarle la bandera para json o ajax
	{

		//echo $tipo;
		
		$data["ventas"] = $this->Impulsadoras_model->printReporteImpulsadorasEspecial($tipo,$trabajador,$desde,$hasta,1,null);		
		$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);


		//echo json_encode($data["ventas"]);
		
		$data["desde"] = $desde;
		$data["hasta"] = $hasta;
		$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
		$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

		$this->load->view('Reportes/reporte_pago_supervisora_especial_detallado',$data);
	}

	public function printReporteImpulsadorasEspecialConsolidado($tipo,$trabajador,$desde,$hasta)
	{
		$data["ventas"] = $this->Impulsadoras_model->printReporteImpulsadorasEspecial($tipo,$trabajador,$desde,$hasta,1,"consolidar");		
		$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

		//echo json_encode($data["ventas"]);
		
		$data["desde"] = $desde;
		$data["hasta"] = $hasta;
		$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
		$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

		$this->load->view('Reportes/reporte_pago_supervisora_especial_consolidado',$data);
	}


	public function printReporteJefeImpulsadoras($tipo,$trabajador,$desde,$hasta)
	{
		//echo $tipo;		
		//$data["ventas"] = $this->Impulsadoras_model->printReporteJefeImpulsadoras($tipo,$trabajador,$desde,$hasta,1,null);

		$data["ventas"] = $this->Hana_model->printReporteJefeImpulsadoras($tipo,$trabajador,$desde,$hasta,1,null);
		$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

		//echo json_encode($data["ventas"]);
		$data["desde"] = $desde;
		$data["hasta"] = $hasta;
		$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
		$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

		$this->load->view('Reportes/reporte_pago_jefe_impulsadora_detallado',$data);
	}

	public function printReporteJefeImpulsadorasConsolidado($tipo,$trabajador,$desde,$hasta)
	{

		$data["ventas"] = $this->Hana_model->printReporteJefeImpulsadoras($tipo,$trabajador,$desde,$hasta,1,"consolidar");
		$data["devoluciones"] = $this->Periodos_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,1);

		//echo json_encode($data["ventas"]);
		
		$data["desde"] = $desde;
		$data["hasta"] = $hasta;
		$data["usuarioCrea"] = $this->Periodos_model->usuarioCrea($desde,$hasta);
		$data["usuarioActual"] = $this->Periodos_model->usuarioActual();

		$this->load->view('Reportes/reporte_pago_jefe_impulsadora_consolidado',$data);

	}

}
?>
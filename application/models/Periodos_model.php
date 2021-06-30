<?php
/**
 * 
 */
date_default_timezone_set("America/Managua");
class Periodos_model extends CI_Model
{
	
	function __construct()
	{
		$this->load->database();
		$this->load->model("Login_model");		
	}
	public function traerVendedores()
	{

		/*$facturas = $this->db->query("SELECT * FROM Facturas WHERE CAST(FECHA AS DATE) >= '20210401'");
		foreach ($facturas->result_array() as $key) {			
			
			$this->db->query("UPDATE Facturas SET IDUSUARIO = (SELECT ISNULL(IdUsuario,-1) FROM Usuarios where IdRuta = 
			'".$key["CODVENDEDOR"]."' AND Estado = 1) WHERE IDENCABEZADO = ".$key["IDENCABEZADO"]."");
		}*/

		$this->db->order_by("Estado",TRUE);
		$query = $this->db->get_where("Usuarios", array("IdRol" => 4, "Estado" => 1));
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return 0;
	}

	public function traerMeses()
	{
		$query = $this->db->query("SELECT DISTINCT Anio,Mes from C_Periodo WHERE Estado = 1");
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return 0;

	}
	public function traerPeriodos()
	{		

		$query = $this->db->query("SELECT T0.*,t1.Nombre_Usuario 
									FROM C_Periodo t0 
									LEFT JOIN usuarios t1 on t1.IdUsuario = t0.IdUsuario AND t1.estado = 1 AND T0.Estado = 1
									--ORDER BY FechaFinal ASC

									UNION 	

									SELECT T0.*,t1.Nombre_Usuario 
									FROM C_ImpulsadoraPeriodo t0 
									LEFT JOIN usuarios t1 on t1.IdUsuario = t0.IdUsuario AND t1.estado = 1 AND T0.Estado = 1
									ORDER BY IdPeriodo ASC");

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	public function traerCanales()
	{

		$this->db->order_by("Estado",TRUE);
		$query = $this->db->get("C_Canal");

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	public function traerGrupos()
	{
		$this->db->order_by("Estado",TRUE);
		$query = $this->db->get("C_Grupos");

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	public function traerCategoriasGrupo()
	{	
	
		$query = $this->db->query("SELECT  DISTINCT t0.IdCategoria,t2.Nombre Categoria,t1.IdGrupo,t1.Nombre 
								FROM C_Productos t0
								inner join C_Grupos t1 on t1.IdGrupo = t0.IdGrupo
								INNER JOIN C_Categorias t2 on t2.IdCategoria = T0.IdCategoria
								order by t1.Nombre");

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	public function traerEncabezadoPeriodo($id)
	{
		/*$this->db->order_by("Estado",TRUE);
		$this->db->where("IdPeriodo",$id);
		$query = $this->db->get("C_Periodo");*/

		$query = $this->db->query("
			SELECT T0.*,T1.Nombre UsuarioCrea,t2.Nombre UsuarioEdita
			FROM C_Periodo T0
			INNER JOIN Usuarios T1 ON T1.IdUsuario = T0.IdUsuarioCrea
			LEFT JOIN Usuarios T2 ON T2.IdUsuario = T0.IdUsuarioEdita
			WHERE IdPeriodo = ".$id);

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;	
	}

	public function tieneDetalle($id)
	{
		$existe = $this->db->query("SELECT T0.IdCanal,T0.IdGrupo,T0.IdCategoria,T0.ValorVendedor ,T2.Nombre Categoria,T1.Nombre
				FROM C_Comision T0 
				INNER JOIN C_Grupos T1 ON T1.IdGrupo = T0.IdGrupo
				INNER JOIN C_Categorias T2 on T2.IdCategoria = T0.IdCategoria
				WHERE T0.IdPeriodo = ".$id);

		if ($existe->num_rows()>0) {
			return 1;
		}
		return 0;
	}

	public function traerDetallePeriodo($id)
	{
		//$id = 20;
		
		$query = $this->db->query("WITH tabla as (
				SELECT  DISTINCT -1 IdCanal, T0.IdCategoria,t1.IdGrupo, 0 ValorVendedor,t2.Nombre Categoria,t1.Nombre
				FROM C_Productos t0
				INNER JOIN C_Grupos t1 on t1.IdGrupo = t0.IdGrupo
				INNER JOIN C_Categorias t2 on t2.IdCategoria = T0.IdCategoria
				WHERE  CONCAT (T1.IdGrupo,'-',T2.IdCategoria) NOT IN (SELECT CONCAT(IdGrupo,'-',IdCategoria) FROM C_Comision WHERE IdPeriodo = ".$id.")

				UNION

				SELECT T0.IdCanal,T0.IdCategoria,T0.IdGrupo,
				case when T3.Tipo = 1 THEN T0.ValorVendedor WHEN T3.TIPO = 2 THEN T0.ValorSupervisor ELSE 0 END AS ValorVendedor,
				T2.Nombre Categoria,T1.Nombre
				FROM C_Comision T0 
				INNER JOIN C_Grupos T1 ON T1.IdGrupo = T0.IdGrupo
				INNER JOIN C_Categorias t2 on t2.IdCategoria = T0.IdCategoria
				INNER JOIN C_Periodo T3 ON T3.IdPeriodo = T0.IdPeriodo
				WHERE T0.IdPeriodo = ".$id."
			)
			SELECT * FROM tabla
			ORDER BY Categoria, Nombre");
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	public function GuardarEdicionPeriodo($id,$detalle)
	{
		$mensaje = array();
		$activo = $this->db->query("SELECT *,DATEDIFF(month,FechaFinal,GETDATE()) Diferencia FROM C_periodo where IdPeriodo = ".$id." and Estado = 1");
		if ($activo->num_rows() == 0) {
			$mensaje[0]["mensaje"] = "Este periodo se encuentra inactivo o no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		}
/*
		if ($activo->result_array()[0]["Diferencia"] > 0) {
			$mensaje[0]["mensaje"] = "Este periodo pertenece a un mes anterior y no se puede modificar";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		}*/

		$tipo = $this->db->query("SELECT * FROM C_periodo WHERE IdPeriodo = ".$id);
		$tipo = $tipo->result_array()[0]["Tipo"];

		$this->db->trans_begin();
		//$det = json_decode($detalle, true);
		$this->db->query("UPDATE C_Comision SET Estado = 0 WHERE IdPeriodo = ".$id);
		foreach ($detalle as $obj) {
			$porciones = explode("-", $obj[0]);
			//echo $porciones[0];
			$existe = $this->db->query("SELECT * FROM C_Comision WHERE IdPeriodo = ".$id." and IdCategoria = ".$porciones[1]." AND IdCanal = ".$porciones[3]." and IdGrupo = ".$porciones[2]);
			if ($existe->num_rows() > 0) {
				if ($tipo == 1) {
					$update = array(
						"ValorVendedor" => $obj[1],
						"IdUsuarioEdita" => $this->session->userdata('id'),
						"FechaEdita" => gmdate(date("Y-m-d H:i:s")),
						"Estado" => 1
					);
				}
				if ($tipo == 2) {
					$update = array(
						"ValorSupervisor" => $obj[1],
						"IdUsuarioEdita" => $this->session->userdata('id'),
						"FechaEdita" => gmdate(date("Y-m-d H:i:s")),
						"Estado" => 1
					);
				}
				
				$this->db->where("IdPeriodo",$id);
				$this->db->where("IdCategoria",$porciones[1]);
				$this->db->where("IdGrupo",$porciones[2]);
				$this->db->where("IdCanal",$porciones[3]);

				$this->db->update("C_Comision",$update);
			}else{
				$ValueVendedor = 0;
				$ValueSupervisor = 0;
				if ($tipo == 1) {
					$ValueVendedor = $obj[1];
					$ValueSupervisor = 0;
				}
				if ($tipo == 2) {
					$ValueVendedor = 0;
					$ValueSupervisor = $obj[1];
				}

				$insert = array(	
					"IdCategoria" => $porciones[1],
					"IdGrupo" => $porciones[2],
					"IdCanal" => $porciones[3],
					"IdPeriodo" => $id,
					"ValorVendedor" => $ValueVendedor,
					"ValorSupervisor" => $ValueSupervisor,
					"Estado" => 1,
					"IdUsuarioCrea" => $this->session->userdata('id'),
					"FechaCrea" => gmdate(date("Y-m-d H:i:s"))
				);

				$this->db->insert("C_Comision",$insert);
			}			
			

		}
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$mensaje[0]["mensaje"] = "Error intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		} else {
			$this->db->trans_commit();
			$mensaje[0]["mensaje"] = "Datos guardados correctamente";
			$mensaje[0]["tipo"] = "success";
			echo json_encode($mensaje);
			return;
		}
	}

	public function GuardarEncabezadoPeriodo($desde, $hasta, $vendedor = null, $estado, $tipo)
	{
		//echo $tipo;return;
		$mensaje = array();
		$and = 'AND IdUsuario IS NULL ';
		$andMensaje = '';
		//echo $vendedor;return;
		if ($vendedor != "null") {
			$and = 'AND IdUsuario = '.$vendedor;
			$andMensaje = " y vendedor";
		}else{
			$vendedor = null;
		}
		
		$validDate = $this->db->query("SELECT *,
										CASE WHEN '".$desde."' < (SELECT DATEADD(DAY, 1, EOMONTH(GETDATE(), -1))) then 1 else 0 end
										 as mesPasado FROM C_Periodo
										WHERE FechaInicial = '".$desde."' AND FechaFinal = '".$hasta."' and Estado = 1
										".$and." and Tipo = ".$tipo);
		if ($validDate->num_rows()>0) {
			$mensaje[0]["mensaje"] = "Ya existe un periodo con este rango de fecha".$andMensaje.", modifique el anterior si desea modificar el pago de comisiones";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		}
		$mesPasado = $this->db->query("SELECT CASE WHEN '".$hasta."' < '".$desde."' then 1 else 0 end as Mes ,CASE WHEN '".$desde."' < (SELECT DATEADD(DAY, 1, EOMONTH(GETDATE(), -1))) then 1 else 0 end as mesPasado");
		if ($mesPasado->result_array()[0]["Mes"] == 1) {
			$mensaje[0]["mensaje"] = "La fecha final no puede ser mayor a la inicial";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		}
		/*if ($mesPasado->result_array()[0]["mesPasado"] == 1) {
			$mensaje[0]["mensaje"] = "No puede crear un periodo para un mes anterior al acutual";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		}*/

		$this->db->trans_begin();
		//echo $vendedor;
				

		$insert = array(	
			"Tipo" => $tipo,
			"FechaInicial" => $desde,
			"FechaFinal" => $hasta,
			"IdUsuario" => $vendedor,
			"Mes" =>  date("m",strtotime($desde)),
			"Anio" => date("Y",strtotime($desde)),
			"Pagado" => false,
			"Estado" => true,
			"IdUsuarioCrea" => $this->session->userdata('id'),
			"FechaCrea" => gmdate(date("Y-m-d H:i:s"))
		);

		if ($tipo == 3 ||  $tipo == 4  || $tipo == 5) {
			$this->db->insert("C_ImpulsadoraPeriodo",$insert);
		}else{
			$this->db->insert("C_Periodo",$insert);
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$mensaje[0]["mensaje"] = "Error intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		} else {
			$this->db->trans_commit();
			$mensaje[0]["mensaje"] = "Periodo guardado correctamente";
			$mensaje[0]["tipo"] = "success";
			echo json_encode($mensaje);
			return;
		}

	}

	public function ActualizarEstadoPeriodo($id,$estado)
	{
		$mensaje = array();

		$validar = $this->db->query("SELECT * FROM C_Periodo");
		if ($estado == 1) {
			$estado = 0;
		} else {
			$estado = 1;
		}

		if ($validar->result_array()[0]["Mes"] != date("m",strtotime(gmdate(date("Y-m-d")))) 
			&& $validar->result_array()[0]["Anio"] != date("Y",strtotime(gmdate(date("Y-m-d"))))) {
			$mensaje[0]["mensaje"] = "Solo puede modificar periodos del mes actual";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		}
		$this->db->trans_begin();
		$this->db->where("IdPeriodo", $id);
        $data = array(
            "Estado" => $estado
        );
        $this->db->update("C_Periodo", $data);
        
        if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$mensaje[0]["mensaje"] = "Error intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		} else {
			$this->db->trans_commit();
			$mensaje[0]["mensaje"] = "Estado guardado correctamente";
			$mensaje[0]["tipo"] = "success";
			echo json_encode($mensaje);
			return;
		}

	}

	public function copiarPeriodos($mesOrigen,$anioOrigen,$mesDestino,$anioDestino,$tipo)
	{
		$mensaje = array();

		$existe = $this->db->query("SELECT * FROM C_Periodo WHERE Mes = ".gmdate(date("m"))." and Anio = ".gmdate(date("Y"))." and Estado = 1");

		if ($existe->num_rows() > 0) {
			$mensaje[0]["mensaje"] = "Ya existen periodos para el mes actual, unicamente se puede copiar periodos si el mes actual no contiene";
			$mensaje[0]["tipo"] = "success";
			echo json_encode($mensaje);
			return;	
		}

		$periodos = $this->db->query("SELECT * FROM C_Periodo WHERE Mes = ".$mesOrigen." and Anio = ".$anioOrigen." and Estado = 1
			and Tipo = ".$tipo);
		//echo "SELECT * FROM C_Periodo WHERE Mes = ".$mesOrigen." and Anio = ".$anioOrigen." and Estado = 1";
		if ($periodos->num_rows() == 0) {
			$mensaje[0]["mensaje"] = "No existen periodos en los criterios de origen seleccionados";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;	
		}

		$this->db->trans_begin();


		foreach ($periodos->result_array() as $key) {
			
			$fechaInicial = $this->db->query("SELECT convert(date,DATEADD (month , 1 , '".$key["FechaInicial"]."'),29) as primerDia");
			$fechaFinal = $this->db->query("SELECT convert(date,DATEADD (month , 1 , '".$key["FechaFinal"]."'),29) as ultimoDia");

			$insert = array(
				"Tipo" => $key["Tipo"],
				"FechaInicial" => $fechaInicial->result_array()[0]["primerDia"],
				"FechaFinal" => $fechaFinal->result_array()[0]["ultimoDia"],
				"IdUsuario" => $key["IdUsuario"],
				"Mes" =>  date("m",strtotime($fechaInicial->result_array()[0]["primerDia"])),
				"Anio" => date("Y",strtotime($fechaInicial->result_array()[0]["primerDia"])),
				"Pagado" => false,
				"Estado" => true,
				"IdUsuarioCrea" => $this->session->userdata('id'),
				"FechaCrea" => gmdate(date("Y-m-d H:i:s"))
			);
			$this->db->insert("C_Periodo",$insert);

			$detalle = $this->db->query("SELECT * FROM C_Comision WHERE IdPeriodo = ".$key["IdPeriodo"]);
			$idperiodo = $this->db->query("SELECT MAX(IdPeriodo) IdPeriodo from C_Periodo");
			$idperiodo = $idperiodo->result_array()[0]["IdPeriodo"];

			if ($detalle->num_rows()>0) {
					foreach ($detalle->result_array()  as $keyDet) {
						$insert = array(
						"IdCategoria" => $keyDet["IdCategoria"],
						"IdGrupo" => $keyDet["IdGrupo"],
						"IdCanal" => $keyDet["IdCanal"],
						"IdPeriodo" => $idperiodo,
						"ValorVendedor" => $keyDet["ValorVendedor"],
						"ValorSupervisor" => $keyDet["ValorSupervisor"],
						"Estado" => 1,
						"IdUsuarioCrea" => $this->session->userdata('id'),
						"FechaCrea" => gmdate(date("Y-m-d H:i:s"))
					);

					$this->db->insert("C_Comision",$insert);
				}
			}
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$mensaje[0]["mensaje"] = "Error intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			return;
		} else {
			$this->db->trans_commit();
			$mensaje[0]["mensaje"] = "Periodo copiado correctamente";
			$mensaje[0]["tipo"] = "success";
			echo json_encode($mensaje);
			return;
		}
	}

	public function filtrarTrabajador($search,$tipo)
	{
		$json = array();
        $i = 0;
		if ($tipo == 1) {
			
			$query = $this->db->query("SELECT t0.*,t2.IdUsuario
										FROM C_Rutas t0
										INNER JOIN C_RutaCanal T1 ON T1.IdRuta = T0.IdRuta
										INNER JOIN Usuarios t2 on t2.IdRuta = t0.IdRuta and t2.Estado = 1
										where t1.Estado = 1 and t0.Nombre like '%".$search."%'" );
            foreach ($query->result_array() as $key) {            
                $json[$i]["Id"] = $key["IdUsuario"];
                $json[$i]["Nombre"] = utf8_encode($key["Nombre"]);                
                $i++;
            }
            echo json_encode($json);
		}
		if ($tipo == 2) {
			$query = $this->db->query("SELECT t0.*
										FROM Usuarios t0
										WHERE T0.Estado = 1 AND IdRol = 3
										and IdUsuario in (SELECT IdSupervisor from usuarios	where Estado = 1) and t0.Nombre like '%".$search."%'");

            foreach ($query->result_array() as $key) {            
                $json[$i]["Id"] = $key["IdUsuario"];
                $json[$i]["Nombre"] = utf8_encode($key["Nombre"].' '.$key["Apellidos"]);
                $i++;
            }
            echo json_encode($json);
		}
		if ($tipo == 4) {
			$query = $this->db->query("SELECT t0.*
										FROM Usuarios t0
										WHERE T0.Estado = 1 AND IdRol = 20");
            foreach ($query->result_array() as $key) {            
                $json[$i]["Id"] = $key["IdUsuario"];
                $json[$i]["Nombre"] = utf8_encode($key["Nombre"].' '.$key["Apellidos"]);
                $i++;
            }
            echo json_encode($json);
		}
		if ($tipo == 5) {
			$query = $this->db->query("SELECT t0.*
										FROM Usuarios t0
										WHERE T0.Estado = 1 AND IdRol = 20 and IdSupervisora = 2137");
            foreach ($query->result_array() as $key) {            
                $json[$i]["Id"] = $key["IdUsuario"];
                $json[$i]["Nombre"] = utf8_encode($key["Nombre"].' '.$key["Apellidos"]);
                $i++;
            }
            echo json_encode($json);
		}
		if ($tipo == 6) {
			$query = $this->db->query("SELECT t0.*
										FROM Usuarios t0
										WHERE T0.Estado = 1 AND IdRol = 21");
            foreach ($query->result_array() as $key) {            
                $json[$i]["Id"] = $key["IdUsuario"];
                $json[$i]["Nombre"] = utf8_encode($key["Nombre"].' '.$key["Apellidos"]);
                $i++;
            }
            echo json_encode($json);
		}
		
	}

	public function traerVentas($trabajador,$desde,$hasta,$bandera = null)
	{
		//$trabajador = 2114;
		$and = '';
	    if ($trabajador != null && $trabajador != 0) {
	      $and = ' and t1.IdUsuario = '.$trabajador."";
	    }

	    $and = '';
		$SlpCodes = '';
	    if ($trabajador != null && $trabajador != 0) {
		    $idRuta = $this->db->query("SELECT IdRuta FROM Usuarios where IdUsuario =".$trabajador."");
		    $usuarios = $this->db->query("SELECT IdUsuario FROM Usuarios where IdRuta =".$idRuta->result_array()[0]["IdRuta"]."");
		    if ($usuarios->result_array()>0) {
		      foreach ($usuarios->result_array() as $key) {
		        $SlpCodes .= $key["IdUsuario"].",";
		      }
		      $SlpCodes = substr($SlpCodes, 0, -1);
		      $and = " and T1.IdUsuario in (".$SlpCodes.")";
		    }
		}

		//echo $and;return;

		$consulta = "SELECT
					t0.IdCategoria
					,T1.IdUsuario
					,T6.IdCanal
					,t0.IdGrupo
					,T2.Nombre Categoria
					,t3.Nombre Grupo
					,T1.CODVENDEDOR
					,T4.Nombre
					,ISNULL(sum( T1.LIBRAS ),0) Libras
				FROM
					C_Productos T0
					LEFT JOIN VIEW_VENTAS_4BUY T1 ON T0.IdProducto = T1.CODIGO
					LEFT JOIN C_Categorias T2 ON T2.IdCategoria = T0.IdCategoria
					LEFT JOIN C_Grupos T3 ON T3.IdGrupo = T0.IdGrupo
					LEFT JOIN Usuarios T4 ON T4.IdUsuario = T1.IdUsuario
					LEFT JOIN C_Rutas T5 ON T5.IdRuta = T4.IdRuta --A ND T4.Estado = 1
					LEFT JOIN C_RutaCanal T6 ON T6.IdRuta = T5.IdRuta AND T6.Estado = 1 
				WHERE
					t1.ESTADOAPP <> 4 
					AND CAST( T1.FECHA AS DATE ) >= '".$desde."' 
					AND CAST( T1.FECHA AS DATE ) <= '".$hasta."' 
					".$and."
				GROUP BY
					T6.IdCanal,
					T1.IdUsuario,
					T4.Nombre,
					T1.CODVENDEDOR,
					T0.IdCategoria,
					T0.IdGrupo,
					t3.Nombre,
					t2.Nombre 
					
				ORDER BY
					t4.Nombre,
					t3.Nombre,
					T2.Nombre";
		$query = $this->db->query($consulta);
		///echo $consulta."<br>";


		if ($query->num_rows()>0) {
			return $query->result_array();
		}
		return 0;
	}

	public function traerVentasSupervisor($trabajador,$desde,$hasta)
	{
		$and = '';
		$SlpCodes = '';
	    if ($trabajador != null && $trabajador != 0) {
		    $supervisores = $this->db->query("SELECT * FROM Usuarios where IdSupervisor =".$trabajador." and Estado = 1");
		    
		    if ($supervisores->result_array()>0) {
		      foreach ($supervisores->result_array() as $key) {
		        $SlpCodes .= $key["IdRuta"].",";
		      }
		      $SlpCodes = substr($SlpCodes, 0, -1);
		      $and = " and T0.CODVENDEDOR in (".$SlpCodes.")";
		    }
		}

		$consulta = "SELECT T7.IdUsuario IdSupervisor,
							T0.IdUsuario,
							t7.Nombre+' '+T7.Apellidos AS Nombre,
							T6.IdCanal,	T1.IdCategoria, T2.Nombre Categoria, t1.IdGrupo,
							t3.Nombre Grupo, SUM ( T0.LIBRAS ) Libras 
						FROM
							VIEW_VENTAS_4BUY T0
							INNER JOIN C_Productos T1 ON T1.IdProducto = T0.CODIGO
							INNER JOIN C_Categorias T2 ON T2.IdCategoria = T1.IdCategoria
							INNER JOIN C_Grupos T3 ON T3.IdGrupo = T1.IdGrupo
							INNER JOIN Usuarios T4 ON T4.IdUsuario = T0.IdUsuario
							INNER JOIN C_Rutas T5 ON T5.IdRuta = T4.IdRuta 
							AND T4.Estado = 1
							INNER JOIN C_RutaCanal T6 ON T6.IdRuta = T5.IdRuta and t6.Estado = 1
							INNER JOIN Usuarios T7 ON T7.IdUsuario = T4.IdSupervisor and T7.Estado = 1
						WHERE
							ESTADOAPP <> 4 
							".$and."
									AND CAST(T0.FECHA AS DATE) >= '".$desde."' AND CAST(T0.FECHA AS DATE) <= '".$hasta."'
						GROUP BY
							t7.IdUsuario,
							T0.IdUsuario,
							t7.Nombre,
							T7.Apellidos,
							T6.IdCanal,	
							T1.IdCategoria,
							t1.IdGrupo,
							t3.Nombre,
							t2.Nombre 
						ORDER BY
							t7.IdUsuario,
							t7.Nombre,
							t3.Nombre,
							T2.Nombre";
		
		//echo $consulta;return;
		$query = $this->db->query($consulta);


		if ($query->num_rows()>0) {
			return $query->result_array();
		}
		return 0;
	}

	public function generarReportePago($tipo,$trabajador,$desde,$hasta,$bandera = null,$consolidado = null)
	{
		
		$json = array();
        $i = 0;
        
		if ($tipo == 1) {//comision  de vendedores
			$and = '';

			
			if ($trabajador != null && $trabajador != 0) {
				
				$idRuta = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$and = " and T1.IdRuta = ".$idRuta->result_array()[0]["IdRuta"];
			}

			$canales = $this->db->query("SELECT distinct t0.IdCanal ,t0.Nombre as Canal 
											FROM C_Canal T0
											inner join C_RutaCanal t1 on t1.IdCanal = t0.IdCanal
											WHERE T0.Estado = 1".$and);
		
			$this->db->trans_begin();

			$this->db->query("TRUNCATE TABLE C_TempV");

			$ventas = $this->traerVentas($trabajador,$desde,$hasta);


			foreach ($canales->result_array() as $keyCanales) {
				foreach ($ventas as $key) {
					//if ($trabajador != null) {
					if ($keyCanales["IdCanal"] == $key["IdCanal"]) {

						$comision = $this->obtenerComision(1,$key["IdUsuario"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"],$key["IdCanal"]);

						if ($comision > 0) {
						$devolucion = number_format($this->Hana_model->getDevolucion(1,$key["CODVENDEDOR"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]),2);

						$calculo = $this->db->query("SELECT round((".$key["Libras"]." - ".$devolucion.") * ".$comision.",2) as calculo");
						//echo $key["Libras"]."<br>";
				                $insert = array(
									"IdUsuario" => $key["IdUsuario"], 
									"Nombre" => $key["Nombre"],
									"IdCategoria" => $key["IdCategoria"],
									"Categoria" => $key["Categoria"],
									"IdGrupo" => $key["IdGrupo"],
									"Grupo" => $key["Grupo"],
									"IdCanal" => $key["IdCanal"],
									"Libras" => $key["Libras"],
									"Devolucion" => $devolucion,
									"Comision" => $comision,
									"TotalLibras" => $key["Libras"] - $devolucion,
									//"Total" => ($key["Libras"] - $devolucion) * $comision
									"Total" => $calculo->result_array()[0]["calculo"]
								);
								$this->db->insert("C_TempV",$insert);
						}else{
							//echo "IdCanal: ".$key["IdCanal"]. " Categoria: ".$key["IdCategoria"];
						}
					}
					//}
				}
			}

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
			
			$consulta = "SELECT IdUsuario,Nombre,IdCategoria,Categoria,IdGrupo,Grupo,SUM(Libras) Libras,SUM(Devolucion)Devolucion,SUM(Libras) - SUM(Devolucion) TotalLibras,Comision, /*(SUM(Libras) - SUM(Devolucion)) * comision*/ SUM(Total) Total
					from c_tempv
					group by IdUsuario,Nombre,IdCategoria,Categoria,IdGrupo,Grupo,Comision";
			if ($consolidado != null) {
				$consulta = "WITH TABLA AS (
							SELECT IdUsuario,Nombre,SUM(Libras) Libras, SUM(Devolucion) Devolucion, SUM(Libras) - SUM(Devolucion)TotalLibras,(SUM(Libras) - SUM(Devolucion)) * comision Total
							from c_tempv
							group by IdUsuario,Nombre,Comision
							)
							SELECT T1.IdRuta, T0.IdUsuario,T0.Nombre,sum(Devolucion) Devolucion,sum(T0.Libras) Libras,sum(T0.TotalLibras) TotalLibras,sum(T0.Total)Total
							from TABLA T0
							inner join Usuarios t1 on T0.IdUsuario = t1.IdUsuario
							GROUP BY T1.IdRuta,T0.IdUsuario,T0.Nombre
							,T1.IdRuta
							ORDER BY T1.IdRuta,Nombre";
			}

			$query = $this->db->query($consulta);
			//echo $consulta;
			if ($query->num_rows() > 0 && $consolidado == null) {
				foreach ($query->result_array() as $key) {
					$json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
					$json["data"][$i]["Nombre"] = utf8_encode($key["Nombre"]);
					$json["data"][$i]["IdCategoria"] = $key["IdCategoria"];
					$json["data"][$i]["Categoria"] = $key["Categoria"];
					$json["data"][$i]["IdGrupo"] = $key["IdGrupo"];
					$json["data"][$i]["Grupo"] = $key["Grupo"];
					$json["data"][$i]["Devolucion"] = number_format($key["Devolucion"],2);
					$json["data"][$i]["TotalLibras"] = number_format($key["TotalLibras"],2);
					$json["data"][$i]["Libras"] = number_format($key["Libras"],2);
					$json["data"][$i]["Comision"] = number_format($key["Comision"],2);
					$json["data"][$i]["Total"] = number_format($key["Total"],2);
					$i++;
				}
			}
			if ($query->num_rows() > 0 && $consolidado != null) { //echo "string";
				foreach ($query->result_array() as $key) {
					$json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
					$json["data"][$i]["IdRuta"] = $key["IdRuta"];
					$json["data"][$i]["Nombre"] = utf8_encode($key["Nombre"]);
					$json["data"][$i]["Libras"] = number_format($key["Libras"],2);
					$json["data"][$i]["Devolucion"] = number_format($key["Devolucion"],2);
					$json["data"][$i]["TotalLibras"] = number_format($key["TotalLibras"],2);
					$json["data"][$i]["Total"] = number_format($key["Total"],2);
					$i++;
				}
			}
			if ($bandera != null) {
				return $json;
			}
			echo json_encode($json);
		}

		if ($tipo == 2) {

			$and = '';

			$SlpCodes = '';



			if ($trabajador != null && $trabajador != 0) {
			
				$supervisores = $this->db->query("SELECT * FROM Usuarios where IdSupervisor =".$trabajador." and Estado = 1");
		    
			    if ($supervisores->result_array()>0) {
			      foreach ($supervisores->result_array() as $key) {
			        $SlpCodes .= $key["IdRuta"].",";
			      }
			      $SlpCodes = substr($SlpCodes, 0, -1);
			    }

				//$idRuta = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$and = " and T1.IdRuta in (".$SlpCodes.")";
			}

			/*echo "SELECT distinct t0.IdCanal ,t0.Nombre as Canal
										FROM C_Canal T0
										INNER JOIN C_RutaCanal t1 on t1.IdCanal = t0.IdCanal
										WHERE T0.Estado = 1".$and;return;*/
										
			$canales = $this->db->query("SELECT distinct t0.IdCanal ,t0.Nombre as Canal
										FROM C_Canal T0
										INNER JOIN C_RutaCanal t1 on t1.IdCanal = t0.IdCanal
										WHERE T0.Estado = 1".$and);
			$this->db->trans_begin();

			$borrar = $this->db->query("TRUNCATE TABLE C_TempV");
			//echo $trabajador;
			$ventas = $this->traerVentasSupervisor($trabajador,$desde,$hasta);

			foreach ($canales->result_array() as $keyCanales) {
				foreach ($ventas as $key) {
					
					if ($keyCanales["IdCanal"] == $key["IdCanal"]) {
						$comision = $this->obtenerComision(2,$trabajador,$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"],$key["IdCanal"]);
						//return;
						if ($comision > 0) {

							$devolucion = $this->Hana_model->getDevolucionSupervisor(1,$key["IdSupervisor"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]);
							//echo $devolucion."<br>";
							$insert = array(
								"IdSupervisor" => $key["IdSupervisor"],
								"IdUsuario" => $key["IdUsuario"],
								"Nombre" => utf8_encode($key["Nombre"]),
								"IdCategoria" => $key["IdCategoria"],
								"Categoria" => $key["Categoria"],
								"IdGrupo" => $key["IdGrupo"],
								"Grupo" => $key["Grupo"],
								"IdCanal" => $key["IdCanal"],
								"Devolucion" => $devolucion,
								"Libras" => $key["Libras"],
								"Comision" => $comision,
								"Total" => $key["Libras"]// * $comision
							);
							$this->db->insert("C_TempV",$insert);
							//echo json_encode($insert);
							
						}
					}
				}
			}


			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();				
			} else {
				$this->db->trans_commit();				
			}

			$consulta = "SELECT IdSupervisor, Nombre, IdCategoria,Categoria,IdGrupo,Grupo,IdCanal,comision,Sum(Libras) Libras,Sum(Total) Total
											FROM C_tempV
											group by IdSupervisor, Nombre, IdCategoria,Categoria,IdGrupo,Grupo,IdCanal,comision";
			///echo $consulta;
			if ($consolidado != null) {
				$consulta = "WITH TABLA AS (
							SELECT IdSupervisor IdUsuario,Nombre,
							SUM(Libras)/* - isnull((SELECT SUM(DISTINCT ti.Devolucion) FROM C_TempV ti where ti.IdCategoria = c_tempv.IdCategoria and ti.IdGrupo = c_tempv.IdGrupo AND TI.IdCanal = c_tempv.IdCanal),0)*/TotalLibras
							,(SUM(Libras)/* - isnull((SELECT SUM(DISTINCT ti.Devolucion) FROM C_TempV ti where ti.IdCategoria = c_tempv.IdCategoria and ti.IdGrupo = c_tempv.IdGrupo AND TI.IdCanal = c_tempv.IdCanal),0)*/) * comision Total
							from c_tempv
							group by IdSupervisor,Nombre,Comision,IdCanal,IdGrupo,IdCategoria
							)
							SELECT T1.IdRuta, T0.IdUsuario,T0.Nombre,sum(T0.TotalLibras) TotalLibras,sum(T0.Total)Total
							FROM TABLA T0
							INNER JOIN Usuarios t1 on T0.IdUsuario = t1.IdUsuario
							GROUP BY T1.IdRuta,T0.IdUsuario,T0.Nombre
							,T1.IdRuta
							ORDER BY T1.IdRuta,Nombre";
			}
			//echo $consulta;
			$query = $this->db->query($consulta);
			
			if ($query->num_rows() > 0 && $consolidado == null) {

				foreach ($query->result_array() as $key) {
					$json["data"][$i]["IdUsuario"] = $key["IdSupervisor"];
					$json["data"][$i]["Nombre"] = $key["Nombre"];
					$json["data"][$i]["IdCategoria"] = $key["IdCategoria"];
					$json["data"][$i]["Categoria"] = $key["Categoria"];
					$json["data"][$i]["IdGrupo"] = $key["IdGrupo"];
					$json["data"][$i]["Grupo"] = $key["Grupo"];
					$json["data"][$i]["IdCanal"] = $key["IdCanal"];
					$json["data"][$i]["Devolucion"] = number_format($this->Hana_model->getDevolucionSupervisor(1,$key["IdSupervisor"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]),2);
					$json["data"][$i]["Libras"] = number_format($key["Libras"],2);					
					$json["data"][$i]["Total"] = number_format(($key["Libras"] - $json["data"][$i]["Devolucion"]) * $key["comision"],2);					
					$json["data"][$i]["Comision"] = number_format($key["comision"],2);
					$json["data"][$i]["TotalLibras"] = number_format($key["Libras"] - $json["data"][$i]["Devolucion"],2);
					//return;

					$i++;

				}
			}
			if ($query->num_rows() > 0 && $consolidado != null) {

				foreach ($query->result_array() as $key) {
					/*$devolucion = $this->Hana_model->getDevolucionSupervisor(1,$key["IdSupervisor"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]);*/
					//echo $key["Nombre"].' '.$key["Total"]."<br>";
					$json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
					$json["data"][$i]["IdRuta"] = $key["IdRuta"];
					$json["data"][$i]["Nombre"] = utf8_encode($key["Nombre"]);					
					$json["data"][$i]["TotalLibras"] = number_format($key["TotalLibras"],2);
					$json["data"][$i]["Total"] = number_format($key["Total"],2);
					$i++;
				}
			}
			if ($bandera != null) {
				return $json;
			}
			echo json_encode($json);

			
		}

		if ($tipo == 3) {//comision gerente de ventas
			
			return $this->Hana_model->generarPagoGerente($desde,$hasta,$bandera);			
		}
		

	}

	public function generarReportePagoConsolidado($tipo,$trabajador,$desde,$hasta,$bandera = null)
	{
		//echo $trabajador;return;
		$json = array();
        $i = 0;
        
		if ($tipo == 1) {//comision  de vendedores
			$and = '';

			
			if ($trabajador != null && $trabajador != 0) {
				
				$idRuta = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$and = " and T1.IdRuta = ".$idRuta->result_array()[0]["IdRuta"];
			}

			$canales = $this->db->query("SELECT distinct t0.IdCanal ,t0.Nombre as Canal 
											FROM C_Canal T0
											inner join C_RutaCanal t1 on t1.IdCanal = t0.IdCanal
											WHERE T0.Estado = 1".$and);

			$ventas = $this->traerVentas($trabajador,$desde,$hasta);			
			foreach ($canales->result_array() as $keyCanales) {
				foreach ($ventas as $key) {
					//if ($trabajador != null) {
					if ($keyCanales["IdCanal"] == $key["IdCanal"]) {

						$comision = $this->obtenerComision(1,$key["IdUsuario"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"],$key["IdCanal"]);
							//echo $comision."|";
							//return;
						if ($comision > 0) {							
				               
								$json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
								$json["data"][$i]["Nombre"] = $key["Nombre"];
								$json["data"][$i]["IdCategoria"] = $key["IdCategoria"];
								$json["data"][$i]["Categoria"] = $key["Categoria"];
								$json["data"][$i]["IdGrupo"] = $key["IdGrupo"];
								$json["data"][$i]["Grupo"] = $key["Grupo"];
								$json["data"][$i]["IdCanal"] = $key["IdCanal"];
								$json["data"][$i]["Devolucion"] = number_format($this->Hana_model->getDevolucion(1,$key["CODVENDEDOR"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]),2);
								$json["data"][$i]["Libras"] = number_format($key["Libras"],2);
								$json["data"][$i]["Comision"] = number_format($comision,2);
								$json["data"][$i]["TotalLibras"] = number_format($key["Libras"] - $json["data"][$i]["Devolucion"],2);
								$json["data"][$i]["Total"] = number_format(($key["Libras"] - $json["data"][$i]["Devolucion"]) * $comision,2);

								$i++;
							}
						}
					//}
					}
				}

			if ($bandera != null) {
				return $json;
			}
			echo json_encode($json);
			

		}

		if ($tipo == 2) {
			$and = '';

			$SlpCodes = '';

		    
			if ($trabajador != null && $trabajador != 0) {
				$supervisores = $this->db->query("SELECT * FROM Usuarios where IdSupervisor =".$trabajador." and Estado = 1");
		    
			    if ($supervisores->result_array()>0) {
			      foreach ($supervisores->result_array() as $key) {
			        $SlpCodes .= $key["IdRuta"].",";
			      }
			      $SlpCodes = substr($SlpCodes, 0, -1);
			    }

				//$idRuta = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$and = " and T1.IdRuta in (".$SlpCodes.")";
			}

			$canales = $this->db->query("SELECT distinct t0.IdCanal ,t0.Nombre as Canal
										FROM C_Canal T0
										INNER JOIN C_RutaCanal t1 on t1.IdCanal = t0.IdCanal
										WHERE T0.Estado = 1".$and);
			$this->db->trans_begin();

			$borrar = $this->db->query("TRUNCATE TABLE C_TempV");

			$ventas = $this->traerVentasSupervisor($trabajador,$desde,$hasta);

			foreach ($canales->result_array() as $keyCanales) {
				foreach ($ventas as $key) {
					
					if ($keyCanales["IdCanal"] == $key["IdCanal"]) {
						$comision = $this->obtenerComision(2,$key["IdUsuario"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"],$key["IdCanal"]);

						if ($comision > 0) {
							
							$insert = array(
								"IdSupervisor" => $key["IdSupervisor"],
								"IdUsuario" => $key["IdUsuario"],
								"Nombre" => utf8_encode($key["Nombre"]),
								"IdCategoria" => $key["IdCategoria"],
								"Categoria" => $key["Categoria"],
								"IdGrupo" => $key["IdGrupo"],
								"Grupo" => $key["Grupo"],
								"IdCanal" => $key["IdCanal"],
								"Libras" => $key["Libras"],
								"Comision" => $comision,
								"Total" => $key["Libras"]// * $comision
							);

							$this->db->insert("C_TempV",$insert);
							
						}
					}
				}
			}


			$resultado = $this->db->query("SELECT IdSupervisor, Nombre, IdCategoria,Categoria,IdGrupo,Grupo,IdCanal,comision,Sum(Libras) Libras,Sum(Total) Total
											FROM C_tempV
											group by IdSupervisor, Nombre, IdCategoria,Categoria,IdGrupo,Grupo,IdCanal,comision");
			if ($resultado->num_rows()>0) {
				foreach ($resultado->result_array() as $key) {
					$json["data"][$i]["IdUsuario"] = $key["IdSupervisor"];
					$json["data"][$i]["Nombre"] = $key["Nombre"];
					$json["data"][$i]["IdCategoria"] = $key["IdCategoria"];
					$json["data"][$i]["Categoria"] = $key["Categoria"];
					$json["data"][$i]["IdGrupo"] = $key["IdGrupo"];
					$json["data"][$i]["Grupo"] = $key["Grupo"];
					$json["data"][$i]["IdCanal"] = $key["IdCanal"];
					$json["data"][$i]["Devolucion"] = number_format($this->Hana_model->getDevolucionSupervisor(1,$key["IdSupervisor"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]),2);
					$json["data"][$i]["Libras"] = number_format($key["Libras"],2);					
					$json["data"][$i]["Total"] = number_format(($key["Libras"] - $json["data"][$i]["Devolucion"]) * $key["comision"],2);					
					$json["data"][$i]["Comision"] = number_format($key["comision"],2);
					$json["data"][$i]["TotalLibras"] = number_format($key["Libras"] - $json["data"][$i]["Devolucion"],2);								

					$i++;
				}
			}
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();						
				return 0;
			} else {
				$this->db->trans_commit();
				if ($bandera != null) {
					return $json;
				}
				echo json_encode($json);				
			}

			
		}
	}

	public function obtenerComision($tipo,$trabajador,$desde,$hasta,$idCategoria,$idGrupo,$idCanal)
	{

		$comision = 0;
		if ($tipo == 1) {
			
			$queryComision = $this->db->query("SELECT round(T0.ValorVendedor,2) as ValorVendedor
									FROM C_Comision T0
									INNER JOIN C_Periodo T1 ON T1.IdPeriodo = T0.IdPeriodo
									WHERE T1.Estado = 1
									AND MONTH(T1.FechaInicial) = MONTH('".$desde."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$desde."') 
									AND MONTH(T1.FechaInicial) = MONTH('".$hasta."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$hasta."')
									AND T0.IdCategoria = ".$idCategoria."
									AND T0.IdGrupo = ".$idGrupo."
									AND T0.IdCanal = ".$idCanal."
									AND T1.IdUsuario = ".$trabajador."
									AND T1.Estado = 1");
			

			if ($queryComision->num_rows() > 0) {
				$comision = $queryComision->result_array()[0]["ValorVendedor"];
				return $comision;
			}

			$queryComision = $this->db->query("SELECT round(T0.ValorVendedor,2) as ValorVendedor
									FROM C_Comision T0
									INNER JOIN C_Periodo T1 ON T1.IdPeriodo = T0.IdPeriodo
									WHERE T1.Estado = 1
									AND MONTH(T1.FechaInicial) = MONTH('".$desde."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$desde."') 
									AND MONTH(T1.FechaInicial) = MONTH('".$hasta."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$hasta."')
									AND T0.IdCategoria = ".$idCategoria."
									AND T0.IdGrupo = ".$idGrupo."
									AND T0.IdCanal = ".$idCanal."
									AND T1.Estado = 1");

			if ($queryComision->num_rows() > 0) {
				$comision = $queryComision->result_array()[0]["ValorVendedor"];
				return $comision;
			}
		}
		if ($tipo == 2) {
			
			$queryComision = $this->db->query("SELECT round(T0.ValorSupervisor,2) as ValorSupervisor
									FROM C_Comision T0
									INNER JOIN C_Periodo T1 ON T1.IdPeriodo = T0.IdPeriodo
									WHERE T1.Estado = 1
									AND MONTH(T1.FechaInicial) = MONTH('".$desde."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$desde."') 
									AND MONTH(T1.FechaInicial) = MONTH('".$hasta."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$hasta."')
									AND T0.IdCategoria = ".$idCategoria."
									AND T0.IdGrupo = ".$idGrupo."
									AND T0.IdCanal = ".$idCanal."
									AND T1.IdUsuario = ".$trabajador."
									AND T1.Estado = 1
									AND T1.Tipo = 2");
			if ($queryComision->num_rows() > 0) {
				$comision = $queryComision->result_array()[0]["ValorSupervisor"];
				return $comision;
			}
		
			$queryComision = $this->db->query("SELECT round(T0.ValorSupervisor,2) as ValorSupervisor
									FROM C_Comision T0
									INNER JOIN C_Periodo T1 ON T1.IdPeriodo = T0.IdPeriodo
									WHERE T1.Estado = 1
									AND MONTH(T1.FechaInicial) = MONTH('".$desde."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$desde."') 
									AND MONTH(T1.FechaInicial) = MONTH('".$hasta."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$hasta."')
									AND T0.IdCategoria = ".$idCategoria."
									AND T0.IdGrupo = ".$idGrupo."
									AND T0.IdCanal = ".$idCanal."
									AND T1.Estado = 1
									AND T1.Tipo = 2");

			if ($queryComision->num_rows() > 0) {
				$comision = $queryComision->result_array()[0]["ValorSupervisor"];
				return $comision;
			}
		}
		if ($tipo == 5) {
			
			$queryComision = $this->db->query("SELECT TOP 1 round(T0.ValorImpulsadora,2) as ValorSupervisor
									FROM C_ImpulsadoraComision T0
									INNER JOIN C_ImpulsadoraPeriodo T1 ON T1.IdPeriodo = T0.IdPeriodo
									WHERE T1.Estado = 1
									AND MONTH(T1.FechaInicial) = MONTH('".$desde."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$desde."') 
									AND MONTH(T1.FechaInicial) = MONTH('".$hasta."') 
									AND YEAR(T1.FechaInicial) = YEAR('".$hasta."')
									AND t0.IdImpulsadora = ".$trabajador."
									AND T1.Estado = 1
									AND T1.Tipo = 4
									AND T0.ValorImpulsadora >0 ");
		

			if ($queryComision->num_rows() > 0) {
				$comision = $queryComision->result_array()[0]["ValorSupervisor"];
				return $comision;
			}

		}

		return $comision;
	}

	public function generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$bandera = null)
	{

		$SlpCode = null;

		if ($tipo == 1) {
			if ($trabajador != '' && $trabajador != '0') {

				$query = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$SlpCode = $query->result_array()[0]["IdRuta"];
			}

			return $this->Hana_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode,$bandera);
		}
		if ($tipo == 2) {
			if ($trabajador != '' && $trabajador != '0') {

				$query = $this->db->query("SELECT IdRuta from Usuarios where IdSupervisor = ".$trabajador);
				$SlpCode = $query->result_array()[0]["IdRuta"];
			}
			//echo $SlpCode;
			return $this->Hana_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode,$bandera);
		}
		if ($tipo == 3) {
			if ($trabajador != '' && $trabajador != '0') {

				$query = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$SlpCode = $query->result_array()[0]["IdRuta"];
			}

			return $this->Hana_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode,$bandera);	
		}
		if ($tipo == 4) {
			$SlpCode = null;

			return $this->Hana_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode,$bandera);	
		}
		if ($tipo == 5) {
			$SlpCode = null;

			return $this->Hana_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode,$bandera);	
		}
		if ($tipo == 6) {
			$SlpCode = null;
			return $this->Hana_model->generarReportePagoDevoluciones($tipo,$trabajador,$desde,$hasta,$SlpCode,$bandera);	
		}

	}

	public function usuarioCrea($desde,$hasta)
	{
		$query = $this->db->query("SELECT T1.*
			FROM C_Periodo T0
			INNER JOIN Usuarios T1 ON T1.IdUsuario = T0.IdUsuarioCrea
			INNER JOIN C_Comision T2 ON T2.IdPeriodo = T0.IdPeriodo
			WHERE MONTH(T0.FechaInicial) = MONTH('".$desde."') AND YEAR(T0.FechaInicial) = YEAR('".$desde."')
			AND MONTH(T0.FechaFinal) = MONTH('".$hasta."') AND YEAR(T0.FechaFinal) = YEAR('".$hasta."')");
		return $query->result_array();
	}

	public function usuarioActual()
	{
		$query = $this->db->query("SELECT * FROM Usuarios where IdUsuario = ".$this->session->userdata('id'));

		return $query->result_array();
	}

	public function traerEmpleadoPeriodo($var,$tipo)
	{
		$json = array();
		$i = 0;
		if ($tipo == 1) {
			$query = $this->db->query("SELECT * FROM Usuarios where IdRol = 4 AND Estado = 1 AND (Nombre LIKE '%".$var."%' or Apellidos like '%".$var."%' or Nombre_Usuario like '%".$var."%')");
			if($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $key) {            
	                $json[$i]["Id"] = $key["IdUsuario"];
	                $json[$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"] . " (".$key["Nombre_Usuario"].")";
	                $i++;
	            }
	            echo json_encode($json);
			}
		}

		if ($tipo == 2) {
			$query = $this->db->query("SELECT * from Usuarios
									where Estado = 1 and IdRol = 3
									and IdUsuario in (SELECT IdSupervisor FROM Usuarios WHERE IdRol = 4 AND Estado = 1) AND (Nombre LIKE '%".$var."%' or Apellidos like '%".$var."%' or Nombre_Usuario like '%".$var."%')");
			if($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $key) {            
	                $json[$i]["Id"] = $key["IdUsuario"];
	                $json[$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"];
	                $i++;
	            }
	            echo json_encode($json);
			}
		}
		if ($tipo == 3) {
			$query = $this->db->query("SELECT * FROM Usuarios
									WHERE Estado = 1 and IdRol = 20
									AND (Nombre LIKE '%".$var."%' or Apellidos like '%".$var."%' or Nombre_Usuario like '%".$var."%')"
								);

			if($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $key) {            
	                $json[$i]["Id"] = $key["IdUsuario"];
	                $json[$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"];
	                $i++;
	            }
	            echo json_encode($json);
			}
		}
		if ($tipo == 4) {
			$query = $this->db->query("SELECT * FROM Usuarios
									WHERE Estado = 1 and IdRol = 20
									AND (Nombre LIKE '%".$var."%' or Apellidos like '%".$var."%' or Nombre_Usuario like '%".$var."%')"
								);

			if($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $key) {            
	                $json[$i]["Id"] = $key["IdUsuario"];
	                $json[$i]["Nombre"] = $key["Nombre"]." ".$key["Apellidos"];
	                $i++;
	            }
	            echo json_encode($json);
			}
		}
	}
}
?>
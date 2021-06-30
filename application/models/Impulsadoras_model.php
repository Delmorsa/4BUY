<?php
/**
 * 
 */
date_default_timezone_set("America/Managua");
class Impulsadoras_model extends CI_Model
{
	
	function __construct()
	{
		$this->load->database();
		$this->load->model("Login_model");		
	}
	public function traerImpulsadoras()
	{
		//$this->db->order_by("Nombre_Usuario",TRUE);
		//$query = $this->db->get_where("Usuarios", array("IdRol" => 20, "Estado" => 1));
		$query = $this->db->query("SELECT *,ISNULL(cast(Adelanto as int),0) monto_adelanto  FROM Usuarios WHERE IdRol in (20,21) and IdUsuario <> 2137 AND ESTADO = 1 order by Nombre_Usuario");
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return 0;
	}

	public function traerImpulsadorasValor($idPeriodo)
	{
		//$this->db->order_by("Nombre_Usuario",TRUE);
		//$query = $this->db->get_where("Usuarios", array("IdRol" => 20, "Estado" => 1));
		$query = $this->db->query("SELECT *,ISNULL(cast(Adelanto as int),0) monto_adelanto  FROM Usuarios WHERE IdRol = 20 AND ESTADO = 1 order by Nombre_Usuario");
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		return 0;
	}

	public function traerJefeImpulsadorasValor($idPeriodo)
	{		
		$query = $this->db->query("SELECT t0.*,isnull(t1.ValorImpulsadora,0) ValorImpulsadora
									FROM Usuarios t0
									left join C_JefeImpulsadoraComision t1 on t1.IdImpulsadora = t0.IdUsuario
									WHERE t0.IdRol = 21 AND t0.ESTADO = 1 order by t0.Nombre_Usuario");
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

		$query = $this->db->query("SELECT T0.*,t1.Nombre_Usuario FROM C_Periodo t0 
									LEFT JOIN usuarios t1 on t1.IdUsuario = t0.IdUsuario AND t1.estado = 1 AND T0.Estado = 1
									ORDER BY FechaFinal ASC");

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;
	}

	
	public function traerClientesImpulsadoras($idImpulsadora)
	{		
		$query = $this->db->query("SELECT t0.* 
										FROM C_ClientesImpulsadoras t0										
										where t0.IdImpulsadora = ".$idImpulsadora." and t0.Estado = 1");
       
        $json = array();
        $i = 0;
        if ($query->num_rows() > 0) {            
            foreach ($query->result_array() as $key) {
                $json["data"][$i]["IdCliente"] = $key["IdCliente"];
                $json["data"][$i]["Nombre"] = $key["Nombre"];
                $json["data"][$i]["Opcion"] = '
                <a href="javascript:void(0)" class="btn btn-xs btn-danger rowDelete"><i class="fa fa-trash-o"></i></a>';
                $i++;
            }
            echo json_encode($json);
            return;
        }
        echo 0;
        return;

	}

	public function traerDetallePeriodo($id)
	{		
		$query = $this->db->query("with tabla as (
									SELECT t0.IdUsuario, T0.Nombre,T0.Apellidos, 0 as valor
									FROM Usuarios T0
									--LEFT JOIN C_ImpulsadoraComision T1 ON T1.IdImpulsadora = T0.IdUsuario
									--left JOIN C_ImpulsadoraPeriodo T2 ON T2.IdPeriodo = T1.IdPeriodo
									WHERE t0.IdRol = 20
									AND T0.Estado = 1 
									AND T0.IdUsuario NOT IN (SELECT IdImpulsadora FROM C_ImpulsadoraComision WHERE IdPeriodo = ".$id.")

									union

									SELECT t0.IdUsuario, T0.Nombre,T0.Apellidos, isnull(t1.ValorImpulsadora,0) as valor
									FROM Usuarios T0
									inner JOIN C_ImpulsadoraComision T1 ON T1.IdImpulsadora = T0.IdUsuario
									inner JOIN C_ImpulsadoraPeriodo T2 ON T2.IdPeriodo = T1.IdPeriodo
									WHERE t0.IdRol = 20
									and T2.IdPeriodo = ".$id."
									and t2.Estado = 1
									AND T0.Estado = 1
									) select * from tabla
									order by nombre,Apellidos");
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}		

		return 0;
	}


	public function traerDetallePeriodoJefeImpulsadoras($id)
	{		
		$query = $this->db->query("with tabla as (
									SELECT t0.IdUsuario, T0.Nombre,T0.Apellidos, 0 as valor
									FROM Usuarios T0
									--LEFT JOIN C_ImpulsadoraComision T1 ON T1.IdImpulsadora = T0.IdUsuario
									--left JOIN C_ImpulsadoraPeriodo T2 ON T2.IdPeriodo = T1.IdPeriodo
									WHERE t0.IdRol = 21
									AND T0.Estado = 1 
									AND T0.IdUsuario NOT IN (SELECT IdImpulsadora FROM C_ImpulsadoraComision WHERE IdPeriodo = ".$id.")

									union

									SELECT t0.IdUsuario, T0.Nombre,T0.Apellidos, isnull(t1.ValorImpulsadora,0) as valor
									FROM Usuarios T0
									inner JOIN C_ImpulsadoraComision T1 ON T1.IdImpulsadora = T0.IdUsuario
									inner JOIN C_ImpulsadoraPeriodo T2 ON T2.IdPeriodo = T1.IdPeriodo
									WHERE t0.IdRol = 21
									and T2.IdPeriodo = ".$id."
									and t2.Estado = 1
									AND T0.Estado = 1
									) select * from tabla
								order by nombre,Apellidos");
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
			FROM C_ImpulsadoraPeriodo T0
			INNER JOIN Usuarios T1 ON T1.IdUsuario = T0.IdUsuarioCrea
			LEFT JOIN Usuarios T2 ON T2.IdUsuario = T0.IdUsuarioEdita
			WHERE IdPeriodo = ".$id);

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}

		return 0;	
	}


	public function GuardarClientesImpulsadoras($idImpulsadora,$detalle)
	{
		$existe = $this->db->query("SELECT * FROM Usuarios WHERE IdUsuario = ".$idImpulsadora." and Estado = 1");
		$inserto=null;$update=null;
		if ($existe->num_rows()>0) {

			$this->db->trans_begin();
			//inactivo todos los clientes para volver a activarlos
			$update = $this->db->query("UPDATE C_ClientesImpulsadoras SET Estado = 0 WHERE IdImpulsadora = ".$idImpulsadora."");

			if ($update) {//si todo sale bien reactivo el estado si existe o ingreso la nueva fila

				$det = json_decode($detalle, true);

				foreach ($det as $obj) {
					//echo "SELECT * FROM C_RutaCanal WHERE IdCanal = ".$idImpulsadora." and IdRuta = ".$obj[0]."";
					$existe = $this->db->query("SELECT * FROM C_ClientesImpulsadoras WHERE IdImpulsadora <> ".$idImpulsadora." and IdCliente = ".$obj[0]." and Estado = 1");
					if ($existe->num_rows()>0) {
						$mensaje[0]["mensaje"] = "El Cliente: ".$obj[1]." esta asignada a otra impulsadora, eliminelo de la otra lista para continuar";
						$mensaje[0]["tipo"] = "error";
						echo json_encode($mensaje);
						$this->db->trans_rollback();
						return;
					}

					$existe = $this->db->query("SELECT * FROM C_ClientesImpulsadoras WHERE IdImpulsadora = ".$idImpulsadora." and IdCliente = ".$obj[0]."");
					if ($existe->num_rows()>0) {
						$update = array(
							"IdUsuarioEdita" => $this->session->userdata("id"),
							"FechaEdita" =>	gmdate(date("Y-m-d H:i:s")),
							"Nombre" => $obj[1],
							"Estado" => 1
						);
						$this->db->where('IdImpulsadora',$idImpulsadora);
						$this->db->where('IdCliente',$obj[0]);

						$update = $this->db->update('C_ClientesImpulsadoras',$update);
					}else{
						$insert = array(
							"IdCliente" => $obj[0],
							"Nombre" => $obj[1],
							"IdImpulsadora" => $idImpulsadora,
							"IdUsuarioCrea" => $this->session->userdata("id"),
							"FechaCrea" => 	gmdate(date("Y-m-d H:i:s")),
							"Estado" => 1
						);

						$update = $this->db->insert('C_ClientesImpulsadoras',$insert);
					}
				}
				if ($inserto || $update) {
					$mensaje[0]["mensaje"] = "Datos guardados correctamente";
					$mensaje[0]["tipo"] = "success";
					$this->db->trans_commit();
					echo json_encode($mensaje);
					return;
				}

				$mensaje[0]["mensaje"] = "Ha ocurrido un error inesperado";
				$mensaje[0]["tipo"] = "error";
				echo json_encode($mensaje);
				$this->db->trans_rollback();
				return;
			}
		}

		$mensaje[0]["mensaje"] = "No se pudo editar, ya que esta impulsadora se encuentra inactiva o no existe";
		$mensaje[0]["tipo"] = "error";
		echo json_encode($mensaje);
		$this->db->trans_rollback();
		return;
		
	}

	public function GuardarAdelantoImpulsadoras($adelanto,$idImpulsadora)
	{
		$existe = $this->db->query("SELECT * FROM Usuarios WHERE IdUsuario = ".$idImpulsadora." and Estado = 1");
		if ($existe->num_rows()>0) {

			//echo $idImpulsadora; return;
			$this->db->trans_begin();
						
			$update = array(
				"Adelanto" => $adelanto
			);
			$this->db->where('IdUsuario',$idImpulsadora);
			$update = $this->db->update('Usuarios',$update);


			if ($this->db->trans_status() === FALSE)
			{
				$mensaje[0]["mensaje"] = "No se pudo editar, Intentelo de nuevo";
				$mensaje[0]["tipo"] = "error";
				echo json_encode($mensaje);
				$this->db->trans_rollback();			    
			    return;
			}
			else
			{
				$mensaje[0]["mensaje"] = "Datos guardados";
				$mensaje[0]["tipo"] = "success";
				echo json_encode($mensaje);								
				$this->db->trans_commit();
				return;
			}
		}

		$mensaje[0]["mensaje"] = "No se pudo editar, ya que esta impulsadora se encuentra inactiva o no existe";
		$mensaje[0]["tipo"] = "error";
		echo json_encode($mensaje);
		$this->db->trans_rollback();
		return;
	}

	public function EditarValorImpulsadora($valor,$idperiodo,$idusuario,$idCategoria)
	{
		$this->db->trans_begin();
		$mensaje = array();

		date_default_timezone_set("America/Managua");

		$existe = $this->db->query("SELECT *,DATEDIFF(day, FechaFinal, GETDATE()) diferencia FROM C_ImpulsadoraPeriodo WHERE IdPeriodo = ".$idperiodo." AND Estado = 1");

		if ($existe->num_rows()==0) {
			$mensaje[0]["mensaje"] = "No se pudo editar el valor, el periodo esta inactivo o no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		$existe = $this->db->query("SELECT * FROM Usuarios WHERE IdUsuario = ".$idusuario." AND Estado = 1");

		if ($existe->num_rows()==0) {
			$mensaje[0]["mensaje"] = "No se pudo editar el valor, la impulsadora esta inactiva o no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		//validacion si es de un mes anterior 
		/*if ($existe->result_array()[0]["diferencia"]>2) {
			$mensaje[0]["mensaje"] = "No se pudo editar el valor de un periodo despues de 48 horas despues de su finalización ";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;	
		}*/

		$existe = $this->db->query("SELECT * FROM C_ImpulsadoraComision WHERE IdPeriodo = ".$idperiodo." AND IdImpulsadora = ".$idusuario." AND IdCategoria = ".$idCategoria." AND Estado = 1");

		if ($existe->num_rows()>0) {
			$encabezado = array(
				"ValorImpulsadora" => $valor,
				"IdUsuarioEdita" => $this->session->userdata("id"),
				"FechaEdita" =>	gmdate(date("Y-m-d H:i:s"))
			);

			$this->db->where('IdPeriodo',$idperiodo);
			$this->db->where('IdImpulsadora',$idusuario);
			$this->db->where('IdCategoria',$idCategoria);
			$inserto = $this->db->update("C_ImpulsadoraComision",$encabezado);

		}else{
			$encabezado = array(
				"IdPeriodo" => $idperiodo,
				"IdImpulsadora" => $idusuario,
				"IdCategoria" => $idCategoria,
				"ValorImpulsadora" => $valor,
				"Estado" => 1,
				"IdUsuarioCrea" => $this->session->userdata("id"),
				"FechaCrea" => gmdate(date("Y-m-d H:i:s"))
			);

			$inserto = $this->db->insert("C_ImpulsadoraComision",$encabezado);
		}


		if ($inserto) {
			$mensaje[0]["mensaje"] = "Datos editados correctamente";
			$mensaje[0]["tipo"] = "success";
			$this->db->trans_commit();
			echo json_encode($mensaje);
			return;
		}else{
			$mensaje[0]["mensaje"] = "No se pudo editar el valor intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
	}
	
	public function EditarValorJefeImpulsadora($valor,$idperiodo,$idusuario)
	{
		$this->db->trans_begin();
		$mensaje = array();

		date_default_timezone_set("America/Managua");

		$existe = $this->db->query("SELECT *,DATEDIFF(day, FechaFinal, GETDATE()) diferencia FROM C_ImpulsadoraPeriodo WHERE IdPeriodo = ".$idperiodo." AND Estado = 1");

		if ($existe->num_rows()==0) {
			$mensaje[0]["mensaje"] = "No se pudo editar el valor, el periodo esta inactivo o no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		$existe = $this->db->query("SELECT * FROM Usuarios WHERE IdUsuario = ".$idusuario." AND Estado = 1");

		if ($existe->num_rows()==0) {
			$mensaje[0]["mensaje"] = "No se pudo editar el valor, la jefa de impulsadora esta inactiva o no existe";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}	

		$existe = $this->db->query("SELECT * FROM C_JefeImpulsadoraComision WHERE IdPeriodo = ".$idperiodo." AND IdImpulsadora = ".$idusuario." AND Estado = 1");

		if ($existe->num_rows()>0) {
			$encabezado = array(
				"ValorImpulsadora" => $valor,
				"IdUsuarioEdita" => $this->session->userdata("id"),
				"FechaEdita" =>	gmdate(date("Y-m-d H:i:s"))
			);

			$this->db->where('IdPeriodo',$idperiodo);
			$this->db->where('IdImpulsadora',$idusuario);
			//$this->db->where('IdCategoria',$idCategoria);
			$inserto = $this->db->update("C_JefeImpulsadoraComision",$encabezado);

		}else{
			$encabezado = array(
				"IdPeriodo" => $idperiodo,
				"IdImpulsadora" => $idusuario,
				//"IdCategoria" => $idCategoria,
				"ValorImpulsadora" => $valor,
				"Estado" => 1,
				"IdUsuarioCrea" => $this->session->userdata("id"),
				"FechaCrea" => gmdate(date("Y-m-d H:i:s"))
			);

			$inserto = $this->db->insert("C_JefeImpulsadoraComision",$encabezado);
		}


		if ($inserto) {
			$mensaje[0]["mensaje"] = "Datos editados correctamente";
			$mensaje[0]["tipo"] = "success";
			$this->db->trans_commit();
			echo json_encode($mensaje);
			return;
		}else{
			$mensaje[0]["mensaje"] = "No se pudo editar el valor intentelo nuevamente";
			$mensaje[0]["tipo"] = "error";
			echo json_encode($mensaje);
			$this->db->trans_rollback();
			return;
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
		}
	}

	public function traerComisionImpulsadora($idImpulsadora,$idCategoria,$idperiodo)
	{
		$mensaje = array();
		
		$valor = $this->db->query("SELECT  ISNULL(ValorImpulsadora,0) ValorImpulsadora 
									from C_ImpulsadoraComision
									where Estado = 1
									and IdImpulsadora = $idImpulsadora
									and idCategoria = $idCategoria
									and IdPeriodo = $idperiodo");
		//echo $valor->num_rows();
		if ($valor->num_rows() > 0) {
			$mensaje[0]["valor"] = $valor->result_array()[0]["ValorImpulsadora"];
			echo json_encode($mensaje);
			return;
		}else{
			$mensaje[0]["valor"] = 0;
			echo json_encode($mensaje);
			return;
		}
	}


	public function printReporteImpulsadorasEspecial($tipo,$trabajador,$desde,$hasta,$bandera = null,$consolidado = null)
	{
		
		$json = array();
        $i = 0;
        
		if ($tipo == 5) {//comision  de vendedores
			$and = '';
		
			
			if ($trabajador != null && $trabajador != 0) {
				
				$idRuta = $this->db->query("SELECT IdRuta from Usuarios where IdUsuario = ".$trabajador);
				$and = " and T1.IdRuta = ".$idRuta->result_array()[0]["IdRuta"];
			}

			$trabajador = $this->db->query("SELECT IdUsuario FROM Usuarios where IdRuta = 2");///agregar mas rutas si se necesita
			$trabajador = $trabajador->result_array()[0]["IdUsuario"];

			$canales = $this->db->query("SELECT distinct t0.IdCanal ,t0.Nombre as Canal 
											FROM C_Canal T0
											inner join C_RutaCanal t1 on t1.IdCanal = t0.IdCanal
											WHERE T0.Estado = 1".$and);
		
			
			$impulsadora = $this->db->query("SELECT * from usuarios where IdSupervisora = 2137");

			$this->db->trans_begin();

			$this->db->query("TRUNCATE TABLE C_TempV");

			$ventas = $this->Periodos_model->traerVentas($trabajador,$desde,$hasta);
			//echo json_encode($ventas);

			foreach ($canales->result_array() as $keyCanales) {
				foreach ($ventas as $key) {
					//if ($trabajador != null) {
					if ($keyCanales["IdCanal"] == $key["IdCanal"]) {

						$comision = 0.05;
						//$comision = $this->Periodos_model->obtenerComision(5,$impulsadora->result_array()[0]["IdUsuario"],$desde,$hasta,null,null,null);
						//$comision = $this->db->query("SELECT * FROM Usuarios WHERE IdUsuario = ".$impulsadora->result_array()[0]["IdUsuario"]);

						//echo "SELECT * FROM Usuarios WHERE IdUsuario = ".$impulsadora->result_array()[0]["IdUsuario"];
						/*echo json_encode($comision->result_array());return;	

						if ($comision->num_rows()>0) {
							$comision = $comision->result_array()[0]["Adelanto"];
						}*/

						//echo $comision;
						if ($comision > 0) {
							$devolucion = number_format($this->Hana_model->getDevolucion(1,$key["CODVENDEDOR"],$desde,$hasta,$key["IdCategoria"],$key["IdGrupo"]),2);

							$calculo = $this->db->query("SELECT round((".$key["Libras"]." - ".$devolucion.") * ".$comision.",2) as calculo");
							//echo $key["Libras"]."<br>";

				                $insert = array(
									"IdUsuario" => $impulsadora->result_array()[0]["IdUsuario"], 
									"Nombre" => $impulsadora->result_array()[0]["Nombre_Usuario"].' '.$impulsadora->result_array()[0]["Apellidos"],
									"IdCategoria" => $key["IdCategoria"],
									"Categoria" => $key["Categoria"],
									"IdGrupo" => $key["IdGrupo"],
									"Grupo" => $key["Grupo"],
									"IdCanal" => $key["IdCanal"],
									"Libras" => $key["Libras"],
									"Devolucion" => $devolucion,
									"Comision" =>  $comision,
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
							SELECT IdUsuario,Nombre,SUM(Devolucion) Devolucion, SUM(Libras) - SUM(Devolucion)TotalLibras,(SUM(Libras) - SUM(Devolucion)) * comision Total
							from c_tempv
							group by IdUsuario,Nombre,Comision
							)
							SELECT T1.IdRuta, T0.IdUsuario,T0.Nombre,sum(Devolucion) Devolucion,sum(T0.TotalLibras) TotalLibras,sum(T0.Total)Total
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
			if ($query->num_rows() > 0 && $consolidado != null) {
				foreach ($query->result_array() as $key) {
					$json["data"][$i]["IdUsuario"] = $key["IdUsuario"];
					$json["data"][$i]["IdRuta"] = $key["IdRuta"];
					$json["data"][$i]["Nombre"] = utf8_encode($key["Nombre"]);					
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

	}

	
}
?>
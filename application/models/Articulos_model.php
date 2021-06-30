<?php
/**
 * Created by PhpStorm.
 * User: Sistemas
 * Date: 28/1/2019
 * Time: 07:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulos_model extends CI_Model
{
	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	
	public function getArticulos()
	{
		
		$query = $this->db->query("SELECT t0.*,t1.Nombre as Grupo,t2.Nombre as Categoria FROM C_Productos t0 
									inner join C_Grupos t1 on T1.IdGrupo = T0.IdGrupo
									inner join C_Categorias t2 on t2.IdCategoria = T0.IdCategoria");

		if ($query->num_rows() > 0){
			return $query->result_array();
		}
		return 0;
	}


	public function verReporteInventario($id)
	{
		$query = "SELECT t0.Desde,t0.Hasta, t0.IDCongelacion,t0.CodBodega,t0.FechaCrea,t0.DescBodega, t1.Codigo,t1.Descripcion,t1.CodCategoria,t1.Categoria,
			t1.Lote lote,
			sum(isnull(t1.Existencia,0)) Existencia,
			isnull((SELECT SUM(CANTIDAD)  FROM Escaneos where codproducto = t1.Codigo and (lote = t1.Lote or lote = '') and idcongelacion = t0.IDCongelacion),0) AS CantEscaneo,t2.Nombre,t2.Apellidos,
			case when t1.costo <> 0 then t1.costo else isnull((select costo from Escaneos WHERE
			codproducto = t1.Codigo 
			AND ( lote = t1.Lote OR lote = '' ) and idcongelacion = t0.IDCongelacion),0)  end as costo
			FROM Congelaciones t0
			inner join CongelacionDetalle t1 on t1.IDCongelacion = t0.IDCongelacion
			inner join usuarios t2 on t2.IdUsuario = t0.IdUsuarioCrea
			WHERE T0.IDCongelacion = ".$id."
			group by t1.Lote,t0.IDCongelacion,t0.FechaCrea,t0.CodBodega,t0.DescBodega,t0.Desde,t0.Hasta, t1.Codigo,t1.Descripcion,t1.CodCategoria,t1.Categoria,t2.Nombre,t2.Apellidos,t1.costo

			UNION

			SELECT t0.Desde,t0.Hasta,t0.IDCongelacion,t0.CodBodega,t0.FechaCrea,t0.DescBodega, t1.codproducto,t1.Descripcion,t1.CodCategoria,t1.Categoria,
			t1.Lote,
			0 Existencia,
			sum(isnull(t1.cantidad,0)) AS CantEscaneo,t2.Nombre,t2.Apellidos,isnull(t1.costo,0) as costo
			FROM Congelaciones t0
			inner join Escaneos t1 on t1.IDCongelacion = t0.IDCongelacion
			inner join usuarios t2 on t2.IdUsuario = t0.IdUsuarioCrea
			WHERE T0.IDCongelacion = ".$id."
			and t1.codproducto not in (select codigo from CongelacionDetalle where idcongelacion = ".$id.")
			and t1.lote not in (select lote from CongelacionDetalle where idcongelacion = ".$id." and lote is not null)
			group by t1.Lote,t0.IDCongelacion,t0.FechaCrea,t0.CodBodega,t0.Desde,t0.Hasta,t0.DescBodega,t1.codproducto,t1.Descripcion,t1.CodCategoria,t1.Categoria,t2.Nombre,t2.Apellidos,t1.costo";
		//echo $query;
		$query = $this->db->query($query);

		if ($query->num_rows()>0) {
			return $query->result_array();
		}
		return 0;
	}
}

/* End of file .php */

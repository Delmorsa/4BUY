<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function guardarInventario($encabezado,$datos)
  {
    $this->db->trans_begin();
    $permiso = $this->Autorizaciones_model->validarPermiso($this->session->userdata("id"), "1040");
    if($permiso){
      date_default_timezone_set("America/Managua");
      $bandera = false; $remove = false; $mensaje = array();
      $encabezadoQuery = $this->db->query("SELECT IdInventario,cast(Fecha as date) Fecha,Tipo,IdUsuarioCrea,IdUsuarioEdita,
                                           FechaCrea,FechaEdita,Estado FROM InventarioFisico
                                           WHERE CAST(Fecha AS Date) = '".$encabezado[0]."'
                                           AND Tipo = '".$encabezado[1]."' ");

      if($encabezadoQuery->num_rows() > 0){
        $this->db->where("cast(Fecha as date) = ", $encabezado[0]);
        $this->db->where("Tipo",$encabezado[1]);
        $update = array(
          "IdUsuarioEdita" => $this->session->userdata("id"),
          "FechaEdita" => date("Y-m-d H:i:s"),
        );
        $actualizar = $this->db->update("InventarioFisico", $update);
        if($actualizar){
          $delete = $this->db->where("IdInventario",$encabezadoQuery->result_array()[0]["IdInventario"])
                             ->delete("InventarioDetalle");
                             if(!$delete){
                               $mensaje[0]["mensaje"] = "Error inesperado en el servidor. No se pudo actualizar la informacion cod(1-DET).
                                                         Pongase en contacto con el administrador";
                               $mensaje[0]["tipo"] = "error";
                               echo json_encode($mensaje);
                             }
          $bandera = true;
        }else{
          $mensaje[0]["mensaje"] = "Error inesperado en el servidor. No se pudo actualizar la informacion cod(1-ENC)
          Pongase en contacto con el administrador";
          $mensaje[0]["tipo"] = "error";
          echo json_encode($mensaje);
        }

      }else{
        $insert = array(
          "Fecha" => date_format(new DateTime($encabezado[0]), "Y-m-d H:i:s"),
          "Tipo" => $encabezado[1],
          "IdUsuarioCrea" => $this->session->userdata("id"),
          "FechaCrea" => date("Y-m-d H:i:s"),
          "Estado" => "A"
        );
        $guardar = $this->db->insert("InventarioFisico", $insert);
        if($guardar){
          $bandera = true;
        }else{
          $mensaje[0]["mensaje"] = "Error inesperado en el servidor. No se pudo almacenar la informacion cod(2-ENC).
          Pongase en contacto con el administrador";
          $mensaje[0]["tipo"] = "error";
          echo json_encode($mensaje);
        }

      }

      if($bandera){
        $id = $this->db->query("SELECT IdInventario FROM InventarioFisico
                                             WHERE CAST(Fecha AS Date) = '".$encabezado[0]."'
                                             AND Tipo = '".$encabezado[1]."'
                                             AND IdUsuarioCrea = '".$this->session->userdata("id")."' ");
        $gramos = 0; $unidad_libras = 0; $libras = 0; $desc = "";
        $det = json_decode($datos,true);
        foreach ($det as $array) {
            if($array[2] == "-" || $array[2] == ""){
              $gramos = 0;
            }else{
              $gramos = str_replace(",","",$array[2]);
            }
            if($array[3] == "-" || $array[3] == ""){
              $unidad_libras = 0;
            }else{
              $unidad_libras = str_replace(",","",$array[3]);
            }
            if($array[4] == "-" || $array[4] == ""){
              $libras = 0;
            }else{
              $libras = str_replace(",","",$array[4]);
            }
            $insertArray = array(
              "IdInventario" => $id->result_array()[0]["IdInventario"],
              "Codigo" => $array[0],
              "Descripcion" => $array[1],
              "Gramos" => $gramos,
              "Unidad_Libras" => $unidad_libras,
              "Libras" => $libras,
              "Estado" => "A",
              "Categoria" => $array[5]
            );

            $eliminar = $this->db->insert("InventarioDetalle",$insertArray);

            if($eliminar){
              $remove = true;
            }else{
              $remove = false;
            }
        }

        if($remove){
          $mensaje[0]["mensaje"] = "Datos almacenados con exito";
          $mensaje[0]["tipo"] = "success";
          echo json_encode($mensaje);
        }
      }
    }else{
      $mensaje[0]["mensaje"] = "No tienes permiso para realizar esta operacion";
      $mensaje[0]["tipo"] = "error";
      echo json_encode($mensaje);
    }

    if ($this->db->trans_status() === FALSE)
    {
       $this->db->trans_rollback();
    } else {
      $this->db->trans_commit();
    }

  }



}

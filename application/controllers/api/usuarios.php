<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Usuarios extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
    }

    public function obtener_get($id = 0)
    {
        $usuarios = [];
        if( $id > 0 )
            $this->db->where('id', $id);

        $usuarios = $this->db->get('usuarios')->result_array();
        if( !empty($usuarios)){
            $this->set_response([
                'status' => TRUE,
                'message' => '',
                'result' => $usuarios
            ], REST_Controller::HTTP_OK); // OK (200) begin the HTTP response code

        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Usuarios no encontrados',
                'result' => []
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) begin the HTTP response code
        }
    }
    
    public function eliminar_delete($id)
    {
        // Especificamos sobre que id se va a realizar la accion de eliminar
        $this->db->where('id', $id);

        //ejecutamos el comando sobre la tabla usuarios
        $usuarios = $this->db->delete('usuarios');
        $this->set_response([
            'id' => $id,
            'message' => 'Registro eliminado',
            'result' => []
        ], REST_Controller::HTTP_NO_CONTENT); 
    }
    
    public function insertar_post()
    {
        // Obtenermos la informacion proveniento del cuerpo del mensaje
        $data = file_get_contents("php://input");
        //Decodificamos a formato JSON
        $usuario = json_decode($data);

        //Insertamos los datos
        $this->db->insert('usuarios',$usuario);

        // Obtenemos el último id registrado en la conexión actual
        $usuario->id = $this->db->insert_id();

        // Retornamos el registro insertado junto con el id correspondiente
        $this->set_response(
            $usuario,
            REST_Controller::HTTP_CREATED
        ); 
    }
    
    public function actualizar_put()
    {
        // Obtenermos la informacion proveniento del cuerpo del mensaje
        $data = file_get_contents("php://input");

        //Decodificamos a formato JSON
        $usuario = json_decode($data);

        // Especificamos sobre que id se va a realizar la accion de actualizar
        $this->db->where('id', $usuario->id);

        //Actualizamos los datos
        $this->db->update('usuarios',$usuario);

        // Retornamos elid  del registro actualizado 
        $this->set_response([
            'id' => $usuario->id,
            'message' => 'Registro actualizado',
            'result' => []
        ], REST_Controller::HTTP_OK); 
    }
}
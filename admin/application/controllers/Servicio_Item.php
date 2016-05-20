<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio_Item extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/servicio_item_model','servicio_item');
		$this->load->model('ABM/servicio_model','servicio');

		$this->load->helper('url');		
	}


	public function index()
	{
		$datos_vista = array();

		$datos_vista["servicios"] = $this->servicio->get_all();

		$this->load->view('ABM/servicio_item_view', $datos_vista);
	}


	public function ajax_list()
	{
		$list = $this->servicio_item->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $servicio_item) {
			$no++;
			$row = array();

			$row[] = $servicio_item->nombre;
			$row[] = $servicio_item->servicio;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_servicio_item('."'".$servicio_item->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_servicio_item('."'".$servicio_item->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->servicio_item->count_all(),
						"recordsFiltered" => $this->servicio_item->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->servicio_item->get_by_id($id);

		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		$data = array(
				'servicio_item_nombre' => $this->input->post('nombre'),
				'servicio_item_id_servicio' => $this->input->post('servicio'),
			);

		$insert = $this->servicio_item->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'servicio_item_nombre' => $this->input->post('nombre'),
				'servicio_item_id_servicio' => $this->input->post('servicio'),				
			);

		$this->servicio_item->update(array('servicio_item_id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{
		$this->servicio_item->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate($add = false)
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if(trim($this->input->post('nombre')) == '')
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ingrese un Nombre';
			$data['status'] = FALSE;
		}		

		// Valida que no exista un registro con el mismo nombre
		if ($add)
			$duplicated = $this->servicio_item->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->servicio_item->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

		if ($duplicated > 0)
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ya existe un registro con ese nombre';
			$data['status'] = FALSE;
		}	

        if($this->input->post('servicio') == '')
		{
			$data['inputerror'][] = 'servicio';
			$data['error_string'][] = 'Seleccione un servicio';
			$data['status'] = FALSE;
		}		


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}

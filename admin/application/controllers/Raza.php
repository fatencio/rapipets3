<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raza extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/raza_model','raza');
		$this->load->model('ABM/animal_model','animal');

		$this->load->helper('url');		
	}


	public function index()
	{
		$datos_vista = array();

		$datos_vista["animales"] = $this->animal->get_all();

		$this->load->view('ABM/raza_view', $datos_vista);
	}


	public function ajax_list()
	{
		$list = $this->raza->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $raza) {
			$no++;
			$row = array();

			$row[] = $raza->nombre;
			$row[] = $raza->animal_asignado;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_raza('."'".$raza->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_raza('."'".$raza->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->raza->count_all(),
						"recordsFiltered" => $this->raza->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->raza->get_by_id($id);

		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		$data = array(
				'raza_nombre' => $this->input->post('nombre'),
				'raza_id_animal' => $this->input->post('animal_asignado'),
			);

		$insert = $this->raza->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'raza_nombre' => $this->input->post('nombre'),
				'raza_id_animal' => $this->input->post('animal_asignado'),				
			);

		$this->raza->update(array('raza_id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_delete($id)
	{
		$this->raza->delete_by_id($id);
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
			$duplicated = $this->raza->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->raza->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

		if ($duplicated > 0)
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ya existe un registro con ese nombre';
			$data['status'] = FALSE;
		}	

        if($this->input->post('animal_asignado') == '')
		{
			$data['inputerror'][] = 'animal_asignado';
			$data['error_string'][] = 'Seleccione un animal';
			$data['status'] = FALSE;
		}		


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}

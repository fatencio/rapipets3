<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Animal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/animal_model','animal');
	}


	public function index()
	{
		$this->load->helper('url');
		$this->load->view('ABM/animal_view');
	}


	public function ajax_list()
	{
		$list = $this->animal->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $animal) {
			$no++;
			$row = array();
			$row[] = $animal->nombre;

			if ($animal->conraza == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';

			if ($animal->contamanios == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_animal('."'".$animal->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_animal('."'".$animal->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->animal->count_all(),
						"recordsFiltered" => $this->animal->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->animal->get_by_id($id);
	//	$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		$data = array(
				'animales_nombre' => $this->input->post('nombre'),
				'animales_conraza' => $this->input->post('conraza'),
				'animales_contamanios' => $this->input->post('contamanios'),
			);

		$insert = $this->animal->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'animales_nombre' => $this->input->post('nombre'),
				'animales_conraza' => $this->input->post('conraza'),
				'animales_contamanios' => $this->input->post('contamanios'),
			);

		$this->animal->update(array('animales_id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->animal->delete_by_id($id);
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

		if($this->input->post('conraza') == '')
		{
			$data['inputerror'][] = 'conraza';
			$data['error_string'][] = 'Seleccione una opción';
			$data['status'] = FALSE;
		}

		if($this->input->post('contamanios') == '')
		{
			$data['inputerror'][] = 'contamanios';
			$data['error_string'][] = 'Seleccione una opción';
			$data['status'] = FALSE;
		}		

		// Valida que no exista un registro con el mismo nombre
		if ($add)
			$duplicated = $this->animal->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->animal->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

		if ($duplicated > 0)
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ya existe un registro con ese nombre';
			$data['status'] = FALSE;
		}	

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}

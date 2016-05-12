<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packaging extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/packaging_model','packaging');
	}


	public function index()
	{
		$this->load->helper('url');
		$this->load->view('ABM/packaging_view');
	}


	public function ajax_list()
	{
		$list = $this->packaging->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $packaging) {
			$no++;
			$row = array();

			$row[] = $packaging->nombre;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar"    onclick="edit_packaging('."'".$packaging->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_packaging('."'".$packaging->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->packaging->count_all(),
						"recordsFiltered" => $this->packaging->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->packaging->get_by_id($id);

        echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		$data = array(
				'packaging_nombre' => $this->input->post('nombre'),
			
			);

		$insert = $this->packaging->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'packaging_nombre' => $this->input->post('nombre'),
				
			);

		$this->packaging->update(array('packaging_id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->packaging->delete_by_id($id);
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
			$duplicated = $this->packaging->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->packaging->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

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

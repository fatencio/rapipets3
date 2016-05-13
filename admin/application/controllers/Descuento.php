<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Descuento extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/descuento_model','descuento');
	}


	public function index()
	{
		$this->load->helper('url');
		$this->load->view('ABM/descuento_view');
	}


	public function ajax_list()
	{
		$list = $this->descuento->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $descuento) {
			$no++;
			$row = array();
			$row[] = $descuento->nombre;
			$row[] = $descuento->porcentaje;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_descuento('."'".$descuento->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_descuento('."'".$descuento->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->descuento->count_all(),
						"recordsFiltered" => $this->descuento->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->descuento->get_by_id($id);
	    echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		$data = array(
				'descuento_nombre' => $this->input->post('nombre'),
				'descuento_porcentaje' => $this->input->post('porcentaje'),
			);

		$insert = $this->descuento->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'descuento_nombre' => $this->input->post('nombre'),
				'descuento_porcentaje' => $this->input->post('porcentaje'),
			);

		$this->descuento->update(array('descuento_id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->descuento->delete_by_id($id);
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

		if(trim($this->input->post('porcentaje')) == '')
		{
			$data['inputerror'][] = 'porcentaje';
			$data['error_string'][] = 'Ingrese un Porcentaje';
			$data['status'] = FALSE;
		}	


		// Valida que sea un número
		if(!is_numeric($this->input->post('porcentaje')))
		{
			$data['inputerror'][] = 'porcentaje';
			$data['error_string'][] = 'Porcentaje debe ser un número entre 0 y 100';
			$data['status'] = FALSE;
		}	

		// Valida que sea un número entero entre 0 y 100
		if(is_numeric($this->input->post('porcentaje')) && is_int(intval($this->input->post('porcentaje'))))
		{
			if (intval($this->input->post('porcentaje')) < 0 || intval($this->input->post('porcentaje')) > 100)
			{
				$data['inputerror'][] = 'porcentaje';
				$data['error_string'][] = 'Porcentaje debe ser un número entre 0 y 100';
				$data['status'] = FALSE;
			}
		}	

		// Valida que no exista un registro con el mismo nombre
		if ($add)
			$duplicated = $this->descuento->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->descuento->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

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

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rubro extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/rubro_model','rubro');
	}


	public function index()
	{
		$this->load->helper('url');
		$this->load->view('ABM/rubro_view');
	}


	public function ajax_list()
	{
		$list = $this->rubro->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $rubro) {
			$no++;
			$row = array();

			$row[] = $rubro->nombre;

			if ($rubro->conraza == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';

			if ($rubro->contamanios == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';	
				
			if ($rubro->conpresentacion == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';

			if ($rubro->conmarca == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';							
			if ($rubro->conedad == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';

			if ($rubro->conmedicados == 1)
				$row[] = '<td class="hidden-xs"><span class="label label-success">Si</span></td>';
			else
				$row[] = '<td class="hidden-xs"><span class="label label-danger">No</span></td>';	
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar"    onclick="edit_rubro('."'".$rubro->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_rubro('."'".$rubro->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->rubro->count_all(),
						"recordsFiltered" => $this->rubro->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->rubro->get_by_id($id);

        echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		$data = array(
				'rubro_nombre' => $this->input->post('nombre'),
				'rubro_conraza' => $this->input->post('conraza'),
				'rubro_contamanios' => $this->input->post('contamanios'),			
				'rubro_conpresentacion' => $this->input->post('conpresentacion'),
				'rubro_conmarca' => $this->input->post('conmarca'),		
				'rubro_conedad' => $this->input->post('conedad'),
				'rubro_conmedicados' => $this->input->post('conmedicados'),											
			);

		$insert = $this->rubro->save($data);

		echo json_encode(array("status" => TRUE));
	}


	public function ajax_update()
	{
		$this->_validate();

		$data = array(
				'rubro_nombre' => $this->input->post('nombre'),
				'rubro_conraza' => $this->input->post('conraza'),
				'rubro_contamanios' => $this->input->post('contamanios'),			
				'rubro_conpresentacion' => $this->input->post('conpresentacion'),
				'rubro_conmarca' => $this->input->post('conmarca'),		
				'rubro_conedad' => $this->input->post('conedad'),
				'rubro_conmedicados' => $this->input->post('conmedicados'),						
			);

		$this->rubro->update(array('rubro_id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->rubro->delete_by_id($id);
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
			$duplicated = $this->rubro->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->rubro->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

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

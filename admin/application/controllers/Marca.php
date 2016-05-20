<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marca extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ABM/marca_model','marca');
		$this->load->model('ABM/animal_model','animal');

		$this->load->helper('url');		
	}


	public function index()
	{
		$datos_vista = array();

		$datos_vista["animales"] = $this->animal->get_all();

		$this->load->view('ABM/marca_view', $datos_vista);
	}


	public function ajax_list()
	{
		$list = $this->marca->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $marca) {
			$no++;
			$row = array();

			$row[] = $marca->nombre;
			
		
	        $animales = null;
			$animales = $this->marca->get_by_id_nombre_animal ($marca->id);
			$lista_animales = '';

            foreach ($animales->animales as $animal) {
             
              $lista_animales .=  $animal->animal_nombre . ','; 
             
            }

            $lista_animales = trim($lista_animales, ','); //Eliminamos la última coma
            $row[] = $lista_animales;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_marca('."'".$marca->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_marca('."'".$marca->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->marca->count_all(),
						"recordsFiltered" => $this->marca->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit($id)
	{
		$data = $this->marca->get_by_id($id);

		// Convierte lista de animales a string para input select de la vista
		$animales_view = '';

		foreach ($data->animales as $animal) {
			$animales_view .= $animal->animal_id . ',';
		}

		$data->animales_view = rtrim($animales_view, ",");

		echo json_encode($data);
	}


	public function ajax_add()
	{
		$this->_validate(true);

		 $data = array(
				'marca_nombre' => $this->input->post('nombre'),
		 	);

         $id_insert = $this->marca->save($data);
         
         $data_animales = $this->input->post('animal_asignado');
         
         for($i = 0; $i < count($data_animales); $i++) {
           
            $data_animal = array(
         	    'marca_animal_marca_id' => $id_insert,
				'marca_animal_animal_id'=> (int)$data_animales[$i],
					
             );

         	$insert_animal =  $this->marca->save_animal($data_animal);
        
         }

      
         echo json_encode(array("status" => TRUE));
	
	}


	public function ajax_update()
	{
		 $this->_validate();

		 $data = array(
				'marca_nombre' => $this->input->post('nombre'),
		 	);

         $this->marca->update(array('marca_id' => $this->input->post('id')), $data);
         
         // Elimina animales asignados a la marca
         $this->marca->delete_marca_animal_by_id($this->input->post('id'));


         // Inserta animales asignados a la marca 
         $data_animales = $this->input->post('animal_asignado');
         
         for($i = 0; $i < count($data_animales); $i++) {
           
            $data_animal = array(
     	        'marca_animal_marca_id' => $this->input->post('id'),
				'marca_animal_animal_id'=> (int)$data_animales[$i],
				
            );

            $insert_animal =  $this->marca->save_animal($data_animal);
        
         }
      
         echo json_encode(array("status" => TRUE));

	}


	public function ajax_delete($id)
	{
		$this->marca->delete_by_id($id);

         // Elimina animales asignados a la marca
         $this->marca->delete_marca_animal_by_id($id);		

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
			$duplicated = $this->marca->check_duplicated(trim($this->input->post('nombre')));
		else
			$duplicated = $this->marca->check_duplicated_edit($this->input->post('id'), trim($this->input->post('nombre')));

		if ($duplicated > 0)
		{
			$data['inputerror'][] = 'nombre';
			$data['error_string'][] = 'Ya existe un registro con ese nombre';
			$data['status'] = FALSE;
		}	


		// TODO: averiguar si valida animal 

	//	$data_animales = $this->input->post('animal_asignado');
		/*
        if(true) //!isset($data_animales[1]))
		{
			$data['inputerror'][] = 'animal_asignado';
			$data['error_string'][] = 'Seleccione un animal';
			$data['status'] = FALSE;
		}		
		*/

      
        //if (empty($_POST["animal_asignado"])) {

      
		if($this->input->post('animal_asignado') == ''){
		
			$data['inputerror'][] = 'animal_asignado[]';
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

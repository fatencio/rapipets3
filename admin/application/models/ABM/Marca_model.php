<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marca_model extends CI_Model {

	var $table = 'marca';
	var $table_animal = 'marca_animal';
	var $view = 'v_marca_animal';

	var $column_order = array("marca_nombre", "marca_animales", null); 	// columnas con la opcion de orden habilitada
	var $column_search = array("marca_nombre", "marca_animales"); 		// columnas con la opcion de busqueda habilitada
	//var $column_having = "GROUP_CONCAT(animal_nombre SEPARATOR ', ')";

    var $order = array('marca_nombre' => 'asc'); // default order 


/*
	var $column_order_animal = array('marca_animal_animal_id', 'marca_animal_marca_id',null); 	// columnas con la opcion de orden habilitada
	var $column_search_animal = array('marca_animal_animal_id', 'marca_animal_marca_id'); // columnas con la opcion de busqueda habilitada


     var $order_animal = array('marca_animal_animal_id' => 'asc'); // default order 
*/


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	private function _get_datatables_query()
	{
		$this->db->select("marca_id as id, marca_nombre as nombre, GROUP_CONCAT(animal_nombre SEPARATOR ', ') as animales");
		$this->db->join('marca_animal', 'marca_id = marca_animal_marca_id');
		$this->db->join('animal', 'marca_animal_animal_id = animal_id');
        $this->db->from($this->view);
        $this->db->group_by('marca_id, marca_nombre'); 

		$i = 0;
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

/*
		if($_POST['search']['value'])
		{
		//	$this->db->having($this->column_having LIKE $_POST['search']['value']); 
			$this->db->having("GROUP_CONCAT(animal_nombre SEPARATOR ', ') LIKE '%" . $_POST['search']['value'] . "%'"); 
		}
	*/	
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}


	function get_datatables()
	{
		$this->_get_datatables_query();

		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		return $query->result();
	}


	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}


	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function get_by_id($id)
	{
		$data = new stdClass();

		$this->db->select('marca_id as id, marca_nombre as nombre');		
		$this->db->from($this->table);
		$this->db->where('marca_id',$id);
		$query = $this->db->get();

		$data = $query->row();

		// Recupera animales asociados a la marca
		$this->db->select('marca_animal_animal_id as animal_id');		
		$this->db->from($this->table_animal);
		$this->db->where('marca_animal_marca_id', $id);
		$query = $this->db->get();

		$data->animales = $query->result();

		return $data;
	}


	   public function get_by_id_nombre_animal($id)
	   {
		$data = new stdClass();

		$this->db->select('marca_id as id, marca_nombre as nombre');		
		$this->db->from($this->table);
		$this->db->where('marca_id',$id);
		$query = $this->db->get();

		$data = $query->row();

		// Recupera animales asociados a la marca
		
		$this->db->select('animal_nombre');		
		$this->db->from($this->table_animal);
		$this->db->join('animal', 'marca_animal_animal_id = animal_id');
		$this->db->where('marca_animal_marca_id', $id);
		$query = $this->db->get();


		$data->animales = $query->result();

		return $data;
	   }
        

	public function check_duplicated($name)
	{
		$this->db->from($this->table);
		$this->db->where('marca_nombre', $name);

		return $this->db->count_all_results();
	}


	public function check_duplicated_edit($id, $name)
	{
		$this->db->from($this->table);
		$this->db->where('marca_nombre', $name);
		$this->db->where('marca_id !=', $id);

		return $this->db->count_all_results();
	}	


	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

    public function save_animal($data)
	{
		$this->db->insert($this->table_animal, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	/*
    public function update_animal($where, $data)
	{
		$this->db->update($this->table_animal, $data, $where);
		return $this->db->affected_rows();
	}
	*/

	public function delete_by_id($id)
	{
		$this->db->where('marca_id', $id);
		$this->db->delete($this->table);
	}

	public function delete_marca_animal_by_id($id)
	{
		$this->db->where('marca_animal_marca_id', $id);
		$this->db->delete($this->table_animal);
	}	

}

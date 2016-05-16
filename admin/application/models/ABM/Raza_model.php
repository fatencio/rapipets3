<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raza_model extends CI_Model {

	var $table = 'raza';

	var $column_order = array('raza_nombre', 'animal_nombre', null); 	// columnas con la opcion de orden habilitada
	var $column_search = array('raza_nombre', 'animal_nombre'); 		// columnas con la opcion de busqueda habilitada

	var $order = array('raza_nombre' => 'asc'); // default order 


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	private function _get_datatables_query()
	{
         
		
		$this->db->select('raza_id as id, raza_nombre as nombre, animal_nombre as animal_asignado');
        $this->db->from($this->table);
		$this->db->join('animal', 'raza_id_animal = animal_id');

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
	
		$this->db->select('raza_id as id, raza_nombre as nombre, raza_id_animal');
		$this->db->from($this->table);
		$this->db->where('raza_id',$id);
		$query = $this->db->get();

		return $query->row();
	}


	public function check_duplicated($name)
	{
		$this->db->from($this->table);
		$this->db->where('raza_nombre', $name);

		return $this->db->count_all_results();
	}


	public function check_duplicated_edit($id, $name)
	{
		$this->db->from($this->table);
		$this->db->where('raza_nombre', $name);
		$this->db->where('raza_id !=', $id);

		return $this->db->count_all_results();
	}	


	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}


	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}


	public function delete_by_id($id)
	{
		$this->db->where('raza_id', $id);
		$this->db->delete($this->table);
	}

}
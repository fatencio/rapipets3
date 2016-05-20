<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rubro_model extends CI_Model {

	var $table = 'rubro';

	var $column_order = array('rubro_nombre','rubro_conraza','rubro_contamanios','rubro_conpresentacion','rubro_conmarca','rubro_conedad','rubro_conmedicados', null); 	// columnas con la opcion de orden habilitada
	var $column_search = array('rubro_nombre','rubro_conraza','rubro_contamanios','rubro_conpresentacion','rubro_conmarca','rubro_conedad','rubro_conmedicados'); 		// columnas con la opcion de busqueda habilitada

	var $order = array('rubro_nombre' => 'asc'); // default order 


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	private function _get_datatables_query()
	{
		
	    $this->db->select('rubro_id as id, rubro_nombre as nombre, rubro_conraza as conraza, rubro_contamanios as contamanios, rubro_conpresentacion as conpresentacion, rubro_conmarca as conmarca, rubro_conedad as conedad, rubro_conmedicados as conmedicados');
		$this->db->from($this->table);
		
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
		$this->db->select('rubro_id as id, rubro_nombre as nombre, rubro_conraza as conraza, rubro_contamanios as contamanios, rubro_conpresentacion as conpresentacion, rubro_conmarca as conmarca, rubro_conedad as conedad, rubro_conmedicados as conmedicados');		
		$this->db->from($this->table);
		$this->db->where('rubro_id',$id);
		$query = $this->db->get();

		return $query->row();
	}


	public function check_duplicated($name)
	{
		$this->db->from($this->table);
		$this->db->where('rubro_nombre', $name);

		return $this->db->count_all_results();
	}


	public function check_duplicated_edit($id, $name)
	{
		$this->db->from($this->table);
		$this->db->where('rubro_nombre', $name);
		$this->db->where('rubro_id !=', $id);

		return $this->db->count_all_results();
	}	


	public function save($data)
	{
		$data['rubro_conraza'] = (is_null($data['rubro_conraza']) ? 0 : 1);
		$data['rubro_contamanios'] = (is_null($data['rubro_contamanios']) ? 0 : 1);
		$data['rubro_conpresentacion'] = (is_null($data['rubro_conpresentacion']) ? 0 : 1);
		$data['rubro_conmarca'] = (is_null($data['rubro_conmarca']) ? 0 : 1);
		$data['rubro_conedad'] = (is_null($data['rubro_conedad']) ? 0 : 1);
		$data['rubro_conmedicados'] = (is_null($data['rubro_conmedicados']) ? 0 : 1);

		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}


	public function update($where, $data)
	{
		$data['rubro_conraza'] = (is_null($data['rubro_conraza']) ? 0 : 1);
		$data['rubro_contamanios'] = (is_null($data['rubro_contamanios']) ? 0 : 1);
		$data['rubro_conpresentacion'] = (is_null($data['rubro_conpresentacion']) ? 0 : 1);
		$data['rubro_conmarca'] = (is_null($data['rubro_conmarca']) ? 0 : 1);
		$data['rubro_conedad'] = (is_null($data['rubro_conedad']) ? 0 : 1);
		$data['rubro_conmedicados'] = (is_null($data['rubro_conmedicados']) ? 0 : 1);
				
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}


	public function delete_by_id($id)
	{
		$this->db->where('rubro_id', $id);
		$this->db->delete($this->table);
	}


	public function get_all()
	{       
		$this->db->select('rubro_id as id, rubro_nombre as nombre, rubro_conraza as conraza, rubro_contamanios as contamanios, rubro_conpresentacion as conpresentacion, rubro_conmarca as conmarca, rubro_conedad as conedad, rubro_conmedicados as conmedicados');		
		$this->db->from($this->table);
		$query = $this->db->get();

		return $query->result();
	}	


}

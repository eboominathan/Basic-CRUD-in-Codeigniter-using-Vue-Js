<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		
	}
	
	Public function insert()
	{
		$data = array('code' => $_POST['code'], 'name'=>$_POST['name']);
		return $this->db->insert('items',$data);

	}
	Public function edit($id)
	{
		return $this->db->get_where('items',array('id'=>$id))->row();
	}

	Public function update($id)
	{
		$data = array('code' => $_POST['code'], 'name'=>$_POST['name']);
		$where = array('id'=>$id);
		return $this->db->update('items',$data,$where);

	}

	Public function delete($data)
	{
		
		for ($i=0; $i <count($data) ; $i++) { 
			$this->db->delete('items',array('id' => $data[$i]->id));
		}
		return true;
		
	}
	Public function get_data()
	{
		return $this->db->get('items')->result();
	}

}

/* End of file  */
/* Location: ./application/models/ */
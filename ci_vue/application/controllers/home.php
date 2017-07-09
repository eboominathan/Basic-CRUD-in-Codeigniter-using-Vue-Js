<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('home_model','home');
	}

	Public function index()
	{
		$this->load->view('home');
	}

	Public function insert()
	{
		
		echo $this->home->insert();
	}
	Public function update($id)
	{
		
		echo $this->home->update($id);
	}
	Public function edit($id)
	{
		$data = $this->home->edit($id);
		echo json_encode($data);

	}
	public function delete()
	{
		$data = json_decode(file_get_contents("php://input"));   	             
		echo $this->home->delete($data);
		
	}
	Public function get_data()
	{
		$data = array();
		$data = $this->home->get_data();
		echo json_encode($data);

	}
	

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
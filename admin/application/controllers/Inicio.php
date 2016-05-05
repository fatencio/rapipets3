<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

	function __construct()
	{
		parent::__construct();		
		
		$this->load->model('usuario_model');	
		$this->load->helper('url');			
		$this->load->helper('form');
		$this->load->library('form_validation');		
	}


	function index(){

		$data = new stdClass();

		// Valida que el usuario este loggeado
		if(!isset($_SESSION['logged_in'])){			
			
			if(!isset($_POST['login-username'])){
				
				$data->login_error = false;

				$this->load->view('login', $data);
					
			}else{
					
				$username = $this->input->post('login-username');
				$password = $this->input->post('login-password');
				
				// login ok				
				if ($this->usuario_model->resolve_user_login($username, $password)) {
					
					$user_id = $this->usuario_model->get_user_id_from_username($username);
					$user    = $this->usuario_model->get_user($user_id);
					
					// inicializa sesion del usuario
					$_SESSION['user_id']      = (int)$user->id;
					$_SESSION['username']     = (string)$user->username;
					$_SESSION['logged_in']    = (bool)true;
					$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
					$_SESSION['is_admin']     = (bool)$user->is_admin;
					
					$this->inicio();
				
				// fallo el login	
				} else {
					
					$data->login_error = true;
					
					$this->load->view('login', $data);
					
				}
			}	
		}
		else{
		
			$this->inicio();
		}		
	}


	function inicio(){
	
		$data = new stdClass();
		
		//Asigno el nombre de usuario para mostrarlo en pantalla
		$data->usuario = $_SESSION['username'];


		//Vista que se carga en el container principal
		$this->load->view('index', $data);
	}	


	function logout(){
		
		$data = new stdClass();

		$_SESSION = array();  
        session_unset();
        session_destroy();  

        $this->load->view('redirect', $data);
	}	

}
?>
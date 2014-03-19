<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_admin_login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	
	public function index()
	{
		@session_start();
		
		$success=0;
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			if(!isset($_POST['email']) || !isset($_POST['password'])){
				$data['error_message']='Invalid email or password!';
				$this->load->view('admin_login',$data);
			}else{
				$email = $_POST['email'];
				$password = $_POST['password'];
				$password = md5($password);
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					if(strlen($password)>1){
				
						$check_login = $this->user_model->check_admin_login_authentication($email,$password);
					
						if(sizeof($check_login)>0){
							$_SESSION['admin_active']='iAmAdmin';
							header( 'Location: http://www.e-progress.gr/thessaloniki_appsforgreece/admin_console') ;
							return;
							
						}else{
							$data['error_message']='Invalid email or password!';
							$this->load->view('admin_login',$data);
						}
					}else{
						$data['error_message']='Invalid email or password!';
						$this->load->view('admin_login',$data);
					}
				}else{
					$data['error_message']='Invalid email or password!';
					$this->load->view('admin_login',$data);
				}
			}
		}else{
			$data['error_message']=1;
			$this->load->view('admin_login',$data);
		}

	}
}

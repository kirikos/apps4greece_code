<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_delete_photo extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('article_model');
		$this->load->model('photo_reportaz_model');
	}

	
	public function index()
	{
		@session_start();
		
		if(isset($_SESSION['admin_active'])){
		
			if($_SESSION['admin_active']=='iAmAdmin'){
		
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				
					if(!isset($_POST['reason']) || !isset($_POST['id'])){
					
						return 'problem';
					
					}else{
						
						$reason = $_POST['reason'];
						$photo = $_POST['id'];
						if(strlen($reason)<5){
							return 'problem';
						}
						
						if($photo<1){
							return 'problem';
						}
						
						$upd_data= array("deleted"=>1,"deleted_reason"=>$reason);
						$this->photo_reportaz_model->delete_photo($photo,$upd_data);
						
					}
				}else{
					return 'problem';
				}
			}else{
				return 'problem';
			}
		}else{
			return 'problem';
		}
	}
}

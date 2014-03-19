<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_send_notification extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('notifications_model');
		$this->load->model('photo_reportaz_model');
		$this->load->model('admin_actions_model');
	}

	
	public function index()
	{
		@session_start();
		
		if(!isset($_SESSION['admin_active'])){
			echo 'problem';
			return;
		}
		
		if($_SESSION['admin_active']!='iAmAdmin'){
			echo 'problem';
			return;
		}
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		
			if(!isset($_POST['id']) || !isset($_POST['whole_area']) || !isset($_POST['distance']) || !isset($_POST['title_officials']) || !isset($_POST['title_citizens'])){
		
				return 'problem';
			
			}else{
			
				$photo = $_POST['id'];
				$whole_area = $_POST['whole_area'];
				$distance = $_POST['distance'];
				$title_officials = $_POST['title_officials'];
				$title_citizens = $_POST['title_citizens'];
				
				if($photo<1){
					return 'problem';
				}
				
				$create_time = time();
				$create_time = date("Y-m-d H:i:s",$create_time);
				$insert_data = array("photo_reportaz_id"=>$photo,"state"=>0,"published_date_citizens"=>$create_time,"published_date_officials"=>$create_time);
				
				if($whole_area==1){
					$insert_data["whole_area_citizens"]=1;
					$insert_data["whole_area_officials"]=1;
				}else{
					$insert_data["whole_area_citizens"]=0;
					$insert_data["whole_area_officials"]=0;
					$insert_data["distance_citizens"]=$distance;
					$insert_data["distance_officials"]=$distance;
				}
				
				if(strlen($title_officials)>5){
					$insert_data["description_officials"] = $title_officials;
					$insert_data["officials"]=1;
				}else{
					$insert_data["description_officials"] ="";
					$insert_data["officials"]=0;
				}
				
				if(strlen($title_citizens)>5){
					$insert_data["description_citizens"] = $title_citizens;
					$insert_data["citizens"]=1;
				}else{
					$insert_data["description_citizens"] ="";
					$insert_data["citizens"]=0;
				}
				
				
				$this->admin_actions_model->insert_admin_actions($insert_data);
			}
		}else{
			echo 'problem';
			return;
		}
	}
}

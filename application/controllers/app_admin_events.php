<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_admin_events extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('photo_reportaz_model');
		$this->load->model('admin_actions_model');
	}

	
	public function index()
	{
		@session_start();
		if(!isset($_SESSION['admin_active'])){
			header( 'Location: http://www.e-progress.gr/thessaloniki_appsforgreece/admin_login' ) ;
			return;
		}
		
		if($_SESSION['admin_active']!='iAmAdmin'){
			header( 'Location: http://www.e-progress.gr/thessaloniki_appsforgreece/admin_login' ) ;
			return;
		}
		
		$exclude_photos = $this->admin_actions_model->get_photoId_admin_actions_all();
		
		$photos = array();
		$count_exc = count($exclude_photos);
		
		if($count_exc==0){
			$photos =$this->photo_reportaz_model->get_new_photo_reportaz();
		}else{
			$i=0;
			$ex_id = "";
			while($i<$count_exc){
				
				if($i==0){				
					$ex_id = $exclude_photos[$i]['photo_reportaz_id'];
					
				}else{
					$ex_id .=','.$exclude_photos[$i]['photo_reportaz_id'];
				}
				$i++;
			}
			
			$photos =$this->photo_reportaz_model->get_new_photo_reportaz($ex_id);
		}
		$data['photos']=$photos;
		
		$users_by_photo = array();
		if(count($photos)>0){
			$users_id = "";
			$i=0;
			$length_photos = count($photos);
			while($i<$length_photos){
				if($i==0){				
					$users_id = $photos[$i]['user_id'];
				}else{
					$users_id .=','.$photos[$i]['user_id'];
				}
				$i++;
			}
			
			$users_by_photo = $this->user_model->get_users($users_id);
		}
		$data['users_photos']=$users_by_photo;
		
		$this->load->view('event_console',$data);
		return;
	}
}

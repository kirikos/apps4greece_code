<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_add_article extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	
	public function index()
	{
		@session_start();
		
		if(isset($_SESSION['admin_active'])){
			if($_SESSION['admin_active']=='iAmAdmin'){
				$this->load->view('add_article');
				return;
			}
		}
		header( 'Location: http://www.e-progress.gr/thessaloniki_appsforgreece/admin_login' ) ;
		return;
		
	}
}

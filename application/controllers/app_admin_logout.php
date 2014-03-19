<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_admin_logout extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	
	public function index()
	{
		@session_start();
		$_SESSION['admin_active'] =" ";
		session_destroy();
		header( 'Location: http://www.e-progress.gr/thessaloniki_appsforgreece/admin_login') ;
		return;
	}
}

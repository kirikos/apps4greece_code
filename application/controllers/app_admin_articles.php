<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_admin_articles extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('photo_reportaz_model');
		$this->load->model('admin_actions_model');
		$this->load->model('article_model');
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
		$articles = $this->article_model->get_mobile_news(0,0);
		
		$data['articles'] = $articles;
		$this->load->view('articles',$data);
		return;
	}
}

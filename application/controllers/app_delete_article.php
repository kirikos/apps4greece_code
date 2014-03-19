<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_delete_article extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('article_model');
	}

	
	public function index()
	{
		@session_start();
		
		if(isset($_SESSION['admin_active'])){
			if($_SESSION['admin_active']=='iAmAdmin'){
				if ($_SERVER['REQUEST_METHOD'] === 'POST') {
					if(!isset($_POST['id'])){
					
						echo 'problem';
						return;
					
					}else{
					
						$article = $_POST['id'];
						
						if($article<1){
							return 'problem';
						}
						$this->article_model->delete_article($article);
						
					}
				}else{
				
					echo 'problem';
					return;
				}
			}else{
				echo 'problem';
				return;
			}
		}else{
			echo 'problem';
			return;
		}
	}
}

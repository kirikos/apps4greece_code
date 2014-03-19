<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_register_validation extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	
	public function index()
	{
		
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');
		
		if(!isset($_GET['email']) || !isset($_GET['password'])){
		
			$track = $xml->addChild('problem','email, password');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		$email = $_GET['email'];
		$password=$_GET['password'];
		$password = md5($password);
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		
				$check_user = $this->user_model->check_exist_inactive_user($email,$password);
				if(sizeof($check_user)>0){
				
					$track = $xml->addChild('done','found user');
					Header('Content-type: application/xml');
					print($xml->asXML());
					$this->user_model->activate_user($check_user[0]['id']);
					return;
				
				}else{
					
					$track = $xml->addChild('problem','user doesn\'t exist');
					Header('Content-type: application/xml');
					print($xml->asXML());
					return;
					
					
				}
				
		}else{
		
			$track = $xml->addChild('problem','This  email address isn\'t considered valid.');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
			
		}
		
	}
}

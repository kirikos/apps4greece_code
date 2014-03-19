<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_login extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	
	public function index()
	{
	
		$xml = new SimpleXMLElement('<xml  version="1.0" encoding="UTF-8"/>');
		$mTrack=$xml->addChild('login');
		if(!isset($_GET['email']) || !isset($_GET['password'])){
		
			$mTrack->addChild('problem','email, password');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		if(!isset($_GET['register'])){
		
			$mTrack->addChild('problem','register');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		$register=$_GET['register'];
		$email = $_GET['email'];
		$password=$_GET['password'];
		
		if($register!='0' && $register!='1'){
		
			$mTrack->addChild('problem','register value');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			
			if($register==1){
				
				$check_user_email = $this->user_model->check_exist_email($email);
				if(sizeof($check_user_email)>0){
					$mTrack->addChild('problem','email exist');
					Header('Content-type: application/xml');
					print($xml->asXML());
					return;
				}else{
					$password = md5($password);
					$create_time = time();
					$create_time = date("Y-m-d H:i:s",$create_time);
					$expire_time = time() + (24 * 60 * 60);
					$expire_time = date("Y-m-d H:i:s",$expire_time);
					$last_login = $create_time;
					$data =array(
						'email'=>$email,
						'password'=>$password,
						'role'=>0,
						'active'=>1,
						'create_date'=>$create_time,
						'expire_date'=>$expire_time,
						'last_login'=>$last_login
					);
					$to = $email;
					$subject = "Registration Email";
					$message = "Καλώς ήρθατε!
					Σας ενημερώνουμε πως η αίτηση εγγραφής σας έχει εγκριθεί.
					Για να ενεργοποιήσετε το λογαριασμό σας παρακαλώ πατήστε τον σύνδεσμο:
					http://www.e-progress.gr/thessaloniki_appsforgreece/register_validation?email=".$email."&password=".$password."
					 Προσοχή το email ενεργοποίησης θα είναι ενεργό για 1 ημέρα.
					Ευχαριστούμε!";
					$from = "support@e-progress.gr";
					$headers = "From:" . $from;
					//mail($to,$subject,$message,$headers);
					$this->user_model->insert_user($data);
					
				}
				
			}else{
			
				//check if exist email and password in User AND UPDATE USER
				$password = md5($password);
				$check_user_email = $this->user_model->check_login_authentication($email,$password);
				
				if(sizeof($check_user_email)>0){
					$last_login = time();
					$last_login = date("Y-m-d H:i:s",$last_login);
					$data=array('last_login'=>$last_login);
					$this->user_model->update_user($check_user_email[0]['id'],$data);
					
					$mTrack->addChild('id',$check_user_email[0]['id']);
					$mTrack->addChild('email',$check_user_email[0]['email']);
					$mTrack->addChild('role',$check_user_email[0]['role']);
					Header('Content-type: application/xml');
					print($xml->asXML());
					return;
				}else{
					$mTrack->addChild('violation','email or password invalid');
					Header('Content-type: application/xml');
					print($xml->asXML());
					return;
				}
				
			}
		}else{
		
			$mTrack->addChild('problem','This  email address isn\'t considered valid.');
			Header('Content-type: application/xml');
			print($xml->asXML());
			return;
		
		}
		
		$mTrack->addChild('done','all good');
		Header('Content-type: application/xml');
		print($xml->asXML());
	}
}

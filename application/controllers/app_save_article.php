<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_save_article extends CI_Controller {
	
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
				
					if(!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['category'])){
						;
					}else{
						
						$title = $_POST['title'];
						$description = $_POST['description'];
						$category = $_POST['category'];
						$t_title = trim($title," ");
						$t_description = trim($description," ");
						
						if($category<1 || $category>3){
							$data['error_message'] = "Δεν έχει επιλεχθεί κατηγορία";
							$this->load->view('add_article',$data);
							return;
						}
						if(strlen($t_title)<3){
							$data['error_message'] = "Ο τίτλος πρέπει να έχει τουλάχιστον 3 χαρακτήρες";
							$this->load->view('add_article',$data);
							return;
						}
						if(strlen($t_description)<10){
							$data['error_message'] = "Η περιγραφή πρέπει να έχει τουλάχιστον 10 χαρακτήρες";
							$this->load->view('add_article',$data);
							return;
						}
						
						
						$create_time = time();
						$create_time = date("Y-m-d H:i:s",$create_time);
						
						$ins_data=array("title"=>$title,"description"=>$description,"category"=>$category,"published_date"=>$create_time);
						
						if(strlen($_FILES['userfile']['type'])>2){
						
							$path_photo =str_replace(":","_",$create_time);
							$path_photo =str_replace(" ","_",$path_photo);
							$path_photo =str_replace("-","_",$path_photo);
							$path_photo ="photos/c".$category."_".$path_photo.".jpg";
		
							switch(strtolower($_FILES['userfile']['type']))
							{
								case 'image/jpeg':
									$image = imagecreatefromjpeg($_FILES['userfile']['tmp_name']);
									$type='jpg';
									break;
								case 'image/png':
									$image = imagecreatefrompng($_FILES['userfile']['tmp_name']);
									$type='png';
									break;
								case 'image/gif':
									$image = imagecreatefromgif($_FILES['userfile']['tmp_name']);
									$type='gif';
									break;
								default:
									redirect('http://www.e-progress.gr/thessaloniki_appsforgreece/admin_login');
							}

							// Target dimensions
							$max_width = 640;
							$max_height = 480;

							// Get current dimensions
							$old_width  = imagesx($image);
							$old_height = imagesy($image);

							// Calculate the scaling we need to do to fit the image inside our frame
							$scale      = min($max_width/$old_width, $max_height/$old_height);

							// Get the new dimensions
							$new_width  = ceil($scale*$old_width);
							$new_height = ceil($scale*$old_height);

							// Create new empty image
							$new = imagecreatetruecolor($new_width, $new_height);

							// Resize old image into new
							imagecopyresampled($new, $image,0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
						

							// Catch the imagedata
							ob_start();

							/*
							*	Set quality of image from 0 to 100(95)
							*/
							imagejpeg($new,$path_photo,95);
							$ins_data=array("photo"=>$path_photo,"title"=>$title,"description"=>$description,"category"=>$category,"published_date"=>$create_time);
						}				
						
						
						$this->article_model->insert_article($ins_data);
						$data['save_message'] = "Το άρθρο δημοσιεύθηκε επιτυχώς!";
						$this->load->view('add_article',$data);
						return;
					}
				}
			}
		}
		
		header( 'Location: http://www.e-progress.gr/thessaloniki_appsforgreece/admin_login' ) ;
		return;
		
	}
}

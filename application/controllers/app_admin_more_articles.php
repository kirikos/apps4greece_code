<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_admin_more_articles extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('article_model');
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
		if(!isset($_POST)){
			echo 'problem';
			return;
		}
		if(!isset($_POST['offset'])){
			echo 'problem';
			return;
		}
		
		$offset = $_POST['offset'];
		if($offset<0){
			echo 'problem';
			return;
		}
		$articles = $this->article_model->get_mobile_news(0,$offset);
		
		
		$count_articles = count($articles);
		if($count_articles==0){
			echo 'No Data.';
			return;
		}
		$i=$offset;
		
		$j=0;
		while($j<$count_articles){
			echo '<section class="container articles" style="margin: 30px auto; width:700px;">
			<div class="login" style="width:581px;">
			<div style="height:643px; margin: -20px -20px 0px;
			line-height: 40px;
			font-size: 15px;
			font-weight: bold;
			color: #555;
			text-align: center;
			text-shadow: 0 1px white;
			background: #f3f3f3;
			border-bottom: 1px solid #cfcfcf;
			border-radius: 3px 3px 0 0;
			background-image: -webkit-linear-gradient(top, whiteffd, #eef2f5);
			background-image: -moz-linear-gradient(top, whiteffd, #eef2f5);
			background-image: -o-linear-gradient(top, whiteffd, #eef2f5);
			background-image: linear-gradient(to bottom, whiteffd, #eef2f5);
			-webkit-box-shadow: 0 1px whitesmoke;
			box-shadow: 0 1px whitesmoke;">
			<div style="float:left;"><img id="p_'.$i.'" style="width:241px; float:left;" src="/'.$articles[$j]['photo'].'" />


			<div style="float: right;">
			<div style="width: 360px;margin-left: 5px;color: black; word-wrap: break-word; line-height: 2;">'.$articles[$j]['title'].'</div>
			<div style="float: right;width: 360px;word-wrap: break-word;text-align: left;height: 553px;margin-left: 5px;font-size: 14px; line-height: 1;">'.$articles[$j]['description'].'</div>

			</div><div style="color: rgb(41, 49, 139);">';

			if($articles[$j]['category']==1){
				echo 'Κοινωνία';
			}else if($articles[$j]['category']==2){
				echo 'Οικονομία';
			}else{
				echo 'Πολιτισμός';
			}

			echo '</div>
			</div>
			';


			echo '<div>
			<input onclick="delete_photo('.$articles[$j]['id'].')" style="color:red;" type="submit" name="commit" value="Delete">
			</div>
			</div>


			</section>';
			$j++;
			$i++;

		}
		return;
	}
}

?>